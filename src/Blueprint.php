<?php namespace Orchestra\Transporter;

use Exception;
use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\DB;

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

        return $query;
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

        $source      = data_get($data, $this->get('key', 'id'));
        $destination = call_user_func($transport, $data, $this);

        if (is_null($destination)) {
            throw new Exception("[transport] need to return the inserted ID");
        }

        DB::table('transport_migrations')->insert([
            'name'        => $table,
            'source_id'   => $source,
            'destination' => $destination,
        ]);
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
}
