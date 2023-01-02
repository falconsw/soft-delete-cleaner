<?php

namespace FalconSW\SoftDeleteCleaner\Tests;

use Carbon\Carbon;
use Exception;
use FalconSW\SoftDeleteCleaner\SoftDeleteCleanerServiceProvider;
use FalconSW\SoftDeleteCleaner\Tests\database\Models\SampleModel;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;

class SoftDeleteCleanerTest extends TestCase
{

    use WithFaker;
    public function test(): void
    {

        $numberOfNotExpiredRows = $this->faker->numberBetween(1, 100);

        // create random date 1 to 14 days ago for not expired rows
        $this->createRow($numberOfNotExpiredRows, $this->faker->numberBetween(1, 14));
        // create random date 15 days ago for expired rows
        $this->createRow($numberOfNotExpiredRows, 15);
        // create random date 16 to 100 days ago for expired rows
        $this->createRow($numberOfNotExpiredRows, $this->faker->numberBetween(16, 100));

        // run command
        $this->artisan($this->command, ['test' => \FalconSW\SoftDeleteCleaner\Tests\database\Models\SampleModel::class]);

        // check trashed rows and not trashed rows
        self::assertEquals($numberOfNotExpiredRows, SampleModel::withTrashed()->count());
        // check trashed rows
        self::assertEquals($numberOfNotExpiredRows, SampleModel::onlyTrashed()->count());

    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->command = 'soft-delete:clean';
        $this->deleted_at = 'deleted_at';
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->withFactories(__DIR__.'/database/factories');
    }

    protected function getPackageProviders($app): array
    {
        return [
            SoftDeleteCleanerServiceProvider::class,
        ];
    }

    protected function createRow($number, $days): void
    {
        factory(SampleModel::class, $number)->create([$this->deleted_at => Carbon::now()->subDays($days)]);
    }


}
