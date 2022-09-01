<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
     /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $photo = null;
        // if($this->photo != null)
        // {
        //     $photo = url('/groupimages/'.$this->photo);
        // }
        $event_count = null;
        if($this->total_event != null)
        {
            $event_count = $this->total_event;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'location' => $this->location,
            'lat' => $this->lat,
            'lang' => $this->lang,
            'required_join' => $this->required_join,
            'create_event' => $this->create_event,
            'photo' => $this->photo,
            'genres_id' => $this->genres_id,
            'genres_detail' => $this->genres,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'group_user' => $this->groupuser,
            'event' => $this->event,
            'total_event' => $event_count
        ];
    }
}
