<?php

namespace Dainsys\Timy\Http\Livewire;

use Dainsys\Timy\Models\Disposition;
use Dainsys\Timy\Resources\TimerResource;
use Dainsys\Timy\Models\Timer;
use Dainsys\Timy\Repositories\DispositionsRepository;
use Livewire\Component;
use Livewire\WithPagination;

class Dispositions extends Component
{
    use WithPagination;

    public $dispositions;

    public $disposition = [
        'id' => null,
        'name' => '',
        'payable' => false,
        'invoiceable' => false
    ];

    protected $rules = [
        'dispositions' => 'array',
        'disposition.name' => 'required|min:3|unique:timy_dispositions,name'
    ];

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('timy::livewire.dispositions');
    }

    public function mount()
    {
        $this->dispositions = $this->getDispositions();
    }

    public function createDisposition()
    {
        $this->validate();

        Disposition::create([
            'name' => $this->disposition['name'],
            'payable' => $this->disposition['payable'],
            'invoiceable' => $this->disposition['invoiceable']
        ]);

        $this->dispositions = $this->getDispositions();

        $this->resetForm();
    }

    public function editDisposition(Disposition $disposition)
    {
        $this->disposition = [
            'id' => $disposition->id,
            'name' => $disposition->name,
            'payable' => $disposition->payable,
            'invoiceable' => $disposition->invoiceable
        ];

        $this->dispatchBrowserEvent('editingDisposition');
    }

    public function updateDisposition()
    {
        $this->validate([
            'disposition.name' => [
                'required',
                'min:3',
                // 'unique:timy_dispositions,name'
            ]
        ]);

        $disposition = Disposition::findOrFail($this->disposition['id']);

        $disposition->update([
            'name' => $this->disposition['name'],
            'payable' => $this->disposition['payable'],
            'invoiceable' => $this->disposition['invoiceable']
        ]);

        $this->dispositions = $this->getDispositions();

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->disposition = [
            'id' => null,
            'name' => '',
            'payable' => false,
            'invoiceable' => false
        ];
    }

    protected function getDispositions()
    {
        return DispositionsRepository::all();
    }

    public function getIsEditingProperty()
    {
        return $this->disposition['id'] != null;
    }
}
