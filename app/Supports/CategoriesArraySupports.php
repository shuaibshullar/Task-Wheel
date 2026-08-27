<?php

namespace App\Supports;

use Illuminate\Database\Eloquent\Collection;

class CategoriesArraySupports
{
    public function __construct(private Collection $categories){}




    /**
     *  @return CategorySupports[]
     */
    public function getArray(): array
    {
        return $this->categories->map(


            fn($category) => app(CategorySupports::class, [

                'Id' => $category->id,
                'category' => $category

            ])


        )->toArray();
    }
}
