<?php

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_projects_page_renders(): void
    {
        Project::factory()->forUser($this->user)->create(['name' => 'Website redesign', 'status' => 'new']);

        $this->get(route('projects.index'))->assertOk()->assertSee('Website redesign');
    }

    public function test_project_can_be_created_with_only_a_customer_and_status(): void
    {
        $customer = Customer::factory()->forUser($this->user)->create();

        $this->postJson(route('projects.store'), [
            'customer_id' => $customer->id,
            'status' => ProjectStatus::New->value,
        ])->assertCreated();

        $this->assertDatabaseHas('projects', [
            'customer_id' => $customer->id,
            'user_id' => $this->user->id,
            'status' => 'new',
            'name' => null,
        ]);
    }

    public function test_project_can_be_created_with_a_brand_new_customer(): void
    {
        $this->postJson(route('projects.store'), [
            'customer_mode' => 'new',
            'customer_name' => 'Walk-in client',
            'customer_phone' => '+216 20 123 456',
            'name' => 'Shop sign',
            'status' => 'new',
        ])->assertCreated()
            ->assertJsonPath('customer.phone', '+216 20 123 456');

        $customer = Customer::firstWhere('phone', '+216 20 123 456');

        $this->assertNotNull($customer);
        $this->assertSame('Walk-in client', $customer->name);
        $this->assertDatabaseHas('projects', ['customer_id' => $customer->id, 'name' => 'Shop sign']);
    }

    public function test_phone_is_required_for_an_inline_customer(): void
    {
        $this->postJson(route('projects.store'), [
            'customer_mode' => 'new',
            'customer_name' => 'No phone',
            'status' => 'new',
        ])->assertStatus(422)->assertJsonValidationErrors('customer_phone');
    }

    public function test_customer_is_required(): void
    {
        $this->postJson(route('projects.store'), ['status' => 'new'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('customer_id');
    }

    public function test_invalid_status_is_rejected(): void
    {
        $customer = Customer::factory()->forUser($this->user)->create();

        $this->postJson(route('projects.store'), [
            'customer_id' => $customer->id,
            'status' => 'archived',
        ])->assertStatus(422)->assertJsonValidationErrors('status');
    }

    public function test_status_can_be_changed_over_ajax(): void
    {
        $project = Project::factory()->forUser($this->user)->create(['status' => ProjectStatus::New->value]);

        $this->patchJson(route('projects.status', $project), ['status' => 'confirmed'])
            ->assertOk()
            ->assertJsonPath('project.status', 'confirmed');

        $this->assertSame(ProjectStatus::Confirmed, $project->refresh()->status);
    }

    public function test_project_can_be_updated_and_deleted(): void
    {
        $project = Project::factory()->create();

        $this->putJson(route('projects.update', $project), [
            'customer_id' => $project->customer_id,
            'name' => 'Renamed project',
            'description' => 'Updated',
            'status' => 'cancelled',
        ])->assertOk();

        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Renamed project', 'status' => 'cancelled']);

        $this->deleteJson(route('projects.destroy', $project))->assertOk();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_finished_and_cancelled_projects_are_hidden_by_default(): void
    {
        Project::factory()->forUser($this->user)->create(['name' => 'Open job', 'status' => 'new']);
        Project::factory()->forUser($this->user)->create(['name' => 'Agreed job', 'status' => 'confirmed']);
        Project::factory()->forUser($this->user)->create(['name' => 'Done job', 'status' => 'finished']);
        Project::factory()->forUser($this->user)->create(['name' => 'Dropped job', 'status' => 'cancelled']);

        $this->get(route('projects.index'))
            ->assertOk()
            ->assertSee('Open job')
            ->assertSee('Agreed job')
            ->assertDontSee('Done job')
            ->assertDontSee('Dropped job');
    }

    public function test_archived_projects_are_shown_when_requested(): void
    {
        Project::factory()->forUser($this->user)->create(['name' => 'Done job', 'status' => 'finished']);

        $this->get(route('projects.index', ['show_archived' => 1]))
            ->assertOk()
            ->assertSee('Done job');
    }

    public function test_filters_by_status_and_search(): void
    {
        $confirmed = Project::factory()->forUser($this->user)->create(['name' => 'Confirmed job', 'status' => 'confirmed']);
        $new = Project::factory()->forUser($this->user)->create(['name' => 'New job', 'status' => 'new']);

        $response = $this->getJson(route('projects.index', ['status' => 'confirmed']));

        $this->assertStringContainsString('Confirmed job', $response->json('html'));
        $this->assertStringNotContainsString('New job', $response->json('html'));
    }

    public function test_projects_of_other_users_are_not_listed(): void
    {
        Project::factory()->forUser($this->user)->create(['name' => 'My job', 'status' => 'new']);
        Project::factory()->create(['name' => 'Their job', 'status' => 'new']);

        $this->get(route('projects.index'))
            ->assertOk()
            ->assertSee('My job')
            ->assertDontSee('Their job');
    }

    public function test_another_users_project_cannot_be_touched(): void
    {
        $foreign = Project::factory()->create(['status' => 'new']);

        $this->patchJson(route('projects.status', $foreign), ['status' => 'cancelled'])->assertForbidden();
        $this->deleteJson(route('projects.destroy', $foreign))->assertForbidden();

        $this->assertDatabaseHas('projects', ['id' => $foreign->id, 'status' => 'new']);
    }

    public function test_a_project_cannot_be_attached_to_another_users_customer(): void
    {
        $foreignCustomer = Customer::factory()->create();

        $this->postJson(route('projects.store'), [
            'customer_id' => $foreignCustomer->id,
            'status' => 'new',
        ])->assertStatus(422)->assertJsonValidationErrors('customer_id');
    }
}
