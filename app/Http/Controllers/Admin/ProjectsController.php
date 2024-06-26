<?php

namespace App\Http\Controllers\Admin;

use App\Actions\UploadFile;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ServiceResource;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProjectsController extends Controller
{
    public function index()
    {
        $projects = Project::with('services')->latest()->simplePaginate(10);

        return Inertia::render('Projects/Index', [
            'projects' => ProjectResource::collection($projects)
        ]);
    }

    public function create()
    {
        return Inertia::render('Projects/Create', [
            "edit" => false,
            "project" => new ProjectResource(new Project()),
            "services" => ServiceResource::collection(Service::select(['id', 'name'])->get())
        ]);
    }

    public function store(Request $request, UploadFile $uploadFile)
    {

        $data = $request->validate([
            'services' => ['required', 'array'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', Rule::unique(Project::class)],
            'image' => ['required', 'image', 'max:3000'],
            'summary' => ['required', 'string','max:255'],
            'location' => ['required', 'string'],
            'date' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        $data['image'] = $uploadFile->setFile($request->file('image'))
            ->setUploadPath((new Project())->uploadFolder())
            ->execute();

        $project = Project::create($data);

        // Asocia los servicios al proyecto
        $project->services()->sync($data['services']);

        return redirect()->route('projects.index')
            ->with('success', 'Proyecto creado');
    }

    public function edit(Project $project)
    {
        // Carga servicios asociados al project
        $project->load('services');

        return Inertia::render('Projects/Create', [
            "edit" => true,
            "project" => new ProjectResource($project),
            "services" => ServiceResource::collection(Service::select(['id', 'name'])->get()),
        ]);
    }

    public function update(Request $request, Project $project, UploadFile $uploadFile)
    {

        //dd($request->all());

        $data = $request->validate([
            'services' => ['required', 'array'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', Rule::unique(Project::class)->ignore($project->id)],
            'image' => ['nullable', 'image', 'max:3000'],
            'summary' => ['required', 'string','max:255'],
            'location' => ['required', 'string'],
            'date' => ['required', 'string'],
            'description' => ['required', 'string'],
        ]);

        $data['image'] = $project->image;

        if ($request->file('image')) {
            $project->deletePhoto();

            $data['image'] = $uploadFile->setFile($request->file('image'))
                ->setUploadPath($project->uploadFolder())
                ->execute();
        }

        $project->update($data);

        $project->services()->sync($data['services']);

        return redirect()->route('projects.index')
            ->with('success', 'Proyecto actualizado');
    }

    public function destroy(Project $project)
    {

        $project->deletePhoto();
        
        $project->services()->detach();
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Proyecto eliminado');
    }
}
