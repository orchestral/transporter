<?php namespace Orchestra\Transporter\TestCase;

use Mockery as m;
use Orchestra\Transporter\Blueprint;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test Orchestra\Transporter\Generator::start() method.
     *
     * @test
     */
    public function testStartMethod()
    {
        $blueprint = new Blueprint();
        $stub = m::mock('\Orchestra\Transporter\Generator[migrate]', [$blueprint]);

        $stub->shouldReceive('migrate')->once()->andReturnNull();

        $this->assertNull($stub->start());
    }
}
