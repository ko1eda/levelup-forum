<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\Favoritable;
use App\Traits\RecordActivity;
use Illuminate\Support\Facades\Redis;

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
    protected $appends = ['is_favorited', 'anchored_body'];


    /**
     * $touches
     *
     * @var array
     */
    protected $touches = ['thread'];


    /**
     * Collection of mentioned users in the replies body
     * This is used primarly for notifying users that they were mentiond
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
        
        // remove any best replies associated with the reply
        static::deleting(function ($reply) {
            $thread =  $reply->thread;
            // only remove if the deleted reply was the best
            if ($thread->best_reply_id === $reply->id) {
                $thread->best_reply_id = null;

                $thread->save();
    
                Redis::hdel('thread:' .$thread->id, 'best_reply');
            }
        });

        // Fetch any mentioned users when a reply is created
        static::created(function ($reply) {
            $reply->resolveMentions();
        });
    }


    /**
     * This method checks for @mentions when a reply is being created.
     * Note: If you wanted to get the user names with the @ infront of them you would
     * just use userArr[0], preg_match_all returns full matches
     * in the first array and grouped in the second.
     *
     * Note appended second where query to ensure that a user cannot
     * mention themselves in a reply and get a notification.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function resolveMentions()
    {
        $usersArr = array(array());

        preg_match_all('/@([A-Za-z0-9-]*\.?[^\s\n@.]+)/im', $this->body, $usersArr, PREG_PATTERN_ORDER);

        $this->mentionedUsers = User::whereIn('username', $usersArr[1])
        ->where('id', '<>', $this->user->id)
        ->get();

        return true;
    }


    /**
     * Purify the reply body so that no unwanted
     * tags are present when it is outputted
     *
     * Note: the anchored body below is also purified
     *
     * @return String
     */
    public function getBodyAttribute($body)
    {
        return \Purify::clean($body);
    }


    /**
     * Convert any body text with mentions to a profile link
     * This attribute is used in the threads.show views so that
     * when a reply is edited its mentions do not show up as links
     *
     * https://regex101.com/r/e2BEFI/1
     * @return String
     */
    public function getAnchoredBodyAttribute($body)
    {
        return preg_replace_callback('/(?<!\*\s)@([A-Za-z0-9-]*\.?[^\s\n@.]+)/im', function ($matches) {
            return "<a href=" .route('profiles.show', $matches[1]) .">{$matches[0]}</a>";
        }, $this->body);
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


    /**
     * A reply can award a user reputation points
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function awardable()
    {
        return $this->morphMany(Award::class, 'awardable');
    }
}
