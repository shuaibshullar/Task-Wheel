<?php

namespace App\Models;

use Exception;
use Database\Factories\PersonIdsFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[WithoutTimestamps()]
#[Fillable(['name'])]
#[Table('Assigned_personnel_ids')]
#[UseFactory(PersonIdsFactory::class)]
class Person extends Model
{
    /** @use HasFactory<PersonIdsFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts() { return []; }


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



    public static function add(string $name)
    {
        try{

            return static::create([
                'name' => $name
            ]);


        } catch (Exception $e) {

            return null;

        }
    }

    public static function getName(int $id): ?string
    {
        return static::find($id)?->name;
    }

    public static function getId(?string $name): ?int
    {
        if ($name === null ) return null;
        return static::whereLikeCi('name', $name)->value('id');
    }

    public static function getIdsByNames(?array $names): ?array
    {
        if ($names === null ) return null;
        return ($arr = static::whereInLike('name', $names)->pluck('id')->toArray()) == [] ? null : $arr;
    }

    public static function getNamesByIds(?array $ids): ?array
    {
        if ($ids === null ) return null;
        return ($arr = static::whereIn('id', $ids)->pluck('name')->toArray()) == [] ? null : $arr;
    }

    public static function del(int | string | null $id_or_name): bool
    {

        if ($id_or_name === null) return false;


        $person = is_string($id_or_name)
            ? static::whereLikeCi('name', $id_or_name)->first()
            : static::find($id_or_name);

        if ($person)
        {
            return $person->delete();
        }

        return false;
    }

    public static function changeName(int $id, string $name)
    {
        $person = static::find($id);

        $person->name = $name;
        $person->save();

        return $person;
    }

    /**
     * @return string[]|null
     */
    public static function getAll(): ?array
    {
        $arr =  static::query()->pluck('name')->toArray();
        return empty($arr) ? null : $arr;
    }


    /**
     * @return array<int, array<string, int>>|null
     */
    public static function getAllByNamesKey(): ?array
    {
        $arr =  static::query()->pluck('id', 'name')->mapWithKeys( fn($id, $name) => [

            strtolower($name) => $id

        ])->toArray();

        return empty($arr) ? null : $arr;
    }
}
