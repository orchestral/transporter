<?php namespace Orchestra\Transporter;

use RuntimeException;

class Generator
{
    /**
     * Schema blueprint.
     *
     * @var \Orchestra\Transporter\Blueprint
     */
    protected $blueprint;

    /**
     * Construct a new generator.
     *
     * @param  \Orchestra\Transporter\Blueprint  $blueprint
     */
    public function __construct(Blueprint $blueprint)
    {
        $this->blueprint = $blueprint;
    }

    /**
     * Migrate by chunk.
     *
     * @param  int  $chunk
     *
     * @return void
     */
    public function migrate($chunk = 200)
    {
        $blueprint = $this->blueprint;

        if (! $blueprint->isValid()) {
            throw new RuntimeException('[transport] is not callable!');
        }

        $query = $blueprint->getQueryBuilder();

        $query->chunk($chunk, function ($collection) use ($blueprint) {
            foreach ($collection as $data) {
                $blueprint->migrate($data);
            }
        });
    }
}
