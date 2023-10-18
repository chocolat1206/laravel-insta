<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

     #To get all the posts of a user
     public function posts()
     {
         return $this->hasMany(Post::class);
     }
 
     #To get all the followers of a user
     public function followers()
     {
         return $this->hasMany(Follow::class, 'following_id');
     }

     #To get all the users that the user is following
     public function following()
     {
        return $this->hasMany(Follow::class, 'follower_id');
     }

     public function isFollowed()
     {
        return $this->followers()->where('follower_id', Auth::user()->id)->exists();
        //Auth::user()->id  is the follower_id
        //Firstly, get all the followers of the User ($this->followers()). Then, from that list, search for the Auth user from the follower columns (Where('follower_id', Auth::user()->id));
     }
}
