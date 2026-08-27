<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
// use App\Models\Category;
// use App\Models\Person;
use App\Models\Task;
use Exception;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function __construct(
        // protected CategoryController             $category,
        // protected AssignedPersonnelController    $assignees,
    ){}


    public function add_and_edit(TaskRequest $request)
    {

        $task = $request->task();



        if ( is_null($task->id) )
            return $this->add($request);

        else
            return $this->edit($request);



        // return to_route('home')->withInput()->with('isBack', true);
        // return to_route('home')->withErrors([
        //     'title' => 'error'
        // ])->withInput()->with('isBack', true);

        //        @error('title')
        //            {{ $message }}
        //        @enderror

    }

    private function add(TaskRequest $request)
    {

        $task = $request->task();

        // $category_id = $this->setCategory($task);
        // $personsIds = $this->assignees->assignees($task->assignees);


        // $category   = Category::get($category_id)?->Name;
        // $assPersons = Person::getNamesByIds($personsIds);
        $new_task = Task::add(
            $task->title,
            $task->description,
            $task->category,
            $task->deadline,
            $task->assignees,
        );



        return back();
    }

    private function edit(TaskRequest $request)
    {
        $task = $request->task();



        // $category_id = $this->setCategory($task);
        // $personsIds = $this->assignees->assignees($task->assignees);


        // $category   = Category::get($category_id)?->Name;
        // $assPersons = Person::getNamesByIds($personsIds);
        $new_task = Task::modify(
            $task->id,
            $task->title,
            $task->description,
            $task->category,
            $task->deadline,
            $task->assignees,
        );


        return back();
    }

    // private function setCategory(object $taskobject): int
    // {
    //     if (! $this->category->isexist($taskobject->category))
    //     {
    //         $category_id = $this->category-> add(   $taskobject->category,     $taskobject->color   )?->id;
    //     } else {
    //         $category_id = $this->category->getId($taskobject->category);
    //     }


    //     $this->category->update(
    //         $category_id,
    //         $taskobject->category,
    //         $taskobject->color,
    //     );


    //     return $category_id;
    // }




    private const ERROR_CODE = 500;
    public function del(Request $request)
    {

        $id = $request?->id;
        if ( is_null($id) ) abort(self::ERROR_CODE);

        try {

            if ( Task::del((int) $id) ) {

                return back();

            } else {

                abort(self::ERROR_CODE);

            }

        } catch (Exception $e) {

            abort(self::ERROR_CODE);
        }
    }

}
