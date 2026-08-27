<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\BaseFormRequest;

class loginRequest extends BaseFormRequest
{
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
        $this->response = to_route('login_get')->withErrors($validator)->withInput()->with('error', true);
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'email'       =>   ['required', 'email'],
            'password'    =>   ['required', 'min:8', 'string'],

        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        //
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            //
        ];
    }

    public function isRememberMe(): bool
    {
        return $this->boolean('remember', false);
    }
}
