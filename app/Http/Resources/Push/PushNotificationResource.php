<?php

namespace App\Http\Resources\Push;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PushNotificationResource extends JsonResource
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
            'id' => (int) $this->id,
            'is_read' => (bool) ($this->PushDeliveredMessage->is_read == 'no') ? 0 : 1,
            'is_displayed' => (bool) ($this->PushDeliveredMessage->is_displayed == 'no') ? 0 : 1,
            'should_visible' => (bool) $this->should_visible,
            'title' => (string) $this->title,
            'body' => (string) $this->text,
            'data' => (array) ($this->action_value) ? json_decode($this->action_value) : [],
            'send_at' => Carbon::parse($this->send_at)->format(config('app_settings.date_format.value')),
            'updated_at' => Carbon::parse($this->updated_at)->format(config('app_settings.date_format.value')),
        ];
    }
}
