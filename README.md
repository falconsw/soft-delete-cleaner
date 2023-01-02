# Laravel Soft Delete Cleaner

[![GitHub release](https://img.shields.io/github/release/falconsw/soft-delete-cleaner.svg?include_prereleases&style=for-the-badge&&colorB=7E57C2)](https://packagist.org/packages/falconsw/soft-delete-cleaner)
[![GitHub issues](https://img.shields.io/github/issues/falconsw/soft-delete-cleaner.svg?style=for-the-badge)](https://github.com/falconsw/soft-delete-cleaner/issues)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=for-the-badge&&colorB=F27E40)](license.md)
[![Total Downloads](https://img.shields.io/packagist/dt/falconsw/soft-delete-cleaner.svg?style=for-the-badge)](https://packagist.org/packages/falconsw/soft-delete-cleaner)

This package deletes soft deleted rows automatically after a time interval that you define.



## Installation
### Step 1
Require the package with composer using the following command:
```bash
composer require falconsw/soft-delete-cleaner
```
### Step 2
The service provider will automatically get registered. Or you may manually add the service provider in your `config/app.php` file:
```php
'providers' => [
    // ...
    \FalconSW\SoftDeleteCleaner\SoftDeleteCleanerServiceProvider::class,
];
```

### Step 3
Now its the time for scheduling the command.
in you `app/Console/Kernel.php` file, paste this code in `schedule()` function:
```php
protected function schedule(Schedule $schedule)
{
    // ...
    $schedule->command(\FalconSW\SoftDeleteCleaner\SoftDeleteCleanerCommand::class)->hourly();
    // ...
}
```
In the code above, the command scheduled to run hourly. you can change it. For more information, please read [this](https://laravel.com/docs/scheduling#scheduling-artisan-commands) page.

### Step 4 (Optional)
You can publish the config file with this following command:
```bash
php artisan vendor:publish --provider="FalconSW\SoftDeleteCleaner\SoftDeleteCleanerServiceProvider" --tag=config
```


Also you can set the `SOFT_DELETE_CLEANER_EXPIRE_TIME` value in `.env` file. like the following code:

```.env
...
SOFT_DELETE_CLEANER_EXPIRE_TIME='1 day'
...
``` 

## Usage
in your models that used `SoftDeletes` trait, you can enable Soft Delete Cleaner with this code:
```php
class SampleModel extends Model
{
    use SoftDeletes;
    const SOFT_DELETE_CLEANER_STATUS = true;
}
```
Just write `const SOFT_DELETE_CLEANER_STATUS = true` in your models!
Also you can set expiration time for your deleted entities using the following line:
```php
const SOFT_DELETE_CLEANER_EXPIRE_TIME = '5 months';
```
In the code above, expiration time for your soft deleted entity model is 5 months.
The final code is:
```php
class SampleModel extends Model
{
    use SoftDeletes;
    const SOFT_DELETE_CLEANER_STATUS = true;
    const SOFT_DELETE_CLEANER_EXPIRE_TIME = '5 months';
}
```
You can set any other values for `SOFT_DELETE_CLEANER_EXPIRE_TIME` like `5`(means 5 days), `2 hours`, `45 days`, `2.5 months`, `1 year`, etc.

**Note:** If you don't set any value for `SOFT_DELETE_CLEANER_EXPIRE_TIME` in your model, the soft deleted models with `SOFT_DELETE_CLEANER_STATUS = true` will be hard deleted after the time defined in config file named `auto-hard-deleter.php`.

## Soft Delete Cleaner Command
Also you can hard delete expired rows manually using this artisan command:
```bash
php artisan soft-delete:clean
```
