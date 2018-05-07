<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Channel;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_channel_has_threads()
    {
        // Given we have a channel
        // Should it have threads
        $channel = factory(Channel::class)->create();
        
        // then those thread should be in the form of a collection
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $channel->threads);
    }
}
