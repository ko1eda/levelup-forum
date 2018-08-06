<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $fillable = [
        'avatar_path',
        'profile_photo_path',
        'banner_path',
        'hide_activities'
    ];


    /**
     * A profile belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(App::User);
    }


    /**
     * getAvatarPathAttribute
     *
     * @param mixed $avatar_path
     * @return void
     */
    public function getAvatarPathAttribute($avatar_path)
    {
        return asset(
            $avatar_path ? "storage/{$avatar_path}" : 'https://imgplaceholder.com/50x50/cccccc/757575/fa-user'
        );
    }

    /**
     * getProfilePhotoPathAttribute
     *
     * @param mixed $profile_photo_path
     * @return void
     */
    public function getProfilePhotoPathAttribute($profile_photo_path)
    {
        return asset(
            $profile_photo_path ? "storage/{$profile_photo_path}" : 'https://imgplaceholder.com/450x450/cccccc/757575/fa-user'
        );
    }
}
