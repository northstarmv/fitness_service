<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
/**
 * @mixin Builder
 */
class User_Client extends Model
{
    protected $connection = 'accounts';
    protected $guarded = [];
    protected $primaryKey = 'user_id';
    protected $casts = [
        'health_conditions' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }

    public function diet_trainer(): HasOne
    {
        return $this->hasOne(User::class, 'id','diet_trainer_id');
    }

    public function physical_trainer(): HasOne
    {
        return $this->hasOne(User::class, 'id','physical_trainer_id');
    }
}
