<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class UserWatchData extends Model
{
    protected $connection = 'mysql';
    protected $guarded = [];
    protected $casts = [
        'data'=>'json',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
