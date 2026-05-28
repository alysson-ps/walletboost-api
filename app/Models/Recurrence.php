<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Resources\Recurrence\RecurrenceCollection;
use App\Http\Resources\Recurrence\RecurrenceResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseResource(RecurrenceResource::class)]
#[UseResourceCollection(RecurrenceCollection::class)]
class Recurrence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'frequency',
        'starts_at',
        'active',
        'color',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'active' => 'boolean',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}