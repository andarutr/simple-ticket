<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Task;

class DashboardRepository implements DashboardRepositoryInterface
{
    protected $project;
    protected $task;

    public function __construct(Project $project, Task $task)
    {
        $this->project = $project;
        $this->task = $task;
    }

    public function getDashboardData()
    {
        $total_project = $this->project->count();
        $planning = $this->project->where('status', 1)->count();
        $on_progress = $this->project->where('status', 2)->count();
        $done = $this->project->where('status', 3)->count();
        $task = $this->task->count();

        $task_done = $this->task->where('status', 4)->count(); 
        $task_on_progress = $this->task->whereIn('status', [2, 3])->count();
        $task_planning = $this->task->where('status', 1)->count();

        $monthlyDoneTasks = $this->task
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->where('status', 4) 
            ->whereYear('created_at', date('Y')) 
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];

        $labels = [];
        $data = [];
        foreach ($monthlyDoneTasks as $item) {
            $labels[] = $months[$item->month] ?? 'Unknown';
            $data[] = $item->count;
        }

        $allMonths = array_values($months);
        $fullLabels = [];
        $fullData = [];

        foreach ($allMonths as $index => $label) {
            $key = array_search($label, $labels);
            $fullLabels[] = $label;
            $fullData[] = $key !== false ? $data[$key] : 0;
        }

        $task_monthly = [
            'labels' => $fullLabels,
            'data' => $fullData
        ];

        $latest_tasks = $this->task->latest()->limit(5)->get();

        return [
            'total_project' => $total_project,
            'planning' => $planning,
            'on_progress' => $on_progress,
            'done' => $done,
            'task' => $task,
            'task_done' => $task_done,
            'task_on_progress' => $task_on_progress,
            'task_planning' => $task_planning,
            'task_monthly' => $task_monthly,
            'latest_tasks' => $latest_tasks,
        ];
    }

    public function getProjectCounts() { }
    public function getTaskCounts() { }
    public function getMonthlyDoneTaskCounts() { }
    public function getLatestTasks($limit = 5) { }
}