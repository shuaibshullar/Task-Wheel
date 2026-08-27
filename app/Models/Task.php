<?php

namespace App\Models;

use Exception;
use App\Supports\TaskSupports;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[WithoutTimestamps()]
#[Fillable(['name', 'description', 'category_id', 'deadline', 'assigned_personnel_id'])]
#[Table('Tasks')]
class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    public const date_format = 'Y-m-d';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts() { return [

        'deadline' => 'date:' . static::date_format,
        'assigned_personnel_id' => 'array'

    ];}



    public static function whereInLike(string $column, array $values)
    {
        $class = static::class;

        return $class::where(function($query) use ( $column, $values) {

            foreach ($values as $value)
            {
                $query->orWhereLike($column, $value, caseSensitive: false);
            }

        });

    }


    public static function whereLikeCi(string $column, mixed $value)
    {
        return static::whereLike($column, $value, caseSensitive: false);
    }




    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





    public static function add(string $name, ?string $description = null, ?int $categoryId = null, string $deadline, ?array $assignedPersonsIds = null)
    {
        try{

            // return static::create([
            //     'name' => $name,
            //     'description' => $description,
            //     'category_id' =>  $category ? Category::get($category)?->Id : null,
            //     'deadline' => $deadline,
            //     'assigned_personnel_id' => $assignedPersons == [] ? null : User::getIdsByNames($assignedPersons)
            // ]);

            return static::create([
                'name' => $name,
                'description' => $description,
                'category_id' =>  $categoryId ?: null,
                'deadline' => $deadline,
                'assigned_personnel_id' => $assignedPersonsIds ?: null
            ]);


        } catch (Exception $e) {

            return null;

        }
    }

    public static function del(int | string | null $id_or_name): bool
    {
        if ($id_or_name === null) return false;



        $task = is_string($id_or_name)
            ? static::whereLikeCi('name', $id_or_name)->first()
            : static::find($id_or_name);


        if ($task)
        {
            return $task->delete();
        }

        return false;
    }

    public static function modify(int | string | null $id_or_old_name, ?string $new_name = null, ?string $description = null, ?int $categoryId = null, ?string $deadline = null, ?array $assignedPersonsIds = null)
    {
        try{


            if ($id_or_old_name === null) return null;


            $task = is_string($id_or_old_name)
                ? static::whereLikeCi('name', $id_or_old_name)->first()
                : static::find($id_or_old_name);


            if (! $task) return null;


            // ! $new_name                  ?:         (              $task->name = $new_name                                                         );
            // ! $description               ?:         (              $task->description = $description                                               );
            // ! $category                  ?:         (              $task->category_id = Category::get($category)?->Id                              );
            // ! $deadline                  ?:         (              $task->deadline = $deadline                                                     );
            // ! $assignedPersons           ?:         (              $task->assigned_personnel_id = User::getIdsByNames($assignedPersons)            );


            ! $new_name                  ?:         (              $task->name = $new_name                                                         );
            ! $description               ?:         (              $task->description = $description                                               );
            ! $categoryId                ?:         (              $task->category_id = $categoryId ?: null                                        );
            ! $deadline                  ?:         (              $task->deadline = $deadline                                                     );
            ! $assignedPersonsIds        ?:         (              $task->assigned_personnel_id = $assignedPersonsIds ?: null                      );



            $task->save();


            return $task;


        } catch (Exception $e) {

            return null;

        }
    }

    public static function get(int | string | null $id_or_name): ?TaskSupports
    {


        if ($id_or_name === null) return null;



        $task = is_string($id_or_name)
            ? static::whereLikeCi('name', $id_or_name)->first()
            : static::find($id_or_name);


        if (! $task) return null;


        $id = is_string($id_or_name) ? $task->id : $id_or_name;


        return $task ? app(TaskSupports::class, ["Id" => $id, "task" => $task]) : null;
    }

    /**
     * @return array<int, object{id: int, title: string, description: string|null, category: string, deadline: string, assignees: string[]}>|null
     */
    public static function getAll(): ?array
    {
        $arr =  static::select(['id', 'name', 'description', 'category_id', 'deadline', 'assigned_personnel_id'])->get()->map(function ($task) {

            return (object) [

                'id'                           =>     $task?->id,
                'title'                        =>     $task?->name,
                'description'                  =>     $task?->description ?? null,
                'category'                     =>     Category::get($task?->category_id)?->Name ?? null,
                'deadline'                     =>     (string) $task?->deadline?->format(static::date_format),
                'assignees'                    =>     User::getNamesByIds($task?->assigned_personnel_id) ?? []

            ];

        })->toArray();

        return empty($arr) ? null : $arr;
    }

}
