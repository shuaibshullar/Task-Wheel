<?php

namespace App\Models;

use App\Supports\CategoriesArraySupports;
use App\Supports\CategorySupports;
use Exception;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[WithoutTimestamps()]
#[Fillable(['name', 'color', 'radius'])]
#[Table('Categories')]
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
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
        return static::whereLike($column, $value, caseSensitive:  false);
    }




    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////





    public static function add(string $name, string $color, ?int $radius = null)
    {
        try{

            return static::create([
                'name' => $name,
                'color' => $color,
                'radius' => $radius
            ]);


        } catch (Exception $e) {

            return null;

        }
    }

    public static function del(int | string | null $id_or_name): bool
    {

        if ($id_or_name === null) return false;



        $category = is_string($id_or_name)
            ? static::whereLikeCi('name', $id_or_name)->first()
            : static::find($id_or_name);

        if($category)
        {
            return $category->delete();
        }

        return false;
    }

    public static function get(int | string | null $id_or_name): ?CategorySupports
    {

        if ($id_or_name === null) return null;

        $category = is_string($id_or_name)
            ? static::whereLikeCi('name', $id_or_name)->first()
            : static::find($id_or_name);


        if (! $category) { return null; }


        $id = is_string($id_or_name) ? $category->id : $id_or_name;



        return $category ? app(CategorySupports::class, ['Id' => $id, 'category' => $category]) : null;
    }

    // /**
    //  *  @return CategorySupports[]
    //  */
    // public static function getAllByArray(array $ids_or_names): ?array
    // {
    //     if (empty($ids_or_names)) { return null; }

    //     $categories = (is_string($ids_or_names[0]))
    //         ? static::whereInLike('name', $ids_or_names)->get()
    //         : static::whereInLike('id', $ids_or_names)->get();



    //     if (empty($categories)) { return null; }



    //     return app(CategoriesArraySupports::class, ['categories' => $categories])->getArray();
    // }

    /**
     * @return array<int, object{name: string, color: string, radius: int|null}>|null
     */
    public static function getAll(): ?array
    {
        $arr =  static::select(['name', 'color', 'radius'])->get()->mapWithKeys(
            fn($category) =>[

                $category->name => (object) [

                    'name'      =>    $category?->name,
                    'color'     =>    $category?->color,
                    'radius'    =>    $category?->radius,

                ]

            ]
        )->toArray();


        return empty($arr) ? null : $arr;
    }

    public static function modify(int | string | null $id_or_old_name, ?string $new_name = null, ?string $color = null, ?int $radius = null)
    {
        try {


            if ($id_or_old_name === null) return null;



            $category = is_string($id_or_old_name)
                ? static::whereLikeCi('name', $id_or_old_name)->first()
                : static::find($id_or_old_name);


            if (! $category) return null;


            ! $new_name                          ?:             (           $category->name = $new_name             );
            ! $color                             ?:             (           $category->color = $color               );
            ! $radius                            ?:             (           $category->radius = $radius             );


            $category->save();

            return $category;

        } catch (Exception $e) {

            return null;

        }
    }
}
