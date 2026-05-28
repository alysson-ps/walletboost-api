<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransactionCollection extends ResourceCollection
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
            'meta' => ['transaction_count' => $this->collection->count()]
        ];
    }
}
