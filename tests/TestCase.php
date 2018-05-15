<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Turn off default html exception handling
     * for tests in favor of laravel exception handling
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    /**
     * Send a request to the given endpoint
     * as an Unauthorized user, passing in
     * any necessary data.
     * @return Illuminate\Foundation\Testing\TestCase
     */
    protected function checkUnauthFunctionality(
        String $requestType = 'get',
        String $endpoint = '',
        array $data = [],
        String $redirectRoute = 'login'
    ) {
        // Enable http exception handling
        $this->withExceptionHandling()
            ->$requestType($endpoint, $data)
            ->assertRedirect(route($redirectRoute));

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
