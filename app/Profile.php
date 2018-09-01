<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Profile extends Model
{

    protected $fillable = [
        'avatar_path',
        'profile_photo_path',
        'banner_path',
        'hide_activities'
    ];


    public static function boot()
    {
        parent::boot();

        // delete all directories in s3 and on the local drive belonging to a user
        static::deleting(function ($profile) {
            \File::deleteDirectory(public_path() . '/storage/' . $profile->user->id);

            \Storage::disk('s3')->deleteDirectory($profile->user->id);
        });
    }

    /**
     * A profile belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * getAvatarPathAttribute
     *
     * @param mixed $avatar_path
     * @return void
     */
    public function getAvatarPathAttribute($avatar_path)
    {
        return $avatar_path ?? 'https://imgplaceholder.com/50x50/cccccc/757575/fa-user';
    }
    
    /**
     * getProfilePhotoPathAttribute
     *
     * @param mixed $profile_photo_path
     * @return void
     */
    public function getProfilePhotoPathAttribute($profile_photo_path)
    {
        return $profile_photo_path ?? 'https://imgplaceholder.com/450x450/cccccc/757575/fa-user';
    }
}
