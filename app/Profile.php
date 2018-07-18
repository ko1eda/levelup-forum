<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    protected $fillable = [
        'avatar_path',
        'profile_path',
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


    // public function getAvatarPathAttribute($avatar_path)
    // {
    //     return asset(
    //         $avatar_path ? "storage/{$avatar_path}" : 'https://imgplaceholder.com/192x192/cccccc/757575/fa-user'
    //     );
    // }
}
