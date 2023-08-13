<?php

namespace App\Http\Resources\Pages;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'title' => (string)$this->title,
            'slug' => (string)$this->slug,
            'content' => (string)$this->content,
            'status' =>(string) $this->status,
            'user_id'=>(integer)$this->user_id,
            'user_name' => (string)$this->user->name,
            'post_type' => (string)$this->post_type,
            'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
