<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'balance' => [
                ...$this->resource['balance'],
                'value' => round($this->resource['balance']['value'], 2),
            ],
            'income' => [
                ...$this->resource['income'],
                'value' => round($this->resource['income']['value'], 2),
            ],
            'expenses' => [
                ...$this->resource['expenses'],
                'value' => round($this->resource['expenses']['value'], 2),
            ],
            'investments' => [
                ...$this->resource['investments'],
                'value' => round($this->resource['investments']['value'], 2),
            ],
        ];
    }
}
