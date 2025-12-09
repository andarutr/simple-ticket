<?php

namespace App\Http\Controllers\Karyawan;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Karyawan\StoreTaskRequest;

class TaskController extends Controller
{
    public function getData()
    {
        $data = Task::with('project')->get();
        
        return response()->json(['data' => $data]);
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
