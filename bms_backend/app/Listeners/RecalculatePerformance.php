<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Events\ProjectCompleted;
use App\Events\AttendanceRecorded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RecalculatePerformance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $employeeId = null;

        if ($event instanceof TaskCompleted) {
            $employeeId = $event->task->assigned_to;
        } elseif ($event instanceof ProjectCompleted) {
            $employeeId = $event->project->assigned_to;
        } elseif ($event instanceof AttendanceRecorded) {
            $employeeId = $event->attendance->employee_id;
        }

        if ($employeeId) {
            $employee = \App\Models\User::find($employeeId);
            if ($employee) {
                $employee->calculatePerformanceScore();
            }
        }
    }
}
