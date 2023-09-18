<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class Workouts extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';

    protected $casts = [
        'categories' => 'json',
        'optional' => 'json',
    ];
}
