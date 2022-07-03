<?php

namespace Kdabrow\TimeMachine\Tests\Database;

use Kdabrow\TimeMachine\Database\Column;
use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\Tests\TestCase;

class ColumnTest extends TestCase
{
    /** @test */
    public function it_merge_only_type_value_callback_columns()
    {
        $column1 = new Column('email_date');
        $column1->setCallback(function() {return 1;});
        $column1->setType('date');
        $column1->setValue('2020-06-15');


        $column2 = new Column('email_date');
        $column2->setCallback(function() {return 2;});
        $column2->setType('datetimetz');
        $column2->setValue('2020-06-15 12:12:12');

        $result = $column1->merge($column2);

        $this->assertEquals('email_date', $result->getName());
        $this->assertEquals('datetimetz', $result->getType());
        $this->assertEquals('2020-06-15 12:12:12', $result->getValue());
        $this->assertEquals(2, $result->getCallback()());
    }

    /** @test */
    public function it_throws_exception_when_are_merged_two_different_columns()
    {
        $this->expectException(TimeMachineException::class);

        $column1 = new Column('email_date_1');
        $column2 = new Column('email_date_2');

        $column1->merge($column2);
    }
}