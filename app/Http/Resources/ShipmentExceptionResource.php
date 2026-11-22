<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentExceptionResource extends JsonResource
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
            'type' => $this->type,
            'severity' => $this->severity,
            'status' => $this->status,
            'delay_impact_hours' => (int) $this->delay_impact_hours,
            'description' => $this->description,
            'resolution_note' => $this->resolution_note,
            'detected_at' => $this->detected_at?->toISOString(),
            'resolved_at' => $this->resolved_at?->toISOString(),
            'reported_by' => $this->reported_by,
            'resolved_by' => $this->resolved_by,
            'reporter' => new UserResource($this->whenLoaded('reporter')),
            'resolver' => new UserResource($this->whenLoaded('resolver')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
