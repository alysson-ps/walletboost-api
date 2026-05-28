<?php

namespace App\Http\Resources\Goal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GoalCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->all();
    }

    public function with(Request $request)
    {
        return [
            'success' => true,
            'meta' => ['goal_count' => $this->collection->count()]
        ];
    }
}
