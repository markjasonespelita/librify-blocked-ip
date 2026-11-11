<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Municipalities;

class MunicipalitiesSearch extends Component
{
    public $search = '';
    public $results = [];

    public function updatedSearch()
    {
        if (strlen($this->search) > 0) {
            $this->results = Municipalities::where(function($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                ->orWhere('name', 'like', '%' . $this->search . '%');
            })
                ->limit(10)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.municipalities-search');
    }
}