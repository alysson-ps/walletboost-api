<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    public static $wrap = null;

    public function with(Request $request)
    {
        return [
            'success' => true,
            'message' => 'Request was successful',
        ];
    }
}
