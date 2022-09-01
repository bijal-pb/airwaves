<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\Event;

class EventExport implements FromQuery
{
    use Exportable;
    protected $events;

    public function _construct($events)
    {
        $this->events = $events;
    }
    
    public function query()
    {
        return Event::query();
    }
}
