<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\Project;
use Carbon\Carbon;

class TaskRepository implements TaskRepositoryInterface
{
    protected $task;
    protected $project;

    public function __construct(Task $task, Project $project)
    {
        $this->task = $task;
        $this->project = $project;
    }

    public function getDataWithProjectAndCounts()
    {
        $tasks = $this->task->with(['project' => function ($q) {
            $q->withCount(['task as total_tasks', 'task as completed_tasks' => function ($q2) {
                $q2->where('status', 4);
            }]);
            $q->with(['task' => function ($q3) {
                $q3->where('status', '<>', 4)
                   ->where('deadline', '<', Carbon::now());
            }]);
        }])->get();

        $formattedData = $tasks->map(function ($task) {
            $project = $task->project;
            $hasOverdueTask = false;
            if ($project) {
                $hasOverdueTask = $project->task->count() > 0;
            }
            $task->project->has_overdue_task = $hasOverdueTask;
            return $task;
        });

        return $formattedData;
    }

    public function getTaskSummary()
    {
        return $this->project->withCount([
            'task as planning_count' => function ($q) {
                $q->where('status', 1); 
            },
            'task as on_progress_count' => function ($q) {
                $q->where('status', 2); 
            },
            'task as done_count' => function ($q) {
                $q->where('status', 3); 
            }
        ])->get()->map(function ($project) {
            $total = $project->planning_count + $project->on_progress_count + $project->done_count;
            return [
                'name' => $project->name,
                'planning' => $project->planning_count,
                'on_progress' => $project->on_progress_count,
                'done' => $project->done_count,
                'total' => $total,
            ];
        })->sortByDesc('total')->values()->toArray();
    }

    public function getAllProjects()
    {
        return $this->project->all();
    }

    public function findTaskById($id)
    {
        return $this->task->find($id);
    }

    public function findTaskWithProject($id)
    {
        return $this->task->with('project')->find($id);
    }

    public function createTask(array $data)
    {
        return $this->task->create($data);
    }

    public function updateTask($id, array $data)
    {
        $task = $this->findTaskById($id);
        if ($task) {
            $task->update($data);
        }
        return $task;
    }

    public function deleteTask($id)
    {
        $task = $this->findTaskById($id);
        if ($task) {
            $task->delete();
            return true;
        }
        return false;
    }
}