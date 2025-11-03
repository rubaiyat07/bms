<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCompleted
{
    use Dispatchable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }
}
