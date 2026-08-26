<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $counts = Project::query()
            ->ownedBy($user)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard', [
            'totalCustomers' => Customer::ownedBy($user)->count(),
            'totalProjects' => (int) $counts->sum(),
            'newProjects' => (int) $counts->get(ProjectStatus::New->value, 0),
            'confirmedProjects' => (int) $counts->get(ProjectStatus::Confirmed->value, 0),
            'finishedProjects' => (int) $counts->get(ProjectStatus::Finished->value, 0),
            'cancelledProjects' => (int) $counts->get(ProjectStatus::Cancelled->value, 0),
            'latestProjects' => Project::ownedBy($user)->with('customer')->latest()->limit(8)->get(),
        ]);
    }
}
