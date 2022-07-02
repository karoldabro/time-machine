<?php

namespace Kdabrow\TimeMachine\Tests;

use Illuminate\Support\Collection;
use Kdabrow\TimeMachine\Contracts\DatabaseTableInterface;
use Kdabrow\TimeMachine\Contracts\SelectorInterface;
use Kdabrow\TimeMachine\Contracts\TimeResolverInterface;
use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\Database\DefaultSelector;
use Kdabrow\TimeMachine\Database\Table;
use Kdabrow\TimeMachine\Engine;
use Kdabrow\TimeMachine\Result;
use Kdabrow\TimeMachine\Tests\Mocks\ClassThatExtendsModel;
use Kdabrow\TimeMachine\TimeTraveller;

class EngineTest extends TestCase
{
    /** @test */
    public function it_return_empty_array_when_not_found_any_row_to_change()
    {
        $mockResolver = \Mockery::mock(TimeResolverInterface::class);
        $mockSelector = \Mockery::mock(SelectorInterface::class);
        $mockSelector->shouldReceive('getRecords')->once()->andReturn(new Collection());
        $mockTable = \Mockery::mock(Table::class);
        $mockTable->shouldReceive('columnsToUpdate')->once()->andReturn(['column_1' => new Column('column_1')]);

        $travellers = [new TimeTraveller(new ClassThatExtendsModel())];

        $engine = new Engine($travellers, $mockResolver, $mockSelector, $mockTable, new Result());

        $this->assertEmpty($engine->start()->getAllSuccessful());
    }

    /** @test */
    public function it_not_modify_empty_columns()
    {
        $modelToFake = new ClassThatExtendsModel();
        $modelToFake->save();

        $mockResolver = \Mockery::mock(TimeResolverInterface::class);
        $mockResolver->shouldNotReceive('query');
        $mockSelector = \Mockery::mock(SelectorInterface::class);
        $mockSelector->shouldReceive('getRecords')->once()->andReturn(new Collection([$modelToFake]));
        $mockTable = \Mockery::mock(Table::class);
        $mockTable->shouldReceive('columnsToUpdate')->once()->andReturn(['column_1' => new Column('column_1')]);

        $travellers = [new TimeTraveller(new ClassThatExtendsModel())];

        $engine = new Engine($travellers, $mockResolver, $mockSelector, $mockTable, new Result());

        $result = $engine->start();

        $this->assertArrayHasKey(ClassThatExtendsModel::class, $result->getAllSuccessful());
        $this->assertCount(1, $result->getAllSuccessful()[ClassThatExtendsModel::class]);
        $this->assertEmpty( $result->getAllSuccessful()[ClassThatExtendsModel::class][0]['column_1']);
    }

    /** @test */
    public function it_run_resolver_query_on_column_to_select()
    {
        $modelToFake = new ClassThatExtendsModel();
        $modelToFake->column_1 = new \DateTime("2020-06-15 12:12:12");
        $modelToFake->save();

        $modifiedDate = new \DateTime("2020-06-10 12:12:12");

        $mockResolver = \Mockery::mock(TimeResolverInterface::class);
        $mockResolver->shouldReceive('query')->once()->andReturn($modifiedDate);
        $mockSelector = \Mockery::mock(SelectorInterface::class);
        $mockSelector->shouldReceive('getRecords')->once()->andReturn(new Collection([$modelToFake]));
        $mockTable = \Mockery::mock(Table::class);
        $mockTable->shouldReceive('columnsToUpdate')->once()->andReturn(['column_1' => new Column('column_1')]);

        $travellers = [new TimeTraveller(new ClassThatExtendsModel())];

        $engine = new Engine($travellers, $mockResolver, $mockSelector, $mockTable, new Result());

        $result = $engine->start();

        $this->assertArrayHasKey(ClassThatExtendsModel::class, $result->getAllSuccessful());
        $this->assertCount(1, $result->getAllSuccessful()[ClassThatExtendsModel::class]);
        $this->assertEquals($modifiedDate, $result->getAllSuccessful()[ClassThatExtendsModel::class][0]['column_1']);
    }

    /** @test */
    public function it_not_call_resolver_when_column_has_callback()
    {
        $modelToFake = new ClassThatExtendsModel();
        $modelToFake->column_1 = new \DateTime("2020-06-15 12:12:12");
        $modelToFake->save();

        $mockResolver = \Mockery::mock(TimeResolverInterface::class);
        $mockResolver->shouldNotReceive('query');
        $mockSelector = \Mockery::mock(SelectorInterface::class);
        $mockSelector->shouldReceive('getRecords')->once()->andReturn(new Collection([$modelToFake]));

        $modifiedDate = new \DateTime("2020-06-10 12:12:12");

        $column = new Column('column_1');
        $column->setCallback(function() use ($modifiedDate) {
            return $modifiedDate;
        });

        $mockTable = \Mockery::mock(Table::class);
        $mockTable->shouldReceive('columnsToUpdate')->once()->andReturn(['column_1' => $column]);

        $travellers = [new TimeTraveller(new ClassThatExtendsModel())];

        $engine = new Engine($travellers, $mockResolver, $mockSelector, $mockTable, new Result());

        $result = $engine->start();

        $this->assertArrayHasKey(ClassThatExtendsModel::class, $result->getAllSuccessful());
        $this->assertCount(1, $result->getAllSuccessful()[ClassThatExtendsModel::class]);
        $this->assertEquals($modifiedDate, $result->getAllSuccessful()[ClassThatExtendsModel::class][0]['column_1']);
    }


    /** @test */
    public function it_calls_callback_on_the_column_with_defined_callback()
    {
        $modelToFake = new ClassThatExtendsModel();
        $modelToFake->column_1 = new \DateTime("2020-06-15 12:12:12");
        $modelToFake->save();

        $modifiedDateColumn3 = new \DateTime("2020-06-20 12:12:12");

        $mockResolver = \Mockery::mock(TimeResolverInterface::class);
        $mockResolver->shouldReceive('query')->once()->andReturn($modifiedDateColumn3);

        $mockDatabaseTable = \Mockery::mock(DatabaseTableInterface::class);
        $mockDatabaseTable->shouldReceive('selectUpdatableFields')->once()->andReturn([
            'column_1' => new Column('column_1'),
            'column_3' => new Column('column_3'),
        ]);

        $modifiedDateColumn1 = new \DateTime("2020-06-10 12:12:12");

        $traveller = new TimeTraveller(new ClassThatExtendsModel());
        $traveller->alsoChange('column_1', function() use ($modifiedDateColumn1) {
            return $modifiedDateColumn1;
        });

        $engine = new Engine([$traveller], $mockResolver, new DefaultSelector(), new Table($mockDatabaseTable), new Result());

        $result = $engine->start();

        $this->assertArrayHasKey(ClassThatExtendsModel::class, $result->getAllSuccessful());
        $this->assertCount(1, $result->getAllSuccessful()[ClassThatExtendsModel::class]);
        $this->assertEquals($modifiedDateColumn1, $result->getAllSuccessful()[ClassThatExtendsModel::class][0]['column_1']);
        $this->assertEquals($modifiedDateColumn3, $result->getAllSuccessful()[ClassThatExtendsModel::class][0]['column_3']);
    }
}