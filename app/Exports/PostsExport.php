<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Post;


class PostsExport implements FromQuery
{
    use Exportable;
    protected $posts;

    public function _construct($posts)
    {
        $this->posts = $posts;
    }
    
    public function query()
    {
        return Post::query();
    }
}
