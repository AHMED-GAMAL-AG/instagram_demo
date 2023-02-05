<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'post_caption',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // post has many likes by many users
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function like(User $user)
    {
        return $this->likedByUsers()->save($user); // save the user_id and post_id to the likes table
    }

    public function dislike(User $user)
    {
        return $this->likedByUsers()->detach($user); // remove the user_id and post_id from the likes table
    }

    public function likedByUser(User $user) // check if the post is liked by user
    {
        return (bool) DB::table('likes')
            ->where('user_id', $user->id)
            ->where('post_id', $this->id)
            ->count();
    }


    public function likeSystem(User $user)
    {
        return $this->likedByUser($user) ?  $this->dislike($user) :  $this->like($user);
    }
}
