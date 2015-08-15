<?php namespace Orchestra\Transporter;

use Exception;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Blueprint extends Fluent
{
    /**
     * Get query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function getQueryBuilder()
    {
        $filter = $this->get('filter');
        $query  = DB::connection($this->get('connection'))->table($this->get('table'));

        if (is_callable($filter)) {
            call_user_func($filter, $query, $this);
        }

        $query->orderBy($this->get('key'));

        return $query;
    }

    /**
     * Get last migrated.
     *
     * @return object|null
     */
    public function getLastMigrated()
    {
        $result = DB::table('orchestra_transporter')
                    ->where('name', '=', $this->get('table'))
                    ->orderBy('source_id', 'desc')
                    ->first();

        return $result;
    }

    /**
     * Get migrated table key.
     *
     * @param  string  $table
     * @param  int  $id
     *
     * @return int
     */
    public function getMigratedKey($table, $id)
    {
        $result = DB::table('orchestra_transporter')
                    ->where('name', '=', $table)
                    ->where('source_id', '=', $id)
                    ->first();

        return data_get($result, 'destination_id', $id);
    }

    /**
     * Migrate data.
     *
     * @param  mixed $data
     *
     * @return void
     */
    public function migrate($data)
    {
        $transport = $this->get('transport');

        $source = data_get($data, $this->get('key', 'id'));
        $result = call_user_func($transport, $data, $this);

        $destination = $this->prepareTransformationAndGetKey($result);

        if (is_null($destination)) {
            throw new Exception('[transport] need to return the inserted ID');
        }

        $this->runCreateHook($source, $destination);
    }

    /**
     * Is Blueprint valid.
     *
     * @return bool
     */
    public function isValid()
    {
        return is_callable($this->get('transport'));
    }

    /**
     * Prepare if result from tranform callback is an Eloquent object, if sp
     * return the key.
     *
     * @param  mixed  $model
     *
     * @return mixed
     */
    protected function prepareTransformationAndGetKey($model)
    {
        if (! $model instanceof Model) {
            return $model;
        }

        if ($model->exists == false) {
            $model->save();
        }

        return $model->getKey();
    }

    /**
     * Run post create hook.
     *
     * @param  mixed  $source
     * @param  mixed  $destination
     * @return void
     */
    protected function runCreateHook($source, $destination)
    {
        DB::table('orchestra_transporter')->insert([
            'name'           => $this->get('table'),
            'source_id'      => $source,
            'destination_id' => $destination,
        ]);

        $listen = $this->get('listen');

        if (is_callable($listen)) {
            call_user_func($listen, $this, $source, $destination);
        }
    }
}
