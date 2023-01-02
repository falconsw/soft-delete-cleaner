<?php

namespace FalconSW\SoftDeleteCleaner\Tests\database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampleModel extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    use SoftDeletes;
    public const SOFT_DELETE_CLEANER_STATUS = true;
    public const SOFT_DELETE_CLEANER_EXPIRE_TIME = '15 days';
}
