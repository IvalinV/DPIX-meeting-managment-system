<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "date" => $this->date->format('M d Y H:i'),
            "status" => $this->status,
            "lawyer_first_name" => $this->lawyer->first_name,
            "lawyer_last_name" => $this->lawyer->last_name,
            "first_name" => $this->citizen->first_name,
            "last_name" => $this->citizen->last_name
        ];
    }
}
