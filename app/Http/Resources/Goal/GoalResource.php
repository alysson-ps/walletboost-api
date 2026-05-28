<?php

namespace App\Http\Resources\Goal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $percentage = $this->target_amount > 0
            ? round(($this->current_amount / $this->target_amount) * 100, 2)
            : 0;

        return [
            'user_id' => $this->user_id,
            'name' => $this->name,
            'target_amount' => $this->target_amount,
            'current_amount' => $this->current_amount,
            'target_date' => $this->target_date,
            'percentage' => $percentage,
            'color' => $this->color,
        ];
    }
}
