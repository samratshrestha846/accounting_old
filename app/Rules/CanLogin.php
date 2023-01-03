<?php

namespace App\Rules;

use App\Models\Lab\HospitalDesignation;
use Illuminate\Contracts\Validation\Rule;

class CanLogin implements Rule
{
    public $designationId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($designationId)
    {
        $this->designationId = $designationId;
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
        if ($this->checkLogin()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Password Field is required ,If User Can Login.';
    }
   
}
