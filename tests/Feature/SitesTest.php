<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Sites;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class SitesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for authentication (adjust based on your auth setup)
        $this->user = User::factory()->create();
    }

    #[Test]
    public function it_can_display_sites_index_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('sites.index'));

        $response->assertStatus(200);
        $response->assertViewIs('sites.sites');
    }

    #[Test]
    public function it_can_display_sites_create_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('sites.create'));

        $response->assertStatus(200);
        $response->assertViewIs('sites.create-sites');
    }

    #[Test]
    public function it_can_store_a_new_sites()
    {
        $data = [
            'name' => 'Test name',
            'municipalities_id' => 123,
            'olts_id' => 123,
            'statuses_id' => 123,
            'ip_address' => 'Test ip_address',
            'remarks' => 'Test remarks'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('sites.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Sites Added Successfully!');

        $this->assertDatabaseHas('sites', $data);
    }

    #[Test]
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->actingAs($this->user)
            ->post(route('sites.store'), []);

        $response->assertSessionHasErrors();
    }

    #[Test]
    public function it_can_display_a_single_sites()
    {
        $sites = Sites::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('sites.show', $sites->id));

        $response->assertStatus(200);
        $response->assertViewIs('sites.show-sites');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_display_sites_edit_page()
    {
        $sites = Sites::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('sites.edit', $sites->id));

        $response->assertStatus(200);
        $response->assertViewIs('sites.edit-sites');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_update_a_sites()
    {
        $sites = Sites::factory()->create();

        $updatedData = [
            'name' => 'Updated name',
            'municipalities_id' => 456,
            'olts_id' => 456,
            'statuses_id' => 456,
            'ip_address' => 'Updated ip_address',
            'remarks' => 'Updated remarks'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('sites.update', $sites->id), $updatedData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Sites Updated Successfully!');

        $this->assertEquals($updatedData['name'], $sites->fresh()->name);
        $this->assertEquals($updatedData['municipalities_id'], $sites->fresh()->municipalities_id);
        $this->assertEquals($updatedData['olts_id'], $sites->fresh()->olts_id);
        $this->assertEquals($updatedData['statuses_id'], $sites->fresh()->statuses_id);
        $this->assertEquals($updatedData['ip_address'], $sites->fresh()->ip_address);
        $this->assertEquals($updatedData['remarks'], $sites->fresh()->remarks);
    }

    #[Test]
    public function it_can_display_delete_confirmation_page()
    {
        $sites = Sites::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('sites.delete', $sites->id));

        $response->assertStatus(200);
        $response->assertViewIs('sites.delete-sites');
    }

    #[Test]
    public function it_can_soft_delete_a_sites()
    {
        $sites = Sites::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('sites.destroy', $sites->id));

        $response->assertRedirect('/sites');

        $this->assertDatabaseHas('sites', [
            'id' => $sites->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_display_trash_page()
    {
        $response = $this->actingAs($this->user)
            ->get(url('trash-sites'));

        $response->assertStatus(200);
        $response->assertViewIs('sites.trash-sites');
    }

    #[Test]
    public function it_can_restore_a_trashed_sites()
    {
        $sites = Sites::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->get(route('sites.restore', $sites->id));

        $response->assertRedirect('/sites');

        $this->assertDatabaseHas('sites', [
            'id' => $sites->id,
            'isTrash' => '0'
        ]);
    }

    #[Test]
    public function it_can_search_sites()
    {
        Sites::factory()->create([
            'name' => 'Test name',
            'municipalities_id' => 123,
            'olts_id' => 123,
            'statuses_id' => 123,
            'ip_address' => 'Test ip_address',
            'remarks' => 'Test remarks'
        ]);

        $response = $this->actingAs($this->user)
            ->get(url('/sites-search?search=Test'));

        $response->assertStatus(200);
        $response->assertViewIs('sites.sites');
    }

    #[Test]
    public function it_can_paginate_sites()
    {
        Sites::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(url('/sites-paginate?paginate=5'));

        $response->assertStatus(200);
        $response->assertViewIs('sites.sites');
    }

    #[Test]
    public function it_can_filter_sites_by_date_range()
    {
        $from = now()->subDays(7)->format('Y-m-d');
        $to = now()->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->get(url('/sites-filter?from=' . $from . '&to=' . $to));

        $response->assertStatus(200);
        $response->assertViewIs('sites.sites');
    }

    #[Test]
    public function it_can_bulk_delete_sitess()
    {
        $sites1 = Sites::factory()->create();
        $sites2 = Sites::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/sites-delete-all-bulk-data'), [
                'ids' => [$sites1->id, $sites2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseMissing('sites', ['id' => $sites1->id]);
        $this->assertDatabaseMissing('sites', ['id' => $sites2->id]);
    }

    #[Test]
    public function it_can_bulk_move_to_trash_sitess()
    {
        $sites1 = Sites::factory()->create();
        $sites2 = Sites::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/sites-move-to-trash-all-bulk-data'), [
                'ids' => [$sites1->id, $sites2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseHas('sites', [
            'id' => $sites1->id,
            'isTrash' => '1'
        ]);
        $this->assertDatabaseHas('sites', [
            'id' => $sites2->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_bulk_restore_sitess()
    {
        $sites1 = Sites::factory()->create(['isTrash' => '1']);
        $sites2 = Sites::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->post(url('/sites-restore-all-bulk-data'), [
                'ids' => [$sites1->id, $sites2->id]
            ]);

        $response->assertSee('Restored');

        $this->assertDatabaseHas('sites', [
            'id' => $sites1->id,
            'isTrash' => '0'
        ]);
        $this->assertDatabaseHas('sites', [
            'id' => $sites2->id,
            'isTrash' => '0'
        ]);
    }
}