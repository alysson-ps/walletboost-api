<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Http\Resources\Account\AccountCollection;
use App\Http\Resources\Account\AccountResource;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\AccountFactory;
use App\Observers\AccountObserver;
use Illuminate\Support\Str;

/**
 * @property int $user_id
 */
#[UseResourceCollection(AccountCollection::class)]
#[UseResource(AccountResource::class)]
#[ObservedBy(AccountObserver::class)]
#[UseFactory(AccountFactory::class)]
class Account extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'color',
        'balance'
    ];

    protected $appends = ['abbreviation'];

    public function getAbbreviationAttribute()
    {
        return Str::upper(substr($this->name, 0, 2));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
