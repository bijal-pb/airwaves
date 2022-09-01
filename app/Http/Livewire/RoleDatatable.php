<?php

namespace App\Http\Livewire;

use App\Exports\RolesExport;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\WithPagination;
use Auth;



class RoleDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'id';

    public $sortDirection = 'asc';
    public $perPage = '10';
    public $search = '';

    public $editModel = false;
    public $deleteModel = false;

    public $editRole;
    public $deleteId = null;

    public $open = false;


    public function render()
    {
        $roles = Role::query();
        if(Auth::id() != 3){
            $roles = $roles->where('id','!=',3);
        }
        $roles = $roles->where('id','like','%'.$this->search.'%')
                ->orwhere('name','like','%'.$this->search.'%')
                ->where('name','!=','developer')
              //  ->where('id','like','%'.$this->search.'%')
              //  ->where('name','like','%'.$this->search.'%')
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
        return view('livewire.role-datatable', [
            'roles' => $roles
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

    public function roleDelete()
    {
        $role = Role::find($this->deleteId);
        $role->delete();
        $this->deleteId = null;
        $this->deleteModel = false;
        $this->dispatchBrowserEvent('alert', 
                ['type' => 'success',  'message' => 'Role Deleted Successfully!']);
    }

    public function exportSelected()
    {
        return (new RolesExport())->download('roles.xlsx');
    }

    public function pdfexport()
    {
        return (new RolesExport())->download('roles.pdf');
    }

    public function csvexport()
    {
        return (new RolesExport())->download('roles.csv');
    }
    public function updatingSearch()
    { 
         $this->resetPage(); 
    }
}