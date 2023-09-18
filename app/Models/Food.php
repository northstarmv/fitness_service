<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * @mixin Builder
 */
class Food extends Model
{
    protected $connection = 'mysql';
    protected $guarded = [];
    protected $casts = [
        'ingredients'=>'json',
        'has_approved'=>'boolean'
    ];
}
