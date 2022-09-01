<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Group;

class GroupExport implements FromQuery
{
    use Exportable;
    protected $groups;

    public function _construct($groups)
    {
        $this->groups = $groups;
    }
    
    public function query()
    {
        return Group::query();
    }
}
