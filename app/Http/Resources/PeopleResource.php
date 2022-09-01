<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PeopleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $distance = null;
        if($this->distance)
        {
            $distance = $this->distance;
        }
        // $photo = null;
        // if($this->photo != null)
        // {
        //     $photo = url('/uploads/'.$this->photo);
        // }
        $tracks = null;
        if($this->tracks)
        {
            $tracks = $this->tracks;
        }
        return [
            'id' => $this->id,
            'name' => $this->name,    
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'photo' => $this->photo,
            'gender' => $this->gender,
            'bio' => $this->bio,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'address' => $this->address,
            'social_type' => $this->social_type,
            'social_id' => $this->social_id,
            'status' => $this->status,
            'online' => intval($this->online),
            'distance' => $distance,
            'track' => $tracks,
            'device_token' => $this->device_token,
        ];
    }
}
