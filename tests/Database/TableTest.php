<?php

namespace Kdabrow\TimeMachine\Tests\Database;

use Kdabrow\TimeMachine\Contracts\DatabaseTableInterface;
use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\Database\Table;
use Kdabrow\TimeMachine\Tests\Mocks\ClassThatExtendsModel;
use Kdabrow\TimeMachine\Tests\TestCase;
use Kdabrow\TimeMachine\TimeTraveller;

class TableTest extends TestCase
{
    /** @test */
    public function it_returns_updatable_fields_in_a_correct_array_shape()
    {
        $timeTraveller = new TimeTraveller(new ClassThatExtendsModel());

        $mockDatabase = \Mockery::mock(DatabaseTableInterface::class);
        $mockDatabase->shouldReceive('selectUpdatableFields')->once()->andReturn(['field_1' => new Column('filed_1'), 'field_2' => new Column('field_2')]);

        $table = new Table($mockDatabase);
        $columns = $table->columnsToUpdate($timeTraveller);

        $this->assertIsArray($columns);
        $this->assertArrayHasKey('field_1', $columns);
        $this->assertArrayHasKey('field_2', $columns);
        $this->assertNull($columns['field_1']->getCallback());
        $this->assertNull($columns['field_2']->getCallback());
    }

    /** @test */
    public function it_adds_to_result_additional_columns_with_a_callback()
    {
        $timeTraveller = new TimeTraveller(new ClassThatExtendsModel());
        $timeTraveller->alsoChange('field_2', function(){});

        $mockDatabase = \Mockery::mock(DatabaseTableInterface::class);
        $mockDatabase->shouldReceive('selectUpdatableFields')->once()->andReturn(['field_1' => new Column('filed_1')]);

        $table = new Table($mockDatabase);
        $fields = $table->columnsToUpdate($timeTraveller);

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('field_1', $fields);
        $this->assertArrayHasKey('field_2', $fields);
        $this->assertNull($fields['field_1']->getCallback());
        $this->assertIsCallable($fields['field_2']->getCallback());
    }

    /** @test */
    public function it_overwrite_selected_field_by_alsoChange_method()
    {
        $timeTraveller = new TimeTraveller(new ClassThatExtendsModel());
        $timeTraveller->alsoChange('field_1', function(){});

        $mockDatabase = \Mockery::mock(DatabaseTableInterface::class);
        $mockDatabase->shouldReceive('selectUpdatableFields')->once()->andReturn(['field_1' => new Column('field_1')]);

        $table = new Table($mockDatabase);
        $fields = $table->columnsToUpdate($timeTraveller);

        $this->assertIsArray($fields);
        $this->assertIsCallable($fields['field_1']->getCallback());
    }

    /** @test */
    public function it_removes_excluded_fields_from_result()
    {
        $timeTraveller = new TimeTraveller(new ClassThatExtendsModel());
        $timeTraveller->exclude('field_2');

        $mockDatabase = \Mockery::mock(DatabaseTableInterface::class);
        $mockDatabase->shouldReceive('selectUpdatableFields')->once()->andReturn(['field_1' => new Column('filed_1'), 'field_2' => new Column('field_2'), 'field_3' => new Column('field_3')]);

        $table = new Table($mockDatabase);
        $fields = $table->columnsToUpdate($timeTraveller);

        $this->assertIsArray($fields);
        $this->assertArrayHasKey('field_1', $fields);
        $this->assertArrayNotHasKey('field_2', $fields);
        $this->assertArrayHasKey('field_3', $fields);
    }
}