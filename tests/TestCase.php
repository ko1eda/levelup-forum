<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Send a request to the given endpoint
     * as an Unauthorized user, passing in
     * any necessary data.
     * @return Illuminate\Foundation\Testing\TestCase
     */
    protected function checkUnauthFunctionality(
        String $requestType = 'get',
        String $endpoint = '',
        array $data = []
    ) {
        $this->expectException(\Illuminate\Auth\AuthenticationException::class);
        
        $this->$requestType($endpoint, $data);

        return $this; // Return the instance for chaining
    }

    /**
     * Authenticate the passed in user,
     * if no user is provided, create one.
     *
     * @return Illuminate\Foundation\Testing\TestCase
     */
    protected function signInUser(User $user = null)
    {
        isset($user)
            ? $user
            : $user = create(User::class);

        $this->be($user);

        return $this;
    }
}
