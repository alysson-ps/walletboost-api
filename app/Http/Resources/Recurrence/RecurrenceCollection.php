<?php

namespace App\Http\Resources\Recurrence;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RecurrenceCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->all();
    }

    public function with(Request $request)
    {
        return [
            'success' => true,
            'meta' => ['recurrence_count' => $this->collection->count()],
        ];
    }
}
