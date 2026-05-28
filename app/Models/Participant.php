<?php

namespace App\Models;

use App\Http\Resources\Participant\ParticipantResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseResource(ParticipantResource::class)]
class Participant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'me',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
