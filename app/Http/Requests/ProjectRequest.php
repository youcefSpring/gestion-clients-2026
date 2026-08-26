<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /** True when the form carries a brand-new customer instead of an existing one. */
    public function createsCustomer(): bool
    {
        return $this->input('customer_mode') === 'new';
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'customer_mode' => ['nullable', 'in:existing,new'],
            'customer_id' => [
                Rule::requiredIf(! $this->createsCustomer()),
                'nullable',
                // Only the signed-in user's own customers are selectable.
                Rule::exists('customers', 'id')->where('user_id', $this->user()?->id),
            ],
            'customer_name' => ['nullable', 'string', 'max:120'],
            'customer_phone' => [Rule::requiredIf($this->createsCustomer()), 'nullable', 'string', 'max:40'],
            'name' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_id' => __('app.customer'),
            'customer_name' => __('app.customer_name'),
            'customer_phone' => __('app.phone'),
            'name' => __('app.project_name'),
            'description' => __('app.description'),
            'status' => __('app.status'),
        ];
    }

    /** Project attributes only; the customer is resolved by the controller. */
    public function projectData(): array
    {
        return $this->safe()->only(['name', 'description', 'status']);
    }
}
