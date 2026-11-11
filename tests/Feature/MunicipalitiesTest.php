<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Municipalities;
use App\Models\User;
use PHPUnit\Framework\Attributes\Test;

class MunicipalitiesTest extends TestCase
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
    public function it_can_display_municipalities_index_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('municipalities.index'));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.municipalities');
    }

    #[Test]
    public function it_can_display_municipalities_create_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('municipalities.create'));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.create-municipalities');
    }

    #[Test]
    public function it_can_store_a_new_municipalities()
    {
        $data = [
            'name' => 'Test name'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('municipalities.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Municipalities Added Successfully!');

        $this->assertDatabaseHas('municipalities', $data);
    }

    #[Test]
    public function it_validates_required_fields_when_storing()
    {
        $response = $this->actingAs($this->user)
            ->post(route('municipalities.store'), []);

        $response->assertSessionHasErrors();
    }

    #[Test]
    public function it_can_display_a_single_municipalities()
    {
        $municipalities = Municipalities::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('municipalities.show', $municipalities->id));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.show-municipalities');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_display_municipalities_edit_page()
    {
        $municipalities = Municipalities::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('municipalities.edit', $municipalities->id));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.edit-municipalities');
        $response->assertViewHas('item');
    }

    #[Test]
    public function it_can_update_a_municipalities()
    {
        $municipalities = Municipalities::factory()->create();

        $updatedData = [
            'name' => 'Updated name'
        ];

        $response = $this->actingAs($this->user)
            ->post(route('municipalities.update', $municipalities->id), $updatedData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Municipalities Updated Successfully!');

        $this->assertEquals($updatedData['name'], $municipalities->fresh()->name);
    }

    #[Test]
    public function it_can_display_delete_confirmation_page()
    {
        $municipalities = Municipalities::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('municipalities.delete', $municipalities->id));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.delete-municipalities');
    }

    #[Test]
    public function it_can_soft_delete_a_municipalities()
    {
        $municipalities = Municipalities::factory()->create();

        $response = $this->actingAs($this->user)
            ->get(route('municipalities.destroy', $municipalities->id));

        $response->assertRedirect('/municipalities');

        $this->assertDatabaseHas('municipalities', [
            'id' => $municipalities->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_display_trash_page()
    {
        $response = $this->actingAs($this->user)
            ->get(url('trash-municipalities'));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.trash-municipalities');
    }

    #[Test]
    public function it_can_restore_a_trashed_municipalities()
    {
        $municipalities = Municipalities::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->get(route('municipalities.restore', $municipalities->id));

        $response->assertRedirect('/municipalities');

        $this->assertDatabaseHas('municipalities', [
            'id' => $municipalities->id,
            'isTrash' => '0'
        ]);
    }

    #[Test]
    public function it_can_search_municipalities()
    {
        Municipalities::factory()->create([
            'name' => 'Test name'
        ]);

        $response = $this->actingAs($this->user)
            ->get(url('/municipalities-search?search=Test'));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.municipalities');
    }

    #[Test]
    public function it_can_paginate_municipalities()
    {
        Municipalities::factory()->count(20)->create();

        $response = $this->actingAs($this->user)
            ->get(url('/municipalities-paginate?paginate=5'));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.municipalities');
    }

    #[Test]
    public function it_can_filter_municipalities_by_date_range()
    {
        $from = now()->subDays(7)->format('Y-m-d');
        $to = now()->format('Y-m-d');

        $response = $this->actingAs($this->user)
            ->get(url('/municipalities-filter?from=' . $from . '&to=' . $to));

        $response->assertStatus(200);
        $response->assertViewIs('municipalities.municipalities');
    }

    #[Test]
    public function it_can_bulk_delete_municipalitiess()
    {
        $municipalities1 = Municipalities::factory()->create();
        $municipalities2 = Municipalities::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/municipalities-delete-all-bulk-data'), [
                'ids' => [$municipalities1->id, $municipalities2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseMissing('municipalities', ['id' => $municipalities1->id]);
        $this->assertDatabaseMissing('municipalities', ['id' => $municipalities2->id]);
    }

    #[Test]
    public function it_can_bulk_move_to_trash_municipalitiess()
    {
        $municipalities1 = Municipalities::factory()->create();
        $municipalities2 = Municipalities::factory()->create();

        $response = $this->actingAs($this->user)
            ->post(url('/municipalities-move-to-trash-all-bulk-data'), [
                'ids' => [$municipalities1->id, $municipalities2->id]
            ]);

        $response->assertSee('Deleted');

        $this->assertDatabaseHas('municipalities', [
            'id' => $municipalities1->id,
            'isTrash' => '1'
        ]);
        $this->assertDatabaseHas('municipalities', [
            'id' => $municipalities2->id,
            'isTrash' => '1'
        ]);
    }

    #[Test]
    public function it_can_bulk_restore_municipalitiess()
    {
        $municipalities1 = Municipalities::factory()->create(['isTrash' => '1']);
        $municipalities2 = Municipalities::factory()->create(['isTrash' => '1']);

        $response = $this->actingAs($this->user)
            ->post(url('/municipalities-restore-all-bulk-data'), [
                'ids' => [$municipalities1->id, $municipalities2->id]
            ]);

        $response->assertSee('Restored');

        $this->assertDatabaseHas('municipalities', [
            'id' => $municipalities1->id,
            'isTrash' => '0'
        ]);
        $this->assertDatabaseHas('municipalities', [
            'id' => $municipalities2->id,
            'isTrash' => '0'
        ]);
    }
}