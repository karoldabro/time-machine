<?php

namespace Kdabrow\TimeMachine\Tests;

use Kdabrow\TimeMachine\Exceptions\TimeMachineException;
use Kdabrow\TimeMachine\Tests\Mocks\ClassNotExtendsModel;
use Kdabrow\TimeMachine\Tests\Mocks\ClassThatExtendsModel;
use Kdabrow\TimeMachine\TimeTraveller;

class TimeTravellerTest extends TestCase
{
    /** @test */
    public function it_throws_exception_when_not_eloquent_model_is_provided()
    {
        $this->expectException(TimeMachineException::class);

        new TimeTraveller(new ClassNotExtendsModel());
    }

    /** @test */
    public function it_throws_exception_when_a_string_class_do_not_implement_eloquent_model()
    {
        $this->expectException(TimeMachineException::class);

        new TimeTraveller(ClassNotExtendsModel::class);
    }

    /** @test */
    public function it_return_model_when_string_is_provided()
    {
        $traveller = new TimeTraveller(ClassThatExtendsModel::class);

        $this->assertInstanceOf(ClassThatExtendsModel::class, $traveller->getModel());
    }

    /** @test */
    public function it_accepts_eloquent_model()
    {
        $traveller = new TimeTraveller(new ClassThatExtendsModel);

        $this->assertInstanceOf(ClassThatExtendsModel::class, $traveller->getModel());
    }

    /** @test */
    public function it_do_not_accept_not_class_value()
    {
        $this->expectException(TimeMachineException::class);

        $traveller = new TimeTraveller(123);
    }

    /** @test */
    public function it_set_columns_as_object()
    {
        $traveller = new TimeTraveller(new ClassThatExtendsModel());
        $traveller->alsoChange('email', function(){});

        $this->assertArrayHasKey('email', $traveller->getColumns());
        $this->assertIsCallable($traveller->getColumns()['email']->getCallback());
    }

    /** @test */
    public function it_set_excludes_as_object()
    {
        $traveller = new TimeTraveller(new ClassThatExtendsModel());
        $traveller->exclude('email');

        $this->assertArrayHasKey('email', $traveller->getExcluded());
    }
}