<?php

namespace App\Http\Livewire;

use App\Exports\PostsExport;
use Livewire\Component;
use App\Models\Post;
use Livewire\WithPagination;
use Auth;


class PostDatatable extends Component
{
    use WithPagination;

    public $sortBy = 'id';

    public $sortDirection = 'desc';
    public $perPage = '10';
    public $search = '';

    public $editModel = false;
    public $deleteModel = false;

    public $editPost;
    public $deleteId = null;

    public $open = false;

    public function render()
    {
        $posts = Post::select('posts.*','users.first_name as post_by')
                // ->with('getUser')
                ->leftJoin('users','posts.user_id','=','users.id')
                ->search($this->search)
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);
        return view('livewire.post-datatable', [
            'posts' => $posts
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

    public function postdelete()
    {
        $post = Post::find($this->deleteId);
        $post->delete();
        $this->deleteId = null;
        $this->deleteModel = false;
        $this->dispatchBrowserEvent('alert', 
                ['type' => 'success',  'message' => 'Post Deleted Successfully!']);
    }
    

    public function exportSelected()
    {
        return (new PostsExport())->download('posts.xlsx');
    }

    public function pdfexport()
    {
        return (new PostsExport())->download('posts.pdf');
    }

    public function csvexport()
    {
        return (new PostsExport())->download('posts.csv');
    }
    public function updatingSearch()
    { 
         $this->resetPage(); 
    }

}