<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $total_project = Project::count();
        $planning = Project::where('status', 1)->count();
        $on_progress = Project::where('status', 2)->count();
        $done = Project::where('status', 3)->count();
        $task = Task::count();

        $task_done = Task::where('status', 4)->count(); 
        $task_on_progress = Task::whereIn('status', [2, 3])->count();
        $task_planning = Task::where('status', 1)->count();

        $monthlyDoneTasks = Task::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
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

        $latest_tasks = Task::latest()->limit(5)->get();

        return view('pages.dashboard', compact(
            'total_project',
            'planning',
            'on_progress',
            'done',
            'task',
            'task_done',
            'task_on_progress',
            'task_planning',
            'task_monthly',
            'latest_tasks'
        ));
    }
}
