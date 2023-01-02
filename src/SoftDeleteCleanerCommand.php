<?php
namespace FalconSW\SoftDeleteCleaner;

use Carbon\Carbon;
use Illuminate\Console\Command;
use ReflectionClass;
use ReflectionException;

class SoftDeleteCleanerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soft-delete:clean {test?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean soft delete expired records';

    /**
     * Execute the console command.
     *
     * @throws ReflectionException
     */
    public function handle(): void
    {

        // Enable Eloquent in Lumen
        if (function_exists('app') && method_exists(app(), 'withEloquent')) {
            app()->withEloquent();
        }

        // get all models in project
        $modelFiles = glob(app_path('Models') . '/*.php');

        $modelFiles = $this->argument('test') ? ["test"] : $modelFiles;

        foreach ($modelFiles as $modelFile) {

            // Get model class namespace and name
            $model = $modelFile === "test" ? $this->argument('test') : str_replace([app_path(), '/', '.php'], ['App', '\\', ''], $modelFile);

            // Check if model has soft delete
            if ((new ReflectionClass($model))->hasMethod('runSoftDelete')) {

                $object = new $model();

                if (!defined("$model::SOFT_DELETE_CLEANER_STATUS") || !$model::SOFT_DELETE_CLEANER_STATUS) {
                    continue;
                }

                if (!defined("$model::SOFT_DELETE_CLEANER_EXPIRE_TIME") || !$model::SOFT_DELETE_CLEANER_EXPIRE_TIME) {
                    $autoHardDeleteAfter = config('soft-delete-cleaner.expire_day');
                } else {
                    $autoHardDeleteAfter = $model::SOFT_DELETE_CLEANER_EXPIRE_TIME;
                }

                // Delete expired rows from database
                $count = $model::onlyTrashed()->where($object->getDeletedAtColumn(), '<=', Carbon::now()->sub($autoHardDeleteAfter))->forceDelete();

                if ($count) {
                    $this->line("Deleted $count expired rows from $model");
                } else {
                    $this->line("No expired rows found in $model");
                }
            }
        }

        $this->line('All done!');

    }
}
