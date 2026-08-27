<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\BaseFormRequest;
use App\Models\User;
use Illuminate\Validation\Rules\Password;

class registerRequest extends BaseFormRequest
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
    protected function setResponse(Validator $validator): void
    {
        $this->response = back()->withErrors($validator)->withInput();
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

            'name'        =>   [

                'bail', 'required', 'string', 'max:255', 'unique:users,name',
                function($attribute, $value, $fail) // Ci unique
                {

                    $possibleUser = User::get($value);

                    if ($possibleUser) $fail($this->messages()['name.unique']);

                },

            ],
            'email'       =>   ['bail', 'required', 'email', 'unique:users,email', 'max:255'],
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

            'name.required'         =>   'This field is required.',
            'name.unique'           =>   'This name is used already.',
            'name.string'           =>   'This field must be string.',
            'name.max'              =>   'This field is too long.',

            'email.required'        =>   'This field is required.',
            'email.email'           =>   'This field must be email.',
            'email.unique'          =>   'This email is used already.',
            'email.max'             =>   'This email is too long.',

            // 'password.required'     =>   'This field is required.',
            // 'password.min'          =>   'This field is too short.',
            // 'password.confirmed'    =>   'The password confirmation is failed.',
            // 'password.string'       =>   'This field must be string.',

        ];
    }
}
