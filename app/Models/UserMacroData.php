<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class UserMacroData extends Model
{
    protected $guarded = [];
    protected $connection = 'mysql';

    public $casts = [
        'override' => 'boolean',
    ];

    public function user():HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
