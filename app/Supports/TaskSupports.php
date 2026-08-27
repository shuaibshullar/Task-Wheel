<?php

namespace App\Supports;

use App\Models\Category;
use App\Models\User;

class TaskSupports
{
    public ?string                $Name;
    public ?string                $Description;
    public ?string                $Category;
    public ?string                $Deadline;
    public ?array                 $AssignedPersons;



    public function __construct(public int $Id, private object $task)
    {
        $this->Name                  =   $task?->name;
        $this->Description           =   $task?->description;
        $this->Category              =   Category::get($task?->category_id)?->Name;
        $this->Deadline              =   $task?->deadline;
        $this->AssignedPersons       =   User::getNamesByIds($task?->assigned_personnel_id);

    }
}
