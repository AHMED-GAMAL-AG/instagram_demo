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

    public function likedByUser(User $user) // check if the post is liked by user
    {
        return (bool) DB::table('likes')
            ->where('user_id', $user->id) // specifies that only the rows with user_id equal to the id of the passed User object should be considered.
            ->where('post_id', $this->id) // specifies that only the rows with post_id equal to the id of the current object (a Post instance) should be considered.
            ->count(); // If a row is found, then the count method will return a value greater than zero, indicating that the user has liked the post.
    }


    // these three functions is replaced by toggle that laravel offers
    // public function likeSystem(User $user)
    // {
    //     return $this->likedByUser($user) ?  $this->dislike($user) :  $this->like($user);
    // }

    // public function like(User $user)
    // {
    //     return $this->likedByUsers()->save($user); // save the user_id and post_id to the likes table
    // }

    // public function dislike(User $user)
    // {
    //     return $this->likedByUsers()->detach($user); // remove the user_id and post_id from the likes table
    // }
}
