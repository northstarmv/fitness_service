<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class UserMiscData extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';
    protected $casts = [
        'misc_data' => 'json',
    ];

}
