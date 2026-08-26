<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    private function actingAsUser(): static
    {
        $this->actingAs($this->user);

        return $this;
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('customers.index'))->assertRedirect(route('login'));
    }

    public function test_customers_page_lists_customers(): void
    {
        $customer = Customer::factory()->forUser($this->user)->create(['name' => 'Amine', 'phone' => '111222']);

        $this->actingAsUser()->get(route('customers.index'))
            ->assertOk()
            ->assertSee('Amine')
            ->assertSee('111222');
    }

    public function test_search_returns_rendered_rows(): void
    {
        Customer::factory()->forUser($this->user)->create(['name' => 'Amine', 'phone' => '111222']);
        Customer::factory()->forUser($this->user)->create(['name' => 'Sonia', 'phone' => '333444']);

        $response = $this->actingAsUser()
            ->getJson(route('customers.index', ['search' => '3334']));

        $response->assertOk();
        $this->assertStringContainsString('Sonia', $response->json('html'));
        $this->assertStringNotContainsString('Amine', $response->json('html'));
    }

    public function test_customer_can_be_created(): void
    {
        $this->actingAsUser()
            ->postJson(route('customers.store'), ['name' => null, 'phone' => '555000'])
            ->assertCreated()
            ->assertJsonPath('customer.phone', '555000');

        $this->assertDatabaseHas('customers', [
            'phone' => '555000',
            'name' => null,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_phone_is_required(): void
    {
        $this->actingAsUser()
            ->postJson(route('customers.store'), ['name' => 'No phone'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('phone');
    }

    public function test_customer_can_be_updated(): void
    {
        $customer = Customer::factory()->forUser($this->user)->create();

        $this->actingAsUser()
            ->putJson(route('customers.update', $customer), ['name' => 'Renamed', 'phone' => '999'])
            ->assertOk();

        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'Renamed', 'phone' => '999']);
    }

    public function test_deleting_a_customer_deletes_their_projects(): void
    {
        $customer = Customer::factory()->forUser($this->user)
            ->has(Project::factory()->count(2)->state(['user_id' => $this->user->id]))
            ->create();

        $this->actingAsUser()
            ->deleteJson(route('customers.destroy', $customer))
            ->assertOk();

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
        $this->assertDatabaseCount('projects', 0);
    }

    public function test_customers_of_other_users_are_not_listed(): void
    {
        Customer::factory()->forUser($this->user)->create(['name' => 'Mine']);
        Customer::factory()->create(['name' => 'Theirs']);

        $this->actingAsUser()->get(route('customers.index'))
            ->assertOk()
            ->assertSee('Mine')
            ->assertDontSee('Theirs');
    }

    public function test_another_users_customer_cannot_be_updated_or_deleted(): void
    {
        $foreign = Customer::factory()->create();

        $this->actingAsUser()
            ->putJson(route('customers.update', $foreign), ['name' => 'Hacked', 'phone' => '000'])
            ->assertForbidden();

        $this->actingAsUser()
            ->deleteJson(route('customers.destroy', $foreign))
            ->assertForbidden();

        $this->assertDatabaseHas('customers', ['id' => $foreign->id]);
    }
}
