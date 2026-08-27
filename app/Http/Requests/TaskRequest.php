<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\BaseFormRequest;
use App\Models\Category;
use App\Models\User;

class TaskRequest extends BaseFormRequest
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
        $this->response = back()->withErrors($validator)->withInput()->with('taskCreateBack', $this->isCreate)->with('taskUpdateBack', $this->isUpdate);
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
            'title' => [
                'required',
                function ($attribute, $value, $fail)
                {
                    if ( ! is_null($this->id) ) // This when you update task
                    {
                        $this->isUpdate = true;
                        $this->isCreate = false;

                        $possibleTask = Task::get((string) $value);
                        if ( ! is_null($possibleTask)    &&    $possibleTask->Id !== $this->id )
                            
                            $fail($this->messages()['title.unique']);


                        return;
                    }

                    $this->isUpdate = false;
                    $this->isCreate = true;

                    $task = Task::get($value);

                    if ($task)
                        $fail($this->messages()['title.unique']);

                }
            ],
            'category'     =>       ['required'],
            // 'color'        =>       ['required'],
            'deadline'     =>       ['required', 'date_format:' . Task::date_format],
            'assignees'    =>       ['array', 'nullable']
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // 'assignees' => ( $assignees = $this->input('assignees') ) && ! is_array($assignees)
            //                     ? str($assignees)->explode(',')->map(
            //                             fn($i) => trim($i)
            //                         )->toArray()
            //                     : null ,

            'category'  => Category::get($this->input('category'))?->Id,
        ]);

        if ( ! is_null($this->id) )
            $this->merge(['id' => (int) $this->task()->id,]);
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [

            fn (Validator $validator) =>
                $validator->errors()->isEmpty()
                ? $this->NotFoundPersonInDB($validator)
                : null

        ];
    }

    private function NotFoundPersonInDB(Validator $validator)
    {
        $assignees                =     $this->task()->assignees        ?? [];
        $assigneesFromDB          =     User::getIdsByNames($assignees) ?? [];


        $assigneesCount           =     count($assignees);
        $assigneesCountFromDB     =     count($assigneesFromDB);



        if ($assigneesCount !== $assigneesCountFromDB) {

             $validator->errors()->add(
                key: 'assignees',
                message: $this->messages()['assignees'],
            );

        } else {

            $this->merge([
                'assignees' => $assigneesFromDB ?: null
            ]);

        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'              =>     'Give the task a designation.',
            'title.unique'                =>     "Change the task's name (this name is used).",
            'category.required'           =>     'The given category is not found or is not exist.',
            'assignees'                   =>     'Some of assignees is wrong.',
        ];
    }

    /**
     * @return object{id: int|null, title: string|null, description: string|null, category: string|null, color: string|null, deadline: string|null, assignees: string[]|null}
     */
    public function task(): object
    {
        return (object) [

            'id'             =>   $this?->id,
            'title'          =>   $this?->title,
            'description'    =>   $this?->description,
            'category'       =>   $this?->category,
            'color'          =>   $this?->color,
            'deadline'       =>   $this?->deadline,
            'assignees'      =>   $this?->assignees,

        ];
    }
}
