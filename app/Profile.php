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

        // this will delete any directory corresponding to a _path attribute on the profile
        // so avatar_path, for profile with id 3 would delete the directory avatars/3
        static::deleting(function ($profile) {
            foreach ($profile->getAttributes() as $key => $value) {
                if (preg_match('/[_]path$/', $key)) {
                    $temp = explode('/', $value);

                     // ex delete directory 'avatars/3
                    if (\File::exists(public_path() . '/storage/' . $temp[0] . '/' . $profile->user->id)) {
                        \File::deleteDirectory(public_path() . '/storage/' . $temp[0] . '/' . $profile->user->id);
                    }
                }
            };
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
