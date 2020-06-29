<?php

namespace Kdabrow\TimeMachine\Tests\Unit\Database\Drivers;

use Kdabrow\TimeMachine\Model;
use Illuminate\Container\Container;
use Kdabrow\TimeMachine\TimeTraveler;
use Kdabrow\TimeMachine\Database\Field;
use Kdabrow\TimeMachine\Tests\Unit\TestCase;
use Kdabrow\TimeMachine\Database\Drivers\MysqlDriver;

class MysqlDriverTest extends TestCase
{
    public function setUp(): void
    {
        // $container = Container::getInstance();
        // $container->instance(Model::class, $this->getMockBuilder(Model::class));

        parent::setUp();
    }

    public function test_if_all_updatableFields_are_returned()
    {
        $this->app->instance(Model::class, new Model());

        $model = new Model();

        $timeTraveler = new TimeTraveler($model);

        $driver = new MysqlDriver($timeTraveler);

        $arrayOfFields = $driver->findUpdatableColumns();

        $this->assertEquals([
            (new Field('date'))->setType('date'),
            (new Field('datetime'))->setType('datetime'),
            (new Field('timestamps'))->setType('timestamps'),
        ], $arrayOfFields);
    }
}
