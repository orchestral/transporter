<?php
namespace Orchestra\Transporter;

use Closure;

class Schema
{
    /**
     * Database connection.
     *
     * @var string|null
     */
    protected static $connection;

    /**
     * Set connection name.
     *
     * @param  string|null  $connection
     *
     * @return void
     */
    public static function on($connection)
    {
        static::$connection = $connection;
    }

    /**
     * Create migration schema.
     *
     * @param  string  $table
     * @param  \Closure  $callback
     *
     * @return \Orchestra\Transporter\Generator
     */
    public static function create($table, Closure $callback)
    {
        $blueprint = new Blueprint([
            'connection' => static::$connection,
            'table'      => $table,
            'key'        => 'id',
            'filter'     => null,
            'transport'  => null,
        ]);

        $callback($blueprint);

        return new Generator($blueprint);
    }
}
