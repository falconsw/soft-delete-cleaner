<?php

return [
    /*
   |--------------------------------------------------------------------------
   | Soft Delete Cleaner
   |--------------------------------------------------------------------------
   |
   | This is the time interval that the soft deleted rows will be hard deleted after.
   | You can specify the time interval in the model itself.
   |
   */

    'expire_day' => env('SOFT_DELETE_CLEANER_EXPIRE_TIME', '60 days'),
];
