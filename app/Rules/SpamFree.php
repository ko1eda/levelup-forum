<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Inspections\SpamManager;

class SpamFree implements Rule
{
    /**
     * $spam
     *
     * @var App\Inspections\SpamManager
     */
    protected $spam;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(SpamManager $spam)
    {
        $this->spam = $spam;
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
        return $this->translateSpamManagerResponse($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Spam was detected while reviewing this item';
    }

    /**
     * translates the return value from
     * the SpamManager into the correct boolean format
     *
     * @return void
     */
    protected function translateSpamManagerResponse($value)
    {
        try {
            // If no spam is found detect returns false
            // This means that the validator should return true
            if (!$this->spam->detect($value)) {
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}
