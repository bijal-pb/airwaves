<?php

namespace App\Exports;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class RolesExport implements FromQuery
{
    use Exportable;
    protected $roles;

    public function _construct($roles)
    {
        $this->roles = $roles;
    }
    
    public function query()
    {
        return Role::query();
    }
}
