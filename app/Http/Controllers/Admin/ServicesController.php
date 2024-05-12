<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ServicesController extends Controller
{
    public function index()
    {
        return Inertia::render('Services/Index', [
            'services' => ServiceResource::collection(Service::latest()->simplePaginate(10))
        ]);
    }

    public function create()
    {
        return Inertia::render('Services/Create', [
            "edit" => false,
            "service" => (object)[]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', Rule::unique(Service::class)]
        ]);

        Service::create($data);

        return redirect()->route('services.index')
            ->with('success', 'Service stored successfully');
    }

    public function edit(Service $service)
    {
        return Inertia::render('Services/Create', [
            "edit" => true,
            "service" => new ServiceResource($service)
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', Rule::unique(Service::class)->ignore($service->id)]
        ]);

        $service->update($data);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully');
    }

    public function destroy(Service $service)
    {

        // Verifica si el servicio tiene asociados artículos
        if ($service->articles->count() > 0) {
            return redirect()->route('services.index')
            ->with('failure', 'Service cannot be deleted because it is associated with one or more articles.');
        }


        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully');
    }
}
