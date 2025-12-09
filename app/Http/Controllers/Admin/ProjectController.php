<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function getData()
    {
        $data = Project::all();
        
        // Sementara, abis ini install yajra!
        return response()->json(['data' => $data]);
    }

    public function index()
    {
        return view('pages.admin.project.index');
    }

    public function store(StoreProjectRequest $req)
    {
        $req->validated();

        Project::create($req->all());

        return response()->json(['message' => 'Berhasil menambahkan project'], 201);
    }

    public function edit(Request $req, $id)
    {
        $project = Project::find($id);

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
        $project = Project::find($req->id);

        if (!$project) {
            return response()->json(['message' => 'Project tidak ditemukan'], 404);
        }

        $project->update($req->all());

        return response()->json(['message' => 'Berhasil mengupdate project'], 200);
    }

    public function destroy(Request $req)
    {
        $project = Project::find($req->id);

        if (!$project) {
            return response()->json(['message' => 'Project tidak ditemukan'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Berhasil menghapus project'], 200);
    }
}
