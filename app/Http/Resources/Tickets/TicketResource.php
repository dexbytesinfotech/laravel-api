<?php

namespace App\Http\Resources\Tickets;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class TicketResource extends JsonResource
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
            'title' => $this->title,
            'content' => $this->content,
            'status' =>(string) $this->status,
            'category_id' => $this->category_id,
            'user_id' => $this->user_id,
            'user_name' => $this->user->name,
            'assigned_to_user_id' => (string)$this->assigned_to_user_id,
            'category_name' => $this->category->name,
           // 'assigned_to_user' => $this->assigned_to_user->name,
           'created_at' => Carbon::parse($this->created_at)->format(config('app_settings.date_format.value')),
           'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
       ];
    }
}
