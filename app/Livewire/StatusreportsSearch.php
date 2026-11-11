<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Statusreports;

class StatusreportsSearch extends Component
{
    public $search = '';
    public $results = [];

    public function updatedSearch()
    {
        if (strlen($this->search) > 0) {
            $this->results = Statusreports::where(function($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                ->orWhere('title', 'like', '%' . $this->search . '%')
                ->orWhere('basis', 'like', '%' . $this->search . '%');
            })
                ->limit(10)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.statusreports-search');
    }
}