<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];


    /**
     * subject
     *
     * @return void
     */
    public function subject()
    {
        return $this->morphTo();
    }


    /**
     * feed
     *
     * @param User $user
     * @param int $limit
     * @return Collection
     */
    public static function feed(User $user, int $limit = 10)
    {
        return static::with([
            'subject' => function ($q) {
                $q->withoutGlobalScopes(['user', 'favorites']);
            }
        ])
        ->where([
            ['user_id', $user->id],
            ['created_at', '>=', \Carbon\Carbon::today()->subDays(3)]
        ])
        ->latest()
        ->limit($limit)
        ->get()
        ->groupBy(function ($activity) {
            return $activity->created_at->format('l jS F Y');
        });
    }
}
