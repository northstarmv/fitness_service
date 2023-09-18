<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin Builder
 */
class ClientNotes extends Model
{

    protected $guarded = [];
    protected $casts = [
        'active' => 'boolean',
    ];

    public function client(): HasOne
    {
        return $this->hasOne(User::class,'id','client_id');
    }

    public function trainer(): HasOne
    {
        return $this->hasOne(User::class,'id','trainer_id');
    }

}
