<?php

use Illuminate\Database\Seeder;
use App\Thread;
use App\Reply;

class ThreadsTableSeeder extends Seeder
{
    /**
     * Create 50 threads, 50 users and 500 replies
     *
     * @return void
     */
    public function run()
    {
        $threads = factory(Thread::class, 50)->create();

        $threads->each(function ($thread) {

            factory(Reply::class, 10)->create([
                'thread_id' => $thread->id
            ]);
        });
    }
}
