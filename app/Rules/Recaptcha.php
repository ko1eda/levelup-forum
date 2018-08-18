<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->submitRecaptcha(new Client, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please fill out the recaptcha and try again.';
    }

    /**
     * submitRecaptcha
     *
     * @param mixed $value value of recaptcha response token
     * @return void
     */
    protected function submitRecaptcha(Client $client, $value) : bool
    {
        // send the recaptcha response to google
        $recaptchaResult = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => config('services.recaptcha.secret'),
                'response' => $value,
                'remoteip' => request()->ip() // get the users ip
            ]
        ]);
        
        // return the boolean response for the success parameter of the returned json object
        return json_decode($recaptchaResult->getBody())->success;
    }
}
