<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

// use Illuminate\Http\Request;

class ViewTaskController extends Controller
{

    public function view(Request $request)
    {
        $categories = Category::getAll() ?? [];
        $assignees  = User::getAll() ?? [];
        $tasks      = Task::getAll() ?? [];

        View::share('categories', $categories);
        View::share('assignees', $assignees);
        View::share('tasks', $tasks);
        return view('home');
    }

}
