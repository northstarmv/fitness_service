<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class UserBloodPressureData extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';
}
