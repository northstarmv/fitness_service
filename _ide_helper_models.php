<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\User
 *
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\Authenticatable, \Illuminate\Contracts\Auth\Access\Authorizable {}
}

namespace App\Models{
/**
 * App\Models\UserHealthData
 *
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserHealthData newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserHealthData newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserHealthData query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserHealthData whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHealthData whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserHealthData whereUserId($value)
 */
	class UserHealthData extends \Eloquent {}
}

