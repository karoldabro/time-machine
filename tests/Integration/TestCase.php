<?php

namespace Kdabrow\TimeMachine\Tests\Integration;

use PDO;
use Kdabrow\TimeMachine\Providers\TimeMachineProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->withFactories(__DIR__ . '/database/factories');

        $this->artisan('migrate', ['--database' => 'mysql']);
    }

    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TimeMachineProvider::class,
        ];
    }

    protected function compareModel($id, $date, $datetime, $timestamp, $emailVerifiedAt, $createdAt)
    {
        $dataToCompare = [
            'id' => $id,
            'date' => $date,
            'datetime' => $datetime,
            'timestamp' => $timestamp,
            'email_verified_at' => $emailVerifiedAt,
            'created_at' => $createdAt,
        ];

        $this->assertDatabaseHas('models', $dataToCompare);
    }

    protected function compareConnectedModelByModelId($modelId, $date, $datetime, $timestamp, $createdAt)
    {
        $dataToCompare = [
            'model_id' => $modelId,
            'date' => $date,
            'datetime' => $datetime,
            'timestamp' => $timestamp,
            'created_at' => $createdAt,
        ];

        $this->assertDatabaseHas('connected_models', $dataToCompare);
    }
}
