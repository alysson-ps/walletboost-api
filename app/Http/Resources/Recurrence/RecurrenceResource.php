<?php

namespace App\Http\Resources\Recurrence;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class RecurrenceResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => $this->amount,
            'description' => $this->description,
            'frequency' => $this->frequency,
            'starts_at' => $this->starts_at,
            'active' => $this->active,
            'color' => $this->color,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
