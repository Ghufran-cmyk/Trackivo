<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Additional data to inject into resource (e.g. current_location, delay_analysis).
     */
    protected array $customFields = [];

    public function withCustomFields(array $fields): self
    {
        $this->customFields = $fields;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'tracking_number' => $this->tracking_number,
            'status' => $this->status,
            'transport_mode' => $this->transport_mode,
            'cargo_type' => $this->cargo_type,
            'weight_kg' => (float) $this->weight_kg,
            'customer_id' => $this->customer_id,
            'carrier_id' => $this->carrier_id,
            'origin_location_id' => $this->origin_location_id,
            'destination_location_id' => $this->destination_location_id,
            'expected_departure' => $this->expected_departure?->toISOString(),
            'expected_arrival' => $this->expected_arrival?->toISOString(),
            'estimated_arrival' => $this->estimated_arrival?->toISOString(),
            'delay_hours' => (int) $this->delay_hours,
            'delay_severity' => $this->delay_severity,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
            'customer' => new UserResource($this->whenLoaded('customer')),
            'carrier' => new CarrierResource($this->whenLoaded('carrier')),
            'origin_location' => new LocationResource($this->whenLoaded('originLocation')),
            'destination_location' => new LocationResource($this->whenLoaded('destinationLocation')),
            'items' => ShipmentItemResource::collection($this->whenLoaded('items')),
            'tracking_events' => TrackingEventResource::collection($this->whenLoaded('trackingEvents')),
            'exceptions' => ShipmentExceptionResource::collection($this->whenLoaded('exceptions')),
        ];

        foreach ($this->customFields as $key => $value) {
            $data[$key] = $value;
        }

        return $data;
    }
}
