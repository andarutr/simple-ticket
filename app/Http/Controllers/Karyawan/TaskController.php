<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepositoryInterface;
use App\Http\Requests\Karyawan\StoreTaskRequest;

class TaskController extends Controller
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository) // Inject Repository
    {
        $this->taskRepository = $taskRepository;
    }
    
    public function getData()
    {
        $formattedData = $this->taskRepository->getDataWithProjectAndCounts();
        
        return response()->json(['data' => $formattedData]);
    }

    public function index()
    {
        return view('pages.karyawan.task.index');
    }

    public function summary()
    {
        $task_summary = $this->taskRepository->getTaskSummary();

        return view('pages.karyawan.task.summary', compact('task_summary'));
    }

    public function create()
    {
        $projects = $this->taskRepository->getAllProjects();

        return response()->json([
            'projects' => $projects
        ]);
    }

    public function store(StoreTaskRequest $req)
    {
        $req->validated();

        $this->taskRepository->createTask($req->all());

        return response()->json(['message' => 'Berhasil menambahkan task'], 201);
    }

    public function edit(Request $req, $id)
    {
        $task = $this->taskRepository->findTaskWithProject($id);
        $projects = $this->taskRepository->getAllProjects();

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
        $task = $this->taskRepository->findTaskById($req->id);

        if (!$task) {
            return response()->json(['message' => 'Task tidak ditemukan'], 404);
        }

        $this->taskRepository->updateTask($req->id, $req->all());

        return response()->json(['message' => 'Berhasil mengupdate task'], 200);
    }

    public function destroy(Request $req)
    {
        $deleted = $this->taskRepository->deleteTask($req->id);

        if (!$deleted) {
            return response()->json(['message' => 'Task tidak ditemukan'], 404);
        }

        return response()->json(['message' => 'Berhasil menghapus task'], 200);
    }
}
