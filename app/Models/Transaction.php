<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseResourceCollection;
use App\Http\Resources\Transaction\TransactionCollection;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use App\Http\Resources\Transaction\TransactionResource;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\TransactionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Enum\AccountTypeEnum;

#[UseResourceCollection(TransactionCollection::class)]
#[UseResource(TransactionResource::class)]
#[UseFactory(TransactionFactory::class)]
class Transaction extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'category_id',
        'recurrence_id',
        'type',
        'amount',
        'description',
        'occurred_at',
        'status',
        'parent_id',
        'installments_number',
        'installment_total',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'status' => TransactionStatusEnum::class,
        'type' => TransactionTypeEnum::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function recurrence()
    {
        return $this->belongsTo(Recurrence::class);
    }

    public function parent()
    {
        return $this->belongsTo(Transaction::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Transaction::class, 'parent_id');
    }

    /**
     *  Scopes
     */
    public function scopeAccountInvestment(Builder $query)
    {
        return $query->whereHas('account', function ($query) {
            $query->where('type', AccountTypeEnum::INVESTMENT);
        });
    }
}
