<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<\App\Models\Project> */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            // A project always belongs to the same user as its customer.
            'user_id' => fn (array $attributes) => Customer::find($attributes['customer_id'])->user_id,
            'name' => fake()->randomElement([
                'Website redesign', 'Mobile app', 'Online store', 'Logo design',
                'SEO package', 'Landing page', 'CRM integration', 'Maintenance contract',
            ]),
            'description' => fake()->optional()->sentence(10),
            'status' => fake()->randomElement(ProjectStatus::values()),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
            'customer_id' => Customer::factory()->forUser($user),
        ]);
    }
}
