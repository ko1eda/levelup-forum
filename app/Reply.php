<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Favoritable;
use App\Traits\RecordActivity;

class Reply extends Model
{
    use Favoritable, RecordActivity;

    /**
     * $fillable
     *
     * @var array
     */
    protected $fillable = ['user_id', 'body'];


    /**
     * $withCount
     *
     * @var array
     */
    protected $withCount = ['favorites'];


    /**
     * $appends
     *
     * @var array
     */
    protected $appends = ['is_favorited'];


    /**
     * Collection of mentioned users in the replies body
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $mentionedUsers;


    /**
     * boot
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('favorites', function (Builder $builder) {
            $builder->with('favorites');
        });

        static::addGlobalScope('user', function (Builder $builder) {
            $builder->with('user');
        });

        // Fetch any mentioned users when a reply is created
        static::created(function ($reply) {
            $reply->mentionedUsers = $reply->resolveMentionedUsers();
        });
    }


    /**
     * This method checks for @mentions when a reply is being created.
     * Note: If you wanted to get the user names with the @ infront of them you would
     * just use userArr[0], preg_match_all returns full matches
     * in the first array and grouped in the second.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function resolveMentionedUsers()
    {
        $usersArr = array(array());

        preg_match_all('/@([A-Za-z0-9]+)/im', $this->body, $usersArr, PREG_PATTERN_ORDER);

        return User::whereIn('username', $usersArr[1])->get();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}