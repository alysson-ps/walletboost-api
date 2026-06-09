<?php

namespace App\Http\Resources\Transaction;

use App\Http\Resources\BaseResource;
use Illuminate\Http\Request;

class TransactionResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'account' => $this->whenLoaded('account'),
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('category'),
            'description' => $this->description,
            'type' => $this->type,
            'amount' => $this->amount,
            'occurred_at' => $this->occurred_at,
            'status' => $this->status,
            'parent_id' => $this->parent_id,
            'installments_number' => $this->installments_number,
            'installment_total' => $this->installment_total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
