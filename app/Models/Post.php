<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['title', 'content', 'featured_image', 'id_author'];

	/**
	 * Author relationship
	 */
	public function author()
	{
		return $this->belongsTo(User::class, 'id_author');
	}

}
