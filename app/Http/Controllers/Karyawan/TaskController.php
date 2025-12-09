<?php

namespace App\Http\Controllers\Karyawan;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Karyawan\StoreTaskRequest;

class TaskController extends Controller
{
    public function getData()
    {
        $tasks = Task::with(['project' => function ($q) {
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
        
        return response()->json(['data' => $formattedData]);
    }

    public function index()
    {
        return view('pages.karyawan.task.index');
    }

    public function create()
    {
        $projects = Project::all();

        return response()->json([
            'projects' => $projects
        ]);
    }

    public function store(StoreTaskRequest $req)
    {
        $req->validated();

        Task::create($req->all());

        return response()->json(['message' => 'Berhasil menambahkan task'], 201);
    }

    public function edit(Request $req, $id)
    {
        $task = Task::with('project')->find($id);
        $projects = Project::all();

        if (!$task) {
            return response()->json(['message' => 'Task tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Berhasil mengambil data',
            'data' => $task,
            'projects' => $projects
        ]);
    }

    public function update(StoreTaskRequest $req)
    {
        $task = Task::find($req->id);

        if (!$task) {
            return response()->json(['message' => 'Task tidak ditemukan'], 404);
        }

        $task->update($req->all());

        return response()->json(['message' => 'Berhasil mengupdate task'], 200);
    }

    public function destroy(Request $req)
    {
        $task = Task::find($req->id);

        if (!$task) {
            return response()->json(['message' => 'Task tidak ditemukan'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Berhasil menghapus task'], 200);
    }
}
