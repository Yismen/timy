<?php

namespace Dainsys\Timy\Tests\Unit;

use Dainsys\Timy\Models\Disposition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

trait LivewireDispositionsTestsTrait
{
    /** @test */
    public function it_properties_are_set_on_load()
    {
        factory(Disposition::class, 5)->create();

        Livewire::test('timy::dispositions')
            ->assertSet('dispositions', Disposition::orderBy('name')->get())
            ->assertSet('disposition', [
                'id' => null,
                'name' => '',
                'payable' => false,
                'invoiceable' => false
            ]);
    }

    /** @test */
    public function it_validates_a_name_create_a_new_disposition()
    {
        factory(Disposition::class)->create(['name' => 'duplicated']);

        Livewire::test('timy::dispositions')
            ->set('disposition.name', null)
            ->call('createDisposition')
            ->assertHasErrors(['disposition.name' => 'required'])
            ->set('disposition.name', 'aa') // two characters
            ->call('createDisposition')
            ->assertHasErrors(['disposition.name' => 'min'])
            ->set('disposition.name', 'duplicated') // two characters
            ->call('createDisposition')
            ->assertHasErrors(['disposition.name' => 'unique']); // at least 3 characters are required
    }

    /** @test */
    public function it_creates_a_new_disposition_and_resets_values()
    {
        Livewire::test('timy::dispositions')
            ->set('disposition.name', 'New Disposition')
            ->set('disposition.payable', '1')
            ->call('createDisposition')
            ->assertSet('disposition', [
                'id' => null,
                'name' => '',
                'payable' => false,
                'invoiceable' => false
            ])
            ->assertSet('dispositions', Disposition::orderBy('name')->get());

        $this->assertDatabaseHas('timy_dispositions', ['name' => 'new disposition', 'payable' => 1]);
    }

    /** @test */
    public function it_sets_a_disposition_to_be_updated()
    {
        $disposition = factory(Disposition::class)->create();

        Livewire::test('timy::dispositions')
            ->set('disposition.name', $disposition->name)
            ->call('editDisposition', $disposition)
            ->assertSet('disposition', [
                'id' => $disposition->id,
                'name' => $disposition->name,
                'payable' => $disposition->payable,
                'invoiceable' => $disposition->invoiceable
            ])
            ->assertDispatchedBrowserEvent('editingDisposition');
    }

    /** @test */
    public function it_validates_a_name_before_updating_a_disposition()
    {
        $disposition = factory(Disposition::class)->create(['name' => 'duplicated']);

        Livewire::test('timy::dispositions')
            ->set('disposition.name', null)
            ->call('updateDisposition')
            ->assertHasErrors(['disposition.name' => 'required'])
            ->set('disposition.name', 'aa') // two characters
            ->call('updateDisposition')
            ->assertHasErrors(['disposition.name' => 'min'])
            // ->set('disposition.name', $disposition->name) // Du
            // ->call('updateDisposition')
            // ->assertHasErrors(['disposition.name' => 'unique'])
        ; // at least 3 characters are required
    }

    /** @test */
    public function it_updates_disposition_and_resets_values()
    {
        $disposition = factory(Disposition::class)->create(['name' => 'Some Name']);

        Livewire::test('timy::dispositions')
            ->call('editDisposition', $disposition)
            ->assertSet('disposition', [
                'id' => $disposition->id,
                'name' => $disposition->name,
                'payable' => $disposition->payable,
                'invoiceable' => $disposition->invoiceable
            ])
            ->set('disposition.name', 'Updated Disposition Name')
            ->set('disposition.payable', '1')
            ->call('updateDisposition')
            ->assertSet('dispositions', Disposition::orderBy('name')->get())
            ->assertSet('disposition', [
                'id' => null,
                'name' => '',
                'payable' => false,
                'invoiceable' => false
            ]);

        $this->assertDatabaseHas('timy_dispositions', ['name' => 'updated disposition name', 'payable' => 1]);
    }

    /** @test */
    public function it_resets_form()
    {
        $disposition = factory(Disposition::class)->create(['name' => 'Some Name']);

        Livewire::test('timy::dispositions')
            ->call('editDisposition', $disposition)
            ->call('resetForm')
            ->assertSet('disposition', [
                'id' => null,
                'name' => '',
                'payable' => false,
                'invoiceable' => false
            ]);
    }
}
