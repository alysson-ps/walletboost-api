<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Resources\Goal\GoalCollection;
use App\Http\Resources\Goal\GoalResource;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\GoalFactory;

#[UseResourceCollection(GoalCollection::class)]
#[UseResource(GoalResource::class)]
#[UseFactory(GoalFactory::class)]
class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'target_amount',
        'current_amount',
        'target_date',
        'color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }   
}
