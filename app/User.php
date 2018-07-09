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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * For Route model binding
     *
     * @return void
     */
    public function getRouteKeyName()
    {
        return 'name';
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
