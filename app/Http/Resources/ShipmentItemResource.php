<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentItemResource extends JsonResource
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
            'item_name' => $this->item_name,
            'quantity' => (int) $this->quantity,
            'weight_kg' => (float) $this->weight_kg,
            'description' => $this->description,
        ];
    }
}
