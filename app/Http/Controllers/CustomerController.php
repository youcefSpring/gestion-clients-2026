<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $customers = Customer::query()
            ->ownedBy($request->user())
            ->withCount('projects')
            ->search($request->query('search'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('customers.partials.table', compact('customers'))->render(),
            ]);
        }

        return view('customers.index', compact('customers'));
    }

    public function store(CustomerRequest $request): JsonResponse
    {
        $customer = $request->user()->customers()->create($request->validated());

        return response()->json([
            'message' => __('app.customer_created'),
            'customer' => $this->present($customer),
        ], Response::HTTP_CREATED);
    }

    public function update(CustomerRequest $request, Customer $customer): JsonResponse
    {
        Gate::authorize('update', $customer);

        $customer->update($request->validated());

        return response()->json([
            'message' => __('app.customer_updated'),
            'customer' => $this->present($customer),
        ]);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        Gate::authorize('delete', $customer);

        $customer->delete();

        return response()->json(['message' => __('app.customer_deleted')]);
    }

    /** Lightweight list used by the project form's customer picker. */
    public function options(Request $request): JsonResponse
    {
        $customers = Customer::query()
            ->ownedBy($request->user())
            ->search($request->query('search'))
            ->orderBy('name')
            ->limit(50)
            ->get()
            ->map(fn (Customer $customer) => $this->present($customer));

        return response()->json(['customers' => $customers]);
    }

    /** @return array<string, mixed> */
    private function present(Customer $customer): array
    {
        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'display_name' => $customer->display_name,
            'phone' => $customer->phone,
        ];
    }
}
