<?php

declare(strict_types=1);

namespace App\Http\Resources\PersonalAccessToken;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class PersonalAccessTokenResource extends BaseResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name'=> $this->name,
            'abilities' => $this->abilities,
            'expires_at' => $this->expires_at
        ];
    }
}
