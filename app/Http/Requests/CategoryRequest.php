<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use App\Models\Category;
use Illuminate\Contracts\Validation\Validator;

class CategoryRequest extends BaseFormRequest
{

    private bool $isCreate;
    private bool $isUpdate;

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
        $this->response = back()->withErrors($validator)->withInput()->with('categoryCreateBack', $this->isCreate)->with('categoryUpdateBack', $this->isUpdate);
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
            'category' => [

                'bail', 'string', 'max:255', 'required',
                function ($attribute, $value, $fail)
                {

                    if ( ! is_null($this->input('id')) ) // For update category
                    {
                        $this->isUpdate = true;
                        $this->isCreate = false;

                        $possibleCategory = Category::get((string) $value);
                        if ( ! is_null($possibleCategory)    &&    $possibleCategory->Id !== $this->id )
                        {
                            $fail($this->messages()['category.unique']);
                        }

                        return;
                    }

                    $this->isUpdate = false;
                    $this->isCreate = true;

                    $category = Category::get($value);

                    if ($category)
                    {
                        $fail($this->messages()['category.unique']);
                    }
                }

            ],
            'color'    => ['bail', 'required', 'string'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ( ! is_null($this->input('id')) )
            $this->merge([
                'id' => (int) $this->input('id'),
            ]);
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'category.string'      =>     'This field must be string.',
            'category.max'         =>     'This field is too long.',
            'category.required'    =>     'Give the category a name.',
            'category.unique'      =>     "Change the category's name (this name is used).",
        ];
    }
}
