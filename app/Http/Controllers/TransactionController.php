<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TransactionStoreRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Participant;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct() {}

    public function create(Request $request)
    {
        $accounts = Account::where('user_id', $request->user()->id)->get();

        $categories = Category::where(function ($query) use ($request) {
            $query->whereNull('user_id')->orWhere('user_id', $request->user()->id);
        })->get();

        $participants = Participant::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'accounts' => $accounts->toResourceCollection(),
                'categories' => $categories->toResourceCollection(),
                'participants' => $participants->toResourceCollection(),
            ]
        ]);
    }

    public function edit(int $id, Request $request)
    {
        $transaction = Transaction::with(['account', 'category', 'participants'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        $accounts = Account::where('user_id', $request->user()->id)->get();

        $categories = Category::where(function ($query) use ($request) {
            $query->whereNull('user_id')->orWhere('user_id', $request->user()->id);
        })->get();

        $participants = Participant::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'transaction' => $transaction->toResource(),
                'accounts' => $accounts->toResourceCollection(),
                'categories' => $categories->toResourceCollection(),
                'participants' => $participants->toResourceCollection(),
            ]
        ]);
    }

    public function store(TransactionStoreRequest $request)
    {
        /** @var User */
        $user = $request->user();
        $data = $request->validated();
        $installmentTotal = $data['installment_total'] ?? null;

        DB::beginTransaction();

        try {
            if ($installmentTotal && $installmentTotal > 1) {
                $transaction = $this->createInstallments($user, $data, $installmentTotal);
            } else {
                $transaction = $user->transactions()->create($data);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return $transaction->toResource();
    }

    private function createInstallments(User $user, array $data, int $installmentTotal): Transaction
    {
        $totalAmount = $data['amount'];
        $installmentAmount = round($totalAmount / $installmentTotal, 2);
        $baseDate = Carbon::parse($data['occurred_at']);

        $parent = $user->transactions()->create(array_merge($data, [
            'amount' => $installmentAmount,
            'occurred_at' => $baseDate,
            'installments_number' => 1,
            'installment_total' => $installmentTotal,
            'parent_id' => null,
        ]));

        for ($i = 2; $i <= $installmentTotal; $i++) {
            // Last installment absorbs rounding difference
            $amount = $i === $installmentTotal
                ? round($totalAmount - ($installmentAmount * ($installmentTotal - 1)), 2)
                : $installmentAmount;

            $user->transactions()->create(array_merge($data, [
                'amount' => $amount,
                'occurred_at' => $baseDate->copy()->addMonths($i - 1),
                'installments_number' => $i,
                'installment_total' => $installmentTotal,
                'parent_id' => $parent->id,
            ]));
        }

        return $parent;
    }


    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->paginate(15);

        return $transactions->toResourceCollection();
    }

    // TODO: Add paginate
    public function recents(Request $request)
    {
        $trasactions = Transaction::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return $trasactions->toResourceCollection();
    }
}
