<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DashboardFilter extends Component
{
    public $startDate;
    public $endDate;
    
    protected $listeners = ['filterUpdated' => 'updateFilter'];
    
    public function mount()
    {
        // Set default to current month
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
    }
    
    public function updateFilter($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->emit('periodChanged', $startDate, $endDate);
    }
    
    public function applyFilter()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);
        
        $this->emit('periodChanged', $this->startDate, $this->endDate);
    }
    
    public function resetFilter()
    {
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->endOfMonth()->format('Y-m-d');
        $this->emit('periodChanged', $this->startDate, $this->endDate);
    }
    
    public function render()
    {
        return view('livewire.dashboard-filter');
    }
}