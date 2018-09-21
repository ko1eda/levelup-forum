<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\POPO\Reputation;

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
        'password', 'remember_token', 'confirmation_token', 'role_id'
    ];

    /**
     * This will cast any attribute to the type
     * specified, that means when you access this
     * property in laravel even though it is stored
     * in the db as 0 or 1 it will be automatically cast
     * to boolean
     *
     * @var array
     */
    protected $casts = [
        'confirmed' => 'boolean'
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
     * a user has one role
     * 1: admin -> can do anything on the forum
     * 2: moderator -> can do many things especially in subforums
     * 3: standard -> normal user account standard privlages
     *
     * @return void
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    /**
     * return true if the user has any role
     * in the passed in array of roles
     *
    * @param array $roles
     * @return bool
     */
    public function hasRoles(array $roles) : bool
    {
        return $this->role()->whereIn('name', $roles)->exists();
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
     * A user can have many rewards, which earn them reputation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function awards()
    {
        return $this->hasMany(Award::class);
    }


    /**
     * Object to interface with user reputation.
     *
     * @return App\POPO\Reputation
     */
    public function reputation() : Reputation
    {
        return new Reputation($this);
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
        $numReplies = $this->replies()
            ->where('created_at', '>=', \Carbon\Carbon::now()->subMinutes($timeInMinutes))
            ->count();
        
        // if the number of of replies in the last x minutes is >= the allowed amount
        return $numReplies >= config('spam.repliesPerMinute') ? true : false ;
    }
}
