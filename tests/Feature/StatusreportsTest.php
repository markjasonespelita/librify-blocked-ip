<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Statusreports;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class StatusreportsTest extends TestCase
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
    public function it_can_display_statusreports_index_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('statusreports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.statusreports');
    }

    #[Test]
    public function it_can_display_statusreports_create_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('statusreports.create'));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.create-statusreports');
    }

    #[Test]
    public function it_can_store_a_new_statusreports()
    {
        $data = [
            'title' => 'Test title',
            'basis' => 'Test basis',
            'total_sites' => 123,
            'done' => 123,
            'cant_be_reached' => 123,
            'offline' => 123,
            'not_found' => 123,
            'users_id' => 123
        ];

        $response = $this->actingAs($this->user)
            ->post(route('statusreports.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Statusreports Added Successfully!');

        $this->assertDatabaseHas('statusreports', $data);
    }

    #[Test]
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->actingAs($this->user)
            ->post(route('statusreports.store'), []);

        $response->assertSessionHasErrors();
    }

    #[Test]
    public function it_can_display_a_single_statusreports()
    {
        $statusreports = Statusreports::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('statusreports.show', $statusreports->id));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.show-statusreports');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_display_statusreports_edit_page()
    {
        $statusreports = Statusreports::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('statusreports.edit', $statusreports->id));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.edit-statusreports');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_update_a_statusreports()
    {
        $statusreports = Statusreports::factory()->create();

        $updatedData = [
            'title' => 'Updated title',
            'basis' => 'Updated basis',
            'total_sites' => 456,
            'done' => 456,
            'cant_be_reached' => 456,
            'offline' => 456,
            'not_found' => 456,
            'users_id' => 456
        ];

        $response = $this->actingAs($this->user)
            ->post(route('statusreports.update', $statusreports->id), $updatedData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Statusreports Updated Successfully!');

        $this->assertEquals($updatedData['title'], $statusreports->fresh()->title);
        $this->assertEquals($updatedData['basis'], $statusreports->fresh()->basis);
        $this->assertEquals($updatedData['total_sites'], $statusreports->fresh()->total_sites);
        $this->assertEquals($updatedData['done'], $statusreports->fresh()->done);
        $this->assertEquals($updatedData['cant_be_reached'], $statusreports->fresh()->cant_be_reached);
        $this->assertEquals($updatedData['offline'], $statusreports->fresh()->offline);
        $this->assertEquals($updatedData['not_found'], $statusreports->fresh()->not_found);
        $this->assertEquals($updatedData['users_id'], $statusreports->fresh()->users_id);
    }

    #[Test]
    public function it_can_display_delete_confirmation_page()
    {
        $statusreports = Statusreports::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('statusreports.delete', $statusreports->id));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.delete-statusreports');
    }

    #[Test]
    public function it_can_soft_delete_a_statusreports()
    {
        $statusreports = Statusreports::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('statusreports.destroy', $statusreports->id));

        $response->assertRedirect('/statusreports');

        $this->assertDatabaseHas('statusreports', [
            'id' => $statusreports->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_display_trash_page()
    {
        $response = $this->actingAs($this->user)
            ->get(url('trash-statusreports'));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.trash-statusreports');
    }

    #[Test]
    public function it_can_restore_a_trashed_statusreports()
    {
        $statusreports = Statusreports::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->get(route('statusreports.restore', $statusreports->id));

        $response->assertRedirect('/statusreports');

        $this->assertDatabaseHas('statusreports', [
            'id' => $statusreports->id,
            'isTrash' => '0'
        ]);
    }

    #[Test]
    public function it_can_search_statusreports()
    {
        Statusreports::factory()->create([
            'title' => 'Test title',
            'basis' => 'Test basis',
            'total_sites' => 123,
            'done' => 123,
            'cant_be_reached' => 123,
            'offline' => 123,
            'not_found' => 123,
            'users_id' => 123
        ]);

        $response = $this->actingAs($this->user)
            ->get(url('/statusreports-search?search=Test'));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.statusreports');
    }

    #[Test]
    public function it_can_paginate_statusreports()
    {
        Statusreports::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(url('/statusreports-paginate?paginate=5'));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.statusreports');
    }

    #[Test]
    public function it_can_filter_statusreports_by_date_range()
    {
        $from = now()->subDays(7)->format('Y-m-d');
        $to = now()->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->get(url('/statusreports-filter?from=' . $from . '&to=' . $to));

        $response->assertStatus(200);
        $response->assertViewIs('statusreports.statusreports');
    }

    #[Test]
    public function it_can_bulk_delete_statusreportss()
    {
        $statusreports1 = Statusreports::factory()->create();
        $statusreports2 = Statusreports::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/statusreports-delete-all-bulk-data'), [
                'ids' => [$statusreports1->id, $statusreports2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseMissing('statusreports', ['id' => $statusreports1->id]);
        $this->assertDatabaseMissing('statusreports', ['id' => $statusreports2->id]);
    }

    #[Test]
    public function it_can_bulk_move_to_trash_statusreportss()
    {
        $statusreports1 = Statusreports::factory()->create();
        $statusreports2 = Statusreports::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/statusreports-move-to-trash-all-bulk-data'), [
                'ids' => [$statusreports1->id, $statusreports2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseHas('statusreports', [
            'id' => $statusreports1->id,
            'isTrash' => '1'
        ]);
        $this->assertDatabaseHas('statusreports', [
            'id' => $statusreports2->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_bulk_restore_statusreportss()
    {
        $statusreports1 = Statusreports::factory()->create(['isTrash' => '1']);
        $statusreports2 = Statusreports::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->post(url('/statusreports-restore-all-bulk-data'), [
                'ids' => [$statusreports1->id, $statusreports2->id]
            ]);

        $response->assertSee('Restored');

        $this->assertDatabaseHas('statusreports', [
            'id' => $statusreports1->id,
            'isTrash' => '0'
        ]);
        $this->assertDatabaseHas('statusreports', [
            'id' => $statusreports2->id,
            'isTrash' => '0'
        ]);
    }
}