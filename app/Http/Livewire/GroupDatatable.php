<?php

namespace App\Http\Livewire;

use App\Exports\GroupExport;
use Livewire\Component;
use App\Models\Group;
use Livewire\WithPagination;
use Auth;

class GroupDatatable extends Component
{  
    use WithPagination;

    public $sortBy = 'id';

    public $sortDirection = 'asc';
    public $perPage = '10';
    public $search = '';

    public $editModel = false;
    public $deleteModel = false;

    public $editGroup;
    public $deleteId = null;

    public $open = false;



    public function render()
    {
        $groups = Group::query()
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
        return view('livewire.group-datatable', [
            'groups' => $groups
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
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->deleteModel = true;
    }

    public function closeDeleteModel()
    {
        $this->deleteModel = false;
    }

    public function groupdelete()
    {
        $group = Group::find($this->deleteId);
        $group->delete();
        $this->deleteId = null;
        $this->deleteModel = false;
        $this->dispatchBrowserEvent('alert', 
                ['type' => 'success',  'message' => 'Group Deleted Successfully!']);
    }
    

    public function exportSelected()
    {
        return (new GroupExport())->download('group.xlsx');
    }

    public function pdfexport()
    {
        return (new GroupExport())->download('group.pdf');
    }

    public function csvexport()
    {
        return (new GroupExport())->download('group.csv');
    }
    public function updatingSearch()
    { 
         $this->resetPage(); 
    }
}
