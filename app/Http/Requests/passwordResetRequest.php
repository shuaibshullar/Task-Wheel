<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rules\Password;

class passwordResetRequest extends BaseFormRequest
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
        // $this->response = back()->withErrors($validator)->withInput();
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
            'token'       =>   ['bail', 'required', 'string'],
            'email'       =>   ['bail', 'required', 'email', 'exists:users,email', 'max:255'],
            'password'    =>   [
                'bail', 'required', 'min:8', 'confirmed', 'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
    }
}
