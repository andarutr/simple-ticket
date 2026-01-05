<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Repositories\ProjectRepositoryInterface;

class ProjectController extends Controller
{
    protected $projectRepository;

    public function __construct(ProjectRepositoryInterface $projectRepository) // Inject Repository
    {
        $this->projectRepository = $projectRepository;
    }

    
    public function getData()
    {
        $data = $this->projectRepository->getDataWithTaskCount();

        return response()->json(['data' => $data]);
    }

    public function index()
    {
        return view('pages.admin.project.index');
    }

    public function store(StoreProjectRequest $req)
    {
        $req->validated();

        $this->projectRepository->createProject($req->all());

        return response()->json(['message' => 'Berhasil menambahkan project'], 201);
    }

    public function edit($id)
    {
        $project = $this->projectRepository->findProjectById($id);

        if (!$project) {
            return response()->json(['message' => 'Project tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Berhasil mengambil data',
            'data' => $project
        ]);
    }

    public function update(StoreProjectRequest $req)
    {
        $project = $this->projectRepository->findProjectById($req->id);

        if (!$project) {
            return response()->json(['message' => 'Project tidak ditemukan'], 404);
        }

        $this->projectRepository->updateProject($req->id, $req->all());

        return response()->json(['message' => 'Berhasil mengupdate project'], 200);
    }

    public function destroy(Request $req)
    {
        $deleted = $this->projectRepository->deleteProject($req->id);

        if (!$deleted) {
            return response()->json(['message' => 'Project tidak ditemukan'], 404);
        }

        return response()->json(['message' => 'Berhasil menghapus project'], 200);
    }
}
