<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sites;

class SitesSearch extends Component
{
    public $search = '';
    public $results = [];

    public function updatedSearch()
    {
        if (strlen($this->search) > 0) {
            $this->results = Sites::where(function($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                ->orWhere('name', 'like', '%' . $this->search . '%')
                ->orWhere('ip_address', 'like', '%' . $this->search . '%')
                ->orWhere('remarks', 'like', '%' . $this->search . '%');
            })
                ->limit(10)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.sites-search');
    }
}