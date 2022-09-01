<?php

namespace App\Http\Livewire;
use App\Models\Event;
use Livewire\WithPagination;
use App\Exports\EventExport;

use Livewire\Component;

class EventDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'id';

    public $sortDirection = 'asc';
    public $perPage = '10';
    public $search = '';


    public $open = false;


    public function render()
    {
        $events = Event::query()
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
        return view('livewire.event-datatable', [
            'events' => $events
        ]);
    }

    
    public function sortBy($field)
    {
        if($this->sortDirection == 'asc') {
            $this->sortDirection = 'desc';
        } else {
            $this->sortDirection = 'asc';
        }

        return $this->sortBy = $field;
    }
    public function exportSelected()
    {
        return (new EventExport())->download('event.xlsx');
    }
    public function pdfexport()
    {
        return (new EventExport())->download('event.pdf');
    }

    public function csvexport()
    {
        return (new EventExport())->download('event.csv');
    }
    public function updatingSearch()
    { 
         $this->resetPage(); 
    }

}
