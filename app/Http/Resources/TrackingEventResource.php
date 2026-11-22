<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrackingEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'shipment_id' => $this->shipment_id,
            'location_id' => $this->location_id,
            'status' => $this->status,
            'is_exception' => (bool) $this->is_exception,
            'latitude' => $this->latitude !== null ? (float) $this->latitude : null,
            'longitude' => $this->longitude !== null ? (float) $this->longitude : null,
            'event_time' => $this->event_time?->toISOString(),
            'description' => $this->description,
            'created_by' => $this->created_by,
            'location' => new LocationResource($this->whenLoaded('location')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
