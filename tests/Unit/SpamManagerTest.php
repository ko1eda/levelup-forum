<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Inspections\SpamManager;
use App\Inspections\InvalidKeywords;
use App\Inspections\RepeatedCharacters;

class SpamManagerTest extends TestCase
{

    /** @test */
    public function it_checks_for_invalid_keywords()
    {
        // Given we have two keywords are entered into the spam filter
        $filter = app()->make(SpamManager::class);
    
        // One not on the spam filters blacklist
        $keyNotOnList = 'Dogs r cool';

        // And one on the spam filters blacklist
        $keyOnList = 'Yahoo customer Support';

        // Then the key not on the blacklist will return no spam
        $this->assertFalse($filter->detect($keyNotOnList));

        // However the key on the blacklist will cause an exception
        $this->expectException(\Exception::class);
        $filter->detect($keyOnList);
    }


    /** @test */
    public function it_checks_for_repeated_keypress()
    {
        // Create a new spam filter
        $filter = app()->makeWith(RepeatedCharacters::class, [null, $treshold = 2]);

        // If a message with repeated characters is run through the filter
        $repeatedStr = 'dude what the hell aaaaaaaaaaaaaaa uuuuuuuuuuuuuuuuuuu';
    
        // Then an exception will be thrown
        $this->expectException(\Exception::class);
        $filter->scan($repeatedStr);
    }
}
