<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnhancedActivityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test users with different roles
        $this->admin = User::factory()->create(['roles' => 'admin']);
        $this->manager = User::factory()->create(['roles' => 'manager']);
        $this->operator = User::factory()->create(['roles' => 'operator']);
        $this->owner = User::factory()->create(['roles' => 'owner']);
    }

    public function test_admin_can_access_activity_history()
    {
        $response = $this->actingAs($this->admin)->get('/activities');
        
        $response->assertStatus(200);
        $response->assertViewIs('activities.index');
    }

    public function test_manager_can_access_activity_history()
    {
        $response = $this->actingAs($this->manager)->get('/activities');
        
        $response->assertStatus(200);
        $response->assertViewIs('activities.index');
    }

    public function test_operator_can_access_activity_history()
    {
        $response = $this->actingAs($this->operator)->get('/activities');
        
        $response->assertStatus(200);
        $response->assertViewIs('activities.index');
    }

    public function test_owner_cannot_access_activity_history()
    {
        $response = $this->actingAs($this->owner)->get('/activities');
        
        $response->assertStatus(403);
    }

    public function test_admin_sees_all_activities()
    {
        // Create activities from different users
        Activity::factory()->create(['user_id' => $this->admin->id, 'action' => 'created']);
        Activity::factory()->create(['user_id' => $this->manager->id, 'action' => 'updated']);
        Activity::factory()->create(['user_id' => $this->operator->id, 'action' => 'deleted']);

        $response = $this->actingAs($this->admin)->get('/activities');
        
        $response->assertStatus(200);
        // Admin should see all 3 activities
        $this->assertEquals(3, $response->viewData('activities')->total());
    }

    public function test_operator_sees_only_own_activities()
    {
        // Create activities from different users
        Activity::factory()->create(['user_id' => $this->admin->id, 'action' => 'created']);
        Activity::factory()->create(['user_id' => $this->manager->id, 'action' => 'updated']);
        Activity::factory()->create(['user_id' => $this->operator->id, 'action' => 'deleted']);

        $response = $this->actingAs($this->operator)->get('/activities');
        
        $response->assertStatus(200);
        // Operator should see only 1 activity (their own)
        $this->assertEquals(1, $response->viewData('activities')->total());
    }

    public function test_activity_filtering_by_action()
    {
        Activity::factory()->create(['user_id' => $this->admin->id, 'action' => 'created']);
        Activity::factory()->create(['user_id' => $this->admin->id, 'action' => 'updated']);
        Activity::factory()->create(['user_id' => $this->admin->id, 'action' => 'deleted']);

        $response = $this->actingAs($this->admin)->get('/activities?action=created');
        
        $response->assertStatus(200);
        $this->assertEquals(1, $response->viewData('activities')->total());
    }

    public function test_activity_filtering_by_date()
    {
        $yesterday = now()->subDay();
        $today = now();

        Activity::factory()->create([
            'user_id' => $this->admin->id,
            'created_at' => $yesterday,
            'action' => 'created'
        ]);
        
        Activity::factory()->create([
            'user_id' => $this->admin->id,
            'created_at' => $today,
            'action' => 'updated'
        ]);

        $response = $this->actingAs($this->admin)
            ->get('/activities?date_from=' . $today->format('Y-m-d'));
        
        $response->assertStatus(200);
        $this->assertEquals(1, $response->viewData('activities')->total());
    }

    public function test_activity_export_csv()
    {
        Activity::factory()->create(['user_id' => $this->admin->id, 'action' => 'created']);

        $response = $this->actingAs($this->admin)->get('/activities/export?export=csv');
        
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_role_based_dashboard_admin()
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('totalUsers');
        $response->assertViewHas('totalProducts');
    }

    public function test_role_based_dashboard_owner()
    {
        $response = $this->actingAs($this->owner)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('businessStats');
    }

    public function test_role_based_dashboard_manager()
    {
        $response = $this->actingAs($this->manager)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('teamStats');
    }

    public function test_role_based_dashboard_operator()
    {
        $response = $this->actingAs($this->operator)->get('/dashboard');
        
        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('personalStats');
    }

    public function test_ajax_activity_refresh()
    {
        Activity::factory()->create(['user_id' => $this->admin->id, 'action' => 'created']);

        $response = $this->actingAs($this->admin)
            ->withHeaders(['X-Requested-With' => 'XMLHttpRequest'])
            ->get('/activities');
        
        $response->assertStatus(200);
        $response->assertJsonStructure(['html', 'pagination']);
    }
}