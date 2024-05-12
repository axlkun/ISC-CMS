<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    //
    public function index(Request $rquest)
    {
        $projectsCount = Project::count();
        $servicesCount = Service::count();

        $latestProject = Project::latest('created_at')->value('created_at') ? Project::latest('created_at')->value('created_at')->diffForHumans() : 'No records available';
        $latestService = Service::latest('created_at')->value('created_at') ? Service::latest('created_at')->value('created_at')->diffForHumans() : 'No records available';
        

        return Inertia::render('Dashboard', [
            'projectsCount' => $projectsCount,
            'servicesCount' => $servicesCount,
            'latestProject' => $latestProject,
            'latestService' => $latestService,
        ]);
    }
}
