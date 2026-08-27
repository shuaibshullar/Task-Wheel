<?php

namespace App;

use App\Models\Category;
use App\Models\Task;
use Closure;
use Illuminate\Container\Attributes\Scoped;
use Illuminate\Container\Attributes\Singleton;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

#[Singleton, Scoped]
class dataToJs
{
    private array $functions;


    public function __construct(private Request $request)
    {
        $methods    = new ReflectionClass($this)->getMethods(ReflectionMethod::IS_PRIVATE);

        $this->functions = collect($methods)->filter( fn($method) =>

            ! $method->isStatic()  &&  $method->isUserDefined()  &&  Str::doesntStartWith($method->getName(), '__')

        )->mapWithKeys( fn($method) => [

            $method->getName() => $method->getClosure($this)

        ])->toArray();
    }

    public function getMethods(): ?array
    {
        return $this->functions;
    }

    public function getClosure(string $function_name): ?Closure
    {
        return $this->functions[$function_name] ?? null;
    }

    ///////////////////////////////////////////////////////////                     Your Methods                     ///////////////////////////////////////////////////////////

    private function tasks()
    {
        $tasks      = Task::getAll();
        $categories = Category::getAll();

        return (object) [
            'tasks'      => $tasks,
            'categories' => $categories
        ];
    }
}
