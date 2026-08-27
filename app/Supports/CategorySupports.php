<?php

namespace App\Supports;

class CategorySupports
{
    public ?string         $Name;
    public ?string         $Color;
    public ?int            $Radius;



    public function __construct(public int $Id, private object $category)
    {
        $this->Name       =    $category?->name;
        $this->Color      =    $category?->color;
        $this->Radius     =    $category?->radius;
    }
}
