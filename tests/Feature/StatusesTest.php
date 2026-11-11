<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Statuses;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class StatusesTest extends TestCase
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
    public function it_can_display_statuses_index_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('statuses.index'));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.statuses');
    }

    #[Test]
    public function it_can_display_statuses_create_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('statuses.create'));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.create-statuses');
    }

    #[Test]
    public function it_can_store_a_new_statuses()
    {
        $data = [
            'name' => 'Test name',
            'icon' => 'Test icon'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('statuses.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Statuses Added Successfully!');

        $this->assertDatabaseHas('statuses', $data);
    }

    #[Test]
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->actingAs($this->user)
            ->post(route('statuses.store'), []);

        $response->assertSessionHasErrors();
    }

    #[Test]
    public function it_can_display_a_single_statuses()
    {
        $statuses = Statuses::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('statuses.show', $statuses->id));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.show-statuses');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_display_statuses_edit_page()
    {
        $statuses = Statuses::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('statuses.edit', $statuses->id));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.edit-statuses');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_update_a_statuses()
    {
        $statuses = Statuses::factory()->create();

        $updatedData = [
            'name' => 'Updated name',
            'icon' => 'Updated icon'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('statuses.update', $statuses->id), $updatedData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Statuses Updated Successfully!');

        $this->assertEquals($updatedData['name'], $statuses->fresh()->name);
        $this->assertEquals($updatedData['icon'], $statuses->fresh()->icon);
    }

    #[Test]
    public function it_can_display_delete_confirmation_page()
    {
        $statuses = Statuses::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('statuses.delete', $statuses->id));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.delete-statuses');
    }

    #[Test]
    public function it_can_soft_delete_a_statuses()
    {
        $statuses = Statuses::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('statuses.destroy', $statuses->id));

        $response->assertRedirect('/statuses');

        $this->assertDatabaseHas('statuses', [
            'id' => $statuses->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_display_trash_page()
    {
        $response = $this->actingAs($this->user)
            ->get(url('trash-statuses'));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.trash-statuses');
    }

    #[Test]
    public function it_can_restore_a_trashed_statuses()
    {
        $statuses = Statuses::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->get(route('statuses.restore', $statuses->id));

        $response->assertRedirect('/statuses');

        $this->assertDatabaseHas('statuses', [
            'id' => $statuses->id,
            'isTrash' => '0'
        ]);
    }

    #[Test]
    public function it_can_search_statuses()
    {
        Statuses::factory()->create([
            'name' => 'Test name',
            'icon' => 'Test icon'
        ]);

        $response = $this->actingAs($this->user)
            ->get(url('/statuses-search?search=Test'));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.statuses');
    }

    #[Test]
    public function it_can_paginate_statuses()
    {
        Statuses::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(url('/statuses-paginate?paginate=5'));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.statuses');
    }

    #[Test]
    public function it_can_filter_statuses_by_date_range()
    {
        $from = now()->subDays(7)->format('Y-m-d');
        $to = now()->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->get(url('/statuses-filter?from=' . $from . '&to=' . $to));

        $response->assertStatus(200);
        $response->assertViewIs('statuses.statuses');
    }

    #[Test]
    public function it_can_bulk_delete_statusess()
    {
        $statuses1 = Statuses::factory()->create();
        $statuses2 = Statuses::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/statuses-delete-all-bulk-data'), [
                'ids' => [$statuses1->id, $statuses2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseMissing('statuses', ['id' => $statuses1->id]);
        $this->assertDatabaseMissing('statuses', ['id' => $statuses2->id]);
    }

    #[Test]
    public function it_can_bulk_move_to_trash_statusess()
    {
        $statuses1 = Statuses::factory()->create();
        $statuses2 = Statuses::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/statuses-move-to-trash-all-bulk-data'), [
                'ids' => [$statuses1->id, $statuses2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseHas('statuses', [
            'id' => $statuses1->id,
            'isTrash' => '1'
        ]);
        $this->assertDatabaseHas('statuses', [
            'id' => $statuses2->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_bulk_restore_statusess()
    {
        $statuses1 = Statuses::factory()->create(['isTrash' => '1']);
        $statuses2 = Statuses::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->post(url('/statuses-restore-all-bulk-data'), [
                'ids' => [$statuses1->id, $statuses2->id]
            ]);

        $response->assertSee('Restored');

        $this->assertDatabaseHas('statuses', [
            'id' => $statuses1->id,
            'isTrash' => '0'
        ]);
        $this->assertDatabaseHas('statuses', [
            'id' => $statuses2->id,
            'isTrash' => '0'
        ]);
    }
}