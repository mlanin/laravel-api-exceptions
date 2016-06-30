<?php

namespace Lanin\Laravel\ApiExceptions\Support;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Lanin\Laravel\ApiExceptions\ValidationFailedApiException;

abstract class Request extends FormRequest
{
    /**
     * Return only those items that are registered in rules method.
     *
     * @return array
     */
    public function validatedOnly()
    {
        $rules = $this->container->call([$this, 'rules']);

        return $this->only(array_keys($rules));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     * @throws ValidationFailedApiException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ValidationFailedApiException($this->formatErrors($validator));
    }
}
