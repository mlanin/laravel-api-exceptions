<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions\Support;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Lanin\Laravel\ApiExceptions\ValidationFailedApiException;

abstract class Request extends FormRequest
{
    /**
     * Return only those items that are registered in rules method.
     */
    public function validatedOnly(): array
    {
        $rules = $this->container->call([$this, 'rules']);

        return $this->only(array_keys($rules));
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationFailedApiException($validator->getMessageBag()->toArray());
    }
}
