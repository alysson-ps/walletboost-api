<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionParticipant extends Model
{
    protected $fillable = [
        'transaction_id',
        'participant_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    protected $appends = [
        'remaining_amount',
    ];

    public function getRemainingAmountAttribute(): float
    {
        $paidAmount = $this->payments()->sum('amount');
        return $this->amount - $paidAmount;
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function payments()
    {
        return $this->hasMany(ParticipantPayment::class);
    }

}
