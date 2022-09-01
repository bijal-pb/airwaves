<?php

namespace App\Http\Livewire;

use App\Exports\UsersExport;
use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Auth;


class UserDatatable extends Component
{
    use WithPagination;

    // protected $paginationTheme = 'bootstrap';

    public $sortBy = 'id';
    public $confirming;

    public $sortDirection = 'desc';
    public $perPage = '10';
    public $search = '';

    public $editModel = false;
    public $deleteModel = false;

    public $editUser;
    public $deleteId = null;

    public $open = false;


    public function render()
    {
        $users = User::query();
        if(Auth::id() == 2){
            $users = $users->whereHas('roles', function($q){
                $q->whereIn('name', ['admin','user','developer']);
            });
        } else {
            $users = $users->whereHas('roles', function($q){
                $q->whereIn('name', ['admin','user']);
            });
        }

        $users = $users->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
        return view('livewire.user-datatable', [
            'users' => $users
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

    public function userDelete()
    {
        $user = User::find($this->deleteId);
        $user->delete();
        $this->deleteId = null;
        $this->deleteModel = false;
        $this->dispatchBrowserEvent('alert', 
                ['type' => 'success',  'message' => 'User Delete Successfully!']);
    }

    public function exportSelected()
    {
        return (new UsersExport())->download('users.xlsx');
    }

    public function pdfexport()
    {
        return (new UsersExport())->download('users.pdf');
    }

    public function csvexport()
    {
        return (new UsersExport())->download('users.csv');
    }
    public function updatingSearch()
    { 
         $this->resetPage(); 
    }
}