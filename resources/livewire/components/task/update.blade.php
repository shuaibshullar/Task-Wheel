<?php

use Livewire\Component;
use App\Models\Category;
use App\Models\Task;
use Livewire\Attributes\Renderless;

new class extends Component
{
    #[Renderless]
    public function update()
    {
        $tasks = Task::getAll();
        $categories = Category::getAll();

        $this->dispatch('update',
            tasks: $tasks,
            categories: $categories,
        );
    }
}
?>
<div wire:poll.10s="update"></div>
