<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // if($this->postBy->photo != null)
        // {
        //     $this->postBy->photo = url('/uploads/'.$this->postBy->photo);
        // }
        // if(count($this->postComments) > 0)
        // {
        //     foreach($this->postComments as $pc)
        //     {
        //         if($pc->commentBy != null)
        //         {
        //             $pc->commentBy->profile_photo = url('/uploads/'.$pc->commentBy->photo);
        //         }
        //     }
        // }
        if(count($this->media) > 0)
        {
            foreach($this->media as $md)
            {
                if($md->type == 'image')
                {
                    $md->media = url('/images/'.$md->media);
                }
                if($md->type == 'video')
                {
                    // $thumb_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $md->media);
                    $thumb_name = $md->media;
                    $md->media = url('/videos/'.$md->media);
                    $thumb_name = $thumb_name.'.jpg';
                    $md->thumb_image = url('/thumbs/'.$thumb_name);
                    //$md->thumb_image = url('/thumbs/play_common.png');
                }
            }
        }
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,    
            'message' => $this->message,
            'privacy' => $this->privacy,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total_likes' => $this->total_likes,
            'total_unlikes' => $this->total_unlikes,
            'total_comments' => $this->total_comments,
            'is_like' => $this->is_like,
            'is_save' => $this->is_save,
            'post_by' => $this->postBy,
            'media' => $this->media,
            'post_comments' => $this->postComments
        ];
    }
}
