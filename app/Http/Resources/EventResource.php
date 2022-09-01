<?php

namespace App\Http\Resources;
use Illuminate\Support\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $event_photo = null;
        // if($this->event_photo != null)
        // {
        //     $event_photo = url('/eventimages/'.$this->event_photo);
        // } 

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'event_time'  => $this->event_time,
            'event_date'  => $this->event_date,
            'event_photo' => $this->event_photo,
            'group_id' => $this->group_id,
            'event_created_by' => $this->event_created_by
        ];
        
    }   


}
