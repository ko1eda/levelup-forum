<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Thread;
use GuzzleHttp\Client;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_search_threads()
    {
        // set up laravel scout for this test,
        // by default the driver is set to null
        // so that every test operation (creates, updates, etc)
        // does not need to be indexed by elasticsearch (which will slow down our tests)
        config(['scout.driver'=> 'elasticsearch']);

        // given we have 4 threads
        $search = 'Quacktastic';

        // two threads that contain a keyword
        $matchingThreads = factory(Thread::class, 2)->create([
            'body' => "Thread with the {$search} term"
        ]);

        // and two threads that do not
        $nonMatchingThreads = factory(Thread::class, 2)->create();
        
        // this is to ensure that the asynchronous nature of updating our elasticsearch index
        // is accounted for because tests will reandomly fail sometimes because elasticsearch
        // may not index the threads quick enough
        $count = 0; // precautionary kill switch
        do {
            sleep(.25); // wait .25s between attempts
            
            $count++;

            $results = $this->json('GET', route('search.threads', "q={$search}"))->json();
        } while (empty($results['data']) && $count < 50);


        // then only the two threads contiaining that keyword should be returned
        $this->assertCount(2, $results['data']);
        
        // then only the two threads contiaining that keyword should be returned
        $this->assertEquals('Thread with the Quacktastic term', $results['data'][0]['body']);
        
        // use guzzle to remove the test index and all the threads it contained when the test is done
        $client = new Client;
        $client->delete(config('scout.elasticsearch.hosts')[0] . ":9200/test");
    }
}
