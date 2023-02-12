<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Handle user data
 */
class User extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password'];

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id'];

	/**
	 * Roles relationship
	 */
	public function roles()
	{
		return $this->belongsToMany(Role::class, 'user_roles', 'id_user', 'id_role');
	}

	/**
	 * Check for the existance of a specific role
	 *
	 * @param  string  $name
	 * @return bool
	 */
	public function hasRole(string $name): bool
	{
		return $this->whereHas('roles', function (Builder $query) use ($name) {
			$query->where('id_user', $this->id);
			$query->where('name', $name);
		})->count() > 0;
	}

	/**
	 * Posts relationship
	 */
	public function posts()
	{
		return $this->hasMany(Post::class, 'id_author');
	}

}
