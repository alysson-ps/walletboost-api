<?php

namespace App\Http\Resources\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->all();
    }

    public function with(Request $request)
    {
        return [
            'success' => true,
            'meta' => ['account_count' => $this->collection->count()],
        ];
    }
}
