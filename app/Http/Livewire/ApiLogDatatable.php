<?php

namespace App\Http\Livewire;

use App\Exports\ApiLogsExport;
use Livewire\Component;
use App\Models\ApiLog;
use Livewire\WithPagination;

class ApiLogDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'id';

    public $sortDirection = 'asc';
    public $perPage = '10';
    public $search = '';


    public function render()
    {
        $logs = ApiLog::query()
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
        return view('livewire.api-log-datatable', [
            'logs' => $logs
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
        return (new ApiLogsExport())->download('apilogs.xlsx');
    }

    public function pdfexport()
    {
        return (new ApiLogsExport())->download('apilogs.pdf');
    }

    public function csvexport()
    {
        return (new ApiLogsExport())->download('apilogs.csv');
    }
    public function updatingSearch()
    { 
         $this->resetPage(); 
    }
}
