<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Actions\UploadFile;

class ProjectsController extends Controller
{
    public function index(Request $request){
        $projects = Project::with(['services'])->latest()->simplePaginate($request->get('limit',10));

        return ProjectResource::collection($projects);
    }

    public function show(Project $project){
        $project->load(['services:name']);
        return new ProjectResource($project);
    }

    public function relatedProjects(Project $project)
    {
        $slug = $project->slug;

        $projects = Project::latest()
            ->where('slug', '!=', $slug)
            ->simplePaginate(3);

        return ProjectResource::collection($projects);
    }

    public function storeImage(Request $request, UploadFile $uploadFile){
        
        if($request->hasFile('upload')){
            $fileName = $uploadFile->setFile($request->file('upload'))
            ->setUploadPath((new Project())->uploadFolder())
            ->execute();

            $url = url("/storage/projects/{$fileName}");

            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);

        }
    }
}
