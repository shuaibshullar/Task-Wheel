<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function add_and_edit(CategoryRequest $request)
    {
        if ( $request->id === null)
            return $this->add($request);
        else
            return $this->edit($request);
    }


    public function add(CategoryRequest $request)
    {
        $category = Category::add(
            name:  $request->category,
            color: $request->color,
        );

        if ($category)
            return back();
        else
            abort(500);
    }

    public function del(Request $request)
    {
        //
    }

    public function edit(CategoryRequest $request)
    {
        //
    }

    // private ?int $category_id;



    // public function add(string $category_name, string $color, ?int $radius = null)
    // {
    //     return Category::add($category_name, $color, $radius);
    // }

    // public function getId(?string $category_name = null): ?int
    // {
    //     if ($this->category_id) return $this->category_id;



    //     return $this->isexist($category_name)
    //               ? $this->category_id
    //               : null;
    // }

    // public function isexist(?string $category_name): bool
    // {

    //     if (! $category_name) return false;

    //     $this->category_id = Category::get($category_name)?->Id;

    //     if(! $this->category_id) return false;

    //     return true;
    // }

    // public function update(int $id, ?string $name = null, ?string $color = null, ?int $radius = null)
    // {
    //     return Category::modify($id, $name, $color, $radius);
    // }
}
