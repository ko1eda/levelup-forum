<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public static function boot()
    {
        parent::boot();

        // When a user is created so is their profile
        // see eloquent model events for more
        // https://laravel.com/docs/5.6/eloquent#events
        static::created(function ($user) {
            $profile = new Profile;

            $profile->user_id = $user->id;

            $profile->save();
        });

        // When a user is deleted so is their profile
        // static::deleting(function ($user) {
        //     Profile::find($user->id)->firstOrFail()->delete();
        // });
    }


    /**
     * For Route model binding
     *
     * @return void
     */
    public function getRouteKeyName()
    {
        return 'username';
    }


    /**
     * Note this is a one to many relationship
     * Where as the others are a polymorphic one to many
     * A user has many activties, and an activity can be of
     * many different types
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }


    /**
     * Every User has a profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class)
            ->withoutGlobalScope('user');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    /**
     * Determine if the user has a reply in the database with a created_at timestamp
     * within a range of minutes UP TO the specified threshold time.
     *
     * If they do, return true (meaning they shouldn't be able to reply again until the time is up)
     *
     * If they do not return false.
     *
     * @param int (optional) $timeInMinutes
     * @return boolean
     */
    public function hasRepliedWithin(int $timeInMinutes = 1)
    {
        return $this->replies()
            ->where('created_at', '>=', \Carbon\Carbon::now()->subMinutes($timeInMinutes))
            ->exists();
    }
}
