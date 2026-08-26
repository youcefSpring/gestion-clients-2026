<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => 'password']
        );

        // Each account only ever sees its own customers and projects.
        $second = User::firstOrCreate(
            ['email' => 'sara@example.com'],
            ['name' => 'Sara', 'password' => 'password']
        );

        $this->seedFor($admin, 12);
        $this->seedFor($second, 4);
    }

    private function seedFor(User $user, int $customerCount): void
    {
        Customer::factory($customerCount)
            ->forUser($user)
            ->has(Project::factory()->count(fake()->numberBetween(0, 4))->state(['user_id' => $user->id]))
            ->create();

        // A customer without a name, to show the "Unnamed Customer" fallback.
        Customer::factory()->forUser($user)->unnamed()->create()
            ->projects()->create([
                'user_id' => $user->id,
                'name' => 'Quick quote',
                'description' => 'Called about a shop front sign.',
                'status' => ProjectStatus::New->value,
            ]);
    }
}
