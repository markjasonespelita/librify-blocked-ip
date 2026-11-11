<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Olts;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class OltsTest extends TestCase
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
    public function it_can_display_olts_index_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('olts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('olts.olts');
    }

    #[Test]
    public function it_can_display_olts_create_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('olts.create'));

        $response->assertStatus(200);
        $response->assertViewIs('olts.create-olts');
    }

    #[Test]
    public function it_can_store_a_new_olts()
    {
        $data = [
            'name' => 'Test name'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('olts.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Olts Added Successfully!');

        $this->assertDatabaseHas('olts', $data);
    }

    #[Test]
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->actingAs($this->user)
            ->post(route('olts.store'), []);

        $response->assertSessionHasErrors();
    }

    #[Test]
    public function it_can_display_a_single_olts()
    {
        $olts = Olts::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('olts.show', $olts->id));

        $response->assertStatus(200);
        $response->assertViewIs('olts.show-olts');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_display_olts_edit_page()
    {
        $olts = Olts::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('olts.edit', $olts->id));

        $response->assertStatus(200);
        $response->assertViewIs('olts.edit-olts');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_update_a_olts()
    {
        $olts = Olts::factory()->create();

        $updatedData = [
            'name' => 'Updated name'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('olts.update', $olts->id), $updatedData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Olts Updated Successfully!');

        $this->assertEquals($updatedData['name'], $olts->fresh()->name);
    }

    #[Test]
    public function it_can_display_delete_confirmation_page()
    {
        $olts = Olts::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('olts.delete', $olts->id));

        $response->assertStatus(200);
        $response->assertViewIs('olts.delete-olts');
    }

    #[Test]
    public function it_can_soft_delete_a_olts()
    {
        $olts = Olts::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('olts.destroy', $olts->id));

        $response->assertRedirect('/olts');

        $this->assertDatabaseHas('olts', [
            'id' => $olts->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_display_trash_page()
    {
        $response = $this->actingAs($this->user)
            ->get(url('trash-olts'));

        $response->assertStatus(200);
        $response->assertViewIs('olts.trash-olts');
    }

    #[Test]
    public function it_can_restore_a_trashed_olts()
    {
        $olts = Olts::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->get(route('olts.restore', $olts->id));

        $response->assertRedirect('/olts');

        $this->assertDatabaseHas('olts', [
            'id' => $olts->id,
            'isTrash' => '0'
        ]);
    }

    #[Test]
    public function it_can_search_olts()
    {
        Olts::factory()->create([
            'name' => 'Test name'
        ]);

        $response = $this->actingAs($this->user)
            ->get(url('/olts-search?search=Test'));

        $response->assertStatus(200);
        $response->assertViewIs('olts.olts');
    }

    #[Test]
    public function it_can_paginate_olts()
    {
        Olts::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(url('/olts-paginate?paginate=5'));

        $response->assertStatus(200);
        $response->assertViewIs('olts.olts');
    }

    #[Test]
    public function it_can_filter_olts_by_date_range()
    {
        $from = now()->subDays(7)->format('Y-m-d');
        $to = now()->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->get(url('/olts-filter?from=' . $from . '&to=' . $to));

        $response->assertStatus(200);
        $response->assertViewIs('olts.olts');
    }

    #[Test]
    public function it_can_bulk_delete_oltss()
    {
        $olts1 = Olts::factory()->create();
        $olts2 = Olts::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/olts-delete-all-bulk-data'), [
                'ids' => [$olts1->id, $olts2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseMissing('olts', ['id' => $olts1->id]);
        $this->assertDatabaseMissing('olts', ['id' => $olts2->id]);
    }

    #[Test]
    public function it_can_bulk_move_to_trash_oltss()
    {
        $olts1 = Olts::factory()->create();
        $olts2 = Olts::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/olts-move-to-trash-all-bulk-data'), [
                'ids' => [$olts1->id, $olts2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseHas('olts', [
            'id' => $olts1->id,
            'isTrash' => '1'
        ]);
        $this->assertDatabaseHas('olts', [
            'id' => $olts2->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_bulk_restore_oltss()
    {
        $olts1 = Olts::factory()->create(['isTrash' => '1']);
        $olts2 = Olts::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->post(url('/olts-restore-all-bulk-data'), [
                'ids' => [$olts1->id, $olts2->id]
            ]);

        $response->assertSee('Restored');

        $this->assertDatabaseHas('olts', [
            'id' => $olts1->id,
            'isTrash' => '0'
        ]);
        $this->assertDatabaseHas('olts', [
            'id' => $olts2->id,
            'isTrash' => '0'
        ]);
    }
}