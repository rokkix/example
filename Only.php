<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Only implements Rule
{
    public $parameters;

    public function __construct(?array $parameters = null)
    {
        $this->parameters = $parameters;
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
        if (empty($value)) {
            return false;
        }

        if (! empty($this->parameters)) {
            $value = array_merge($value, $this->parameters);
        }

        $j = count($value);

        for($i = 0; $i < $j; $i++) {
            $item = array_shift($value);
            if (in_array($item, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Названия :attribute не должны повторяться.';
    }
}
