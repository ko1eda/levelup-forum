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
     * Select activites with their respective subject (threads, replies, etc)
     * for a given user
     * From the last $days =3 days, ordered from newest to oldest
     *
     * Process w/ Eloquent :
     * Then group them by their created at date in the format (Day Name Date Month Year)
     * Then map over each group and take only the first $limit = 3 from each set.
     * Then return with current defaults a total of 9 items max 3 for each day
     *
     * @param User $user
     * @param int $limit
     * @return Collection
     */
    public static function feed(User $user, int $days = 3, int $limit = 3)
    {
        return static::with([
            'subject' => function ($q) {
                $q->withoutGlobalScopes(['user', 'favorites']);
            }
        ])
        ->where([
            ['user_id', $user->id],
            ['created_at', '>=', \Carbon\Carbon::today()->subDays($days)]
        ])
        ->latest()
        ->get()
        ->groupBy(function ($activity) {
            return $activity->created_at->format('l jS F Y');
        })
        ->map(function ($group) use ($limit) {
            return $group->take($limit);
        });
    }
}
