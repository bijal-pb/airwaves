<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'gender' => $this->gender == 1 ? 'Male' : 'Female',
            'bio' => $this->bio,
            'status' => $this->status,
            'lat' => $this->latitude,
            'lang' => $this->longitude,
            'address' => $this->address,
            'online' => intval($this->online),
            'tracks' => $tracks,
            'device_token' => $this->device_token,
            'social_type' => $this->social_type,
            'is_notificaion' => $this->is_notification
        ];
    }
}
