<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Resources\PersonalAccessToken\PersonalAccessTokenResource;
use DateTime;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Override;

/**
 * @property DateTime $expires_at
 */
#[UseResource(PersonalAccessTokenResource::class)]
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    protected function IsExpired(): Attribute
    {
        return Attribute::get(
            fn() => $this->expires_at <= now()
        );
    }
}
