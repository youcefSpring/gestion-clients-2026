<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Http\Requests\ProjectRequest;
use App\Http\Requests\UpdateProjectStatusRequest;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        // Confirmed and cancelled projects are hidden by default so the list
        // only shows work that still needs attention.
        $showArchived = $request->boolean('show_archived') || filled($request->query('status'));

        $projects = Project::query()
            ->ownedBy($request->user())
            ->with('customer')
            ->search($request->query('search'))
            ->status($request->query('status'))
            ->visible($showArchived)
            ->when($request->query('customer'), fn ($query, $id) => $query->where('customer_id', $id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('projects.partials.table', compact('projects'))->render(),
            ]);
        }

        return view('projects.index', [
            'projects' => $projects,
            'customers' => Customer::ownedBy($request->user())->orderBy('name')->get(),
            'statuses' => ProjectStatus::cases(),
            'selectedCustomer' => $request->query('customer'),
            'showArchived' => $request->boolean('show_archived'),
        ]);
    }

    public function store(ProjectRequest $request): JsonResponse
    {
        $customer = $this->resolveCustomer($request);

        $project = $customer->projects()->create($request->projectData() + ['user_id' => $request->user()->id]);

        return response()->json([
            'message' => $request->createsCustomer()
                ? __('app.project_and_customer_created')
                : __('app.project_created'),
            'project' => $this->present($project),
            'customer' => $this->presentCustomer($customer),
        ], Response::HTTP_CREATED);
    }

    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        Gate::authorize('update', $project);

        $customer = $this->resolveCustomer($request);

        $project->update($request->projectData() + ['customer_id' => $customer->id]);

        return response()->json([
            'message' => __('app.project_updated'),
            'project' => $this->present($project),
            'customer' => $this->presentCustomer($customer),
        ]);
    }

    /** Uses the picked customer, or creates one from the inline fields. */
    private function resolveCustomer(ProjectRequest $request): Customer
    {
        if ($request->createsCustomer()) {
            return $request->user()->customers()->create([
                'name' => $request->validated('customer_name'),
                'phone' => $request->validated('customer_phone'),
            ]);
        }

        $customer = Customer::ownedBy($request->user())->findOrFail($request->validated('customer_id'));

        return $customer;
    }

    /** @return array<string, mixed> */
    private function presentCustomer(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'display_name' => $customer->display_name,
            'phone' => $customer->phone,
        ];
    }

    public function updateStatus(UpdateProjectStatusRequest $request, Project $project): JsonResponse
    {
        Gate::authorize('update', $project);

        $project->update($request->validated());

        return response()->json([
            'message' => __('app.status_changed'),
            'project' => $this->present($project),
        ]);
    }

    public function destroy(Project $project): JsonResponse
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return response()->json(['message' => __('app.project_deleted')]);
    }

    /** @return array<string, mixed> */
    private function present(Project $project): array
    {
        $project->loadMissing('customer');

        return [
            'id' => $project->id,
            'customer_id' => $project->customer_id,
            'name' => $project->name,
            'description' => $project->description,
            'status' => $project->status->value,
            'status_label' => $project->status->label(),
            'status_classes' => $project->status->classes(),
            'row_classes' => $project->status->rowClasses(),
            'archived' => in_array($project->status->value, ProjectStatus::archived(), true),
        ];
    }
}
