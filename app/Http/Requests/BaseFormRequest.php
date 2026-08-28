<?php

namespace App\Http\Requests;

use App\Casts\Lowercase;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Contracts\Validation\Validator;

class BaseFormRequest extends FormRequest {


    protected ?Response $response = null;


    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {

        $this->setResponse($validator);

        $exception = $validator->getException();

        if (! $this->response) {

            throw (new $exception($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());

        } else {

            throw (new $exception($validator, $this->response))->errorBag($this->errorBag);

        }

    }

    /**
     * Get the validator instance for the request.
     *
     * @return Validator
     */
    protected function getValidatorInstance(): Validator
    {
        if ($this->has('email') && is_string($this->email))
            $this->merge([
                'email' => Lowercase::runCast($this->email),
            ]);

        return parent::getValidatorInstance();
    }

    /**
     * Flush the cached response instance.
     *
     * This resets the internal response state to null, ensuring that any
     * subsequent validation lifecycle or request pipeline execution
     * triggers a fresh response generation.
     *
     * @return void
     */
    protected function setResponse(Validator $validator)
    {
        // $this->response = back()->withErrors($validator)->withInput();
    }

}
