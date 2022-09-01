<?php

namespace App\Exports;

use App\Models\ApiLog;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ApiLogsExport implements FromQuery
{
    use Exportable;
    protected $apilogs;

    public function _construct($apilogs)
    {
        $this->apilogs = $apilogs;
    }
    
    public function query()
    {
        return ApiLog::query();
    }
}
