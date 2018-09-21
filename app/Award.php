<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Award extends Model
{
    /**
     * the user who is being awarded reputation
     *
     * @var App\User
     */
    protected $user;

    /**
     * the point values of each award
     *
     * @var array
     */
    protected $values = [
        'best_reply_marked' => 50,
        'best_reply_removed' => -50,
        'thread_created' => 10,
        'reply_created' => 2,
    ];

    /**
     * $fillable
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'value'];
    

    /**
     * __construct
     *
     * @param mixed $attributes
     * @param mixed User
     * @return void
     */
    public function __construct($attributes = [], User $user = null)
    {
        parent::__construct($attributes);

        $this->user = $user;
    }


    /**
     * When a model is created a user recieves the correct award
     *
     * @param Model $model
     * @return void
     */
    public function modelCreated(Model $model)
    {
        $sn = strtolower((new \ReflectionClass($model))->getShortName());

        $model->awardable()->create([
            'user_id' => $this->user->id,
            'type' => $sn . '_created',
            'value' => $this->values[$sn . '_created'],
        ]);
    }


    /**
     * Award the user for having the best reply,
     *
     * However, if a new best reply is selected,
     * subtract the reward from the previous user who had the best reply first,
     * then apply the new award.
     *
     * @param Model $model
     * @return void
     */
    public function bestReply(Reply $reply)
    {
        // If there is already a best reply for the thread, remove the points that user had been given
        if ($previousBestReply = Reply::find($reply->thread->best_reply_id)) {
            $previousBestReply->awardable()->create([
                'user_id' => $previousBestReply->user->id,
                'type' => 'best_reply_removed',
                'value' => $this->values['best_reply_removed'],
            ]);
        }

        $reply->awardable()->create([
            'user_id' => $this->user->id,
            'type' => 'best_reply_marked',
            'value' => $this->values['best_reply_marked'],
        ]);
    }
}
