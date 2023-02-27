<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'status',
        'language',
        'bio',
        'url',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class)->orderBy('created_at', 'DESC');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function follows() // the user can follow many users
    {
        // the table that links the two models , the current foreign id , the other user foreign id
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }

    public function followers() // the user can be followed by many users
    {
        // the table that links the two models , the current foreign id , the other user foreign id
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'user_id');
    }

    public function following(User $user) // to check if a user is following another one
    {
        return $this->follows()->where('following_user_id', $user->id)->exists(); // if this user follow the $user return true
    }

    public function setAccepted(User $user) // accept the request automaticly if profile is public
    {
        if ($user->status == 'public') {
            DB::table('follows')
                ->where('user_id', $this->id)
                ->where('following_user_id', $user->id)
                ->update([
                    'accepted' => true
                ]);
        }
    }

    public function accepted(User $user) // to check if a user accepted another one
    {
        if ($this->status == 'public') {
            return true;
        } else {
            return (bool) DB::table('follows')
                ->where('user_id', $user->id)
                ->where('following_user_id', $this->id)
                ->where('accepted', true)->count(); // count will return 1 = true
        }
    }

    public function followRequest() // to send a follow requests
    {
        if ($this->status == 'private') {
            return $this->followers()
                ->where('following_user_id', $this->id)
                ->where('accepted', false)
                ->latest()->paginate(5);
        } else {
            return null;
        }
    }

    public function pendingFollowRequest() // pending Follow Requests
    {
        return $this->follows()
            ->where('user_id', $this->id)
            ->where('accepted', false)
            ->latest()->paginate(5);
    }

    public function followingAndAccepted(User $user) // to check accepted status of a following request
    {
        return $this->follows()
            ->where('following_user_id', $user->id)
            ->where('accepted', true)
            ->exists();
    }

    public function toggleAccepted(User $user, $status)
    {
        return DB::table('follows')
            ->where('user_id', $user->id)
            ->where('following_user_id', $this->id)
            ->update([
                'accepted' => $status
            ]);
    }

    public function home() // get the ids of my followings
    {
        // pluck get only the id
        $ids = $this->follows()->where('accepted', true)->get()->pluck('id'); // get the ids only of my followings that accepted the request
        return Post::whereIn('user_id', $ids)->latest()->paginate(20); // return the posts
    }

    public function iFollow() // get the latest list of people i follow
    {
        return $this->follows()
            ->where('user_id', $this->id)
            ->where('accepted', true)
            ->latest()->get();
    }

    public function otherUsers() // get a list of users that i did not follow yet
    {
        $i_follow = $this->iFollow()->pluck('id')->toArray(); // a list of list of people i follow
        $pending_follow = $this->pendingFollowRequest()->pluck('id')->toArray(); // a list of list of people i send request to
        array_push($i_follow, $this->id); // add current id to $iFollow

        $others = array_merge($i_follow, $pending_follow);

        return User::whereNotIn('id', $others)->latest()->get(); // return all users except others
    }

    public function explore() // get the ids of the users with public profile
    {
        $i_follow = $this->iFollow()->pluck('id')->toArray(); // a list of list of people i follow
        array_push($i_follow, $this->id); // add current id to $iFollow

        $public = User::where('status', 'private')->pluck('id')->toArray(); // users with public profile
        $others = array_merge($i_follow, $public);

        return Post::whereNotIn('user_id', $others)->latest()->paginate(50); // return all users that i dont follow with a public status
    }
}
