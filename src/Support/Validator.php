<?php

namespace Notimatica\ApiExceptions\Support;

class Validator extends \Illuminate\Validation\Validator
{
    /**
     * Add an error message to the validator's collection of messages.
     *
     * @param  string $attribute
     * @param  string $rule
     * @param  array $parameters
     * @return void
     */
    protected function addError($attribute, $rule, $parameters)
    {
        $message = $this->getMessage($attribute, $rule);
        $message = $this->doReplacements($message, $attribute, $rule, $parameters);

        $return  = [
            'rule' => snake_case($rule),
            'message' => $message,
        ];

        if (! empty($parameters) && ! in_array($rule, ['Unique', 'Exists'])) {
            $return['parameters'] = $parameters;
        }

        // Return rule with attribute type for complex size validators
        if (in_array($rule, $this->sizeRules)) {
            $return['rule'] .= '.' . $this->getAttributeType($attribute);
        }

        $this->messages->add($attribute, $return);
    }

    /**
     * Determine if the data passes the validation rules.
     *
     * @return bool
     */
    public function passes()
    {
        $this->messages = new MessageBag();

        // We'll spin through each rule, validating the attributes attached to that
        // rule. Any error messages will be added to the containers with each of
        // the other error messages, returning true if we don't have messages.
        foreach ($this->rules as $attribute => $rules) {
            foreach ($rules as $rule) {
                $this->validate($attribute, $rule);
            }
        }

        // Here we will spin through all of the "after" hooks on this validator and
        // fire them off. This gives the callbacks a chance to perform all kinds
        // of other validation that needs to get wrapped up in this operation.
        foreach ($this->after as $after) {
            call_user_func($after);
        }

        return count($this->messages->all()) === 0;
    }
}
