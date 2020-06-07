<?php

namespace Kdabrow\TimeMachine\Tests\Unit\Finders;

use Kdabrow\TimeMachine\Finders\Column;
use Kdabrow\TimeMachine\Tests\Unit\TestCase;
use Kdabrow\TimeMachine\TimeTraveler;
use Mockery;
use stdClass;

class ColumnTest extends TestCase
{
    public function test_if_columns_are_correctly_filtered()
    {
        $columns = [];

        $columns[0] = new stdClass;
        $columns[0]->Field = 'one';
        $columns[0]->Type = 'date';

        $columns[1] = new stdClass;
        $columns[1]->Field = 'two';
        $columns[1]->Type = 'int';

        $columns[2] = new stdClass;
        $columns[2]->Field = 'three';
        $columns[2]->Type = 'timestamp';

        $columns[3] = new stdClass;
        $columns[3]->Field = 'four';
        $columns[3]->Type = 'datetime';

        $columns[4] = new stdClass;
        $columns[4]->Field = 'five';
        $columns[4]->Type = 'string';

        $timeTraveller = Mockery::mock(TimeTraveler::class);
        $timeTraveller->shouldReceive('getModel')->andReturn('ModelName');

        $columnFinder = new Column($timeTraveller);

        $this->assertEquals(['one', 'three', 'four'], $columnFinder->toUpdate());
    }
}
