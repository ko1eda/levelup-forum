<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\SpamFilter;

class SpamFilterTest extends TestCase
{

    /** @test */
    public function it_validates_spam()
    {
        $filter = new SpamFilter();
        $keyNotOnList = 'Dogs r cool';
        $keyOnList = 'Yahoo customer Support';

        $this->assertFalse($filter->detect($keyNotOnList));

        $this->expectException(\Exception::class);
        
        $filter->detect($keyOnList);
    }
}
