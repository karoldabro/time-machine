<?php

namespace Kdabrow\TimeMachine\Tests\Database;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Kdabrow\TimeMachine\Database\DefaultSelector;
use Kdabrow\TimeMachine\Result;
use Kdabrow\TimeMachine\Tests\Mocks\ClassThatExtendsModel;
use Kdabrow\TimeMachine\Tests\TestCase;
use Kdabrow\TimeMachine\TimeTraveller;

class DefaultSelectorTest extends TestCase
{
    /** @test */
    public function it_update_query_using_conditions_from_closure()
    {
        $defaultSelector = new DefaultSelector();

        $mockBuilder = \Mockery::mock(Builder::class);
        $mockBuilder->shouldReceive('get')->once()->andReturn(new Collection());

        $timeTraveller = new TimeTraveller(new ClassThatExtendsModel(), function($query, $updated) use ($mockBuilder) {
            return $mockBuilder;
        });
        $columns = ['column_1', 'column_2'];

        $records = $defaultSelector->getRecords($timeTraveller, $columns, new Result());

        $this->assertEmpty($records);
    }
}