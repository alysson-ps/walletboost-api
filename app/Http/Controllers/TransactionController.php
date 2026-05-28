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
        DB::beginTransaction();

        /**
         * @var User
         */
        $user = $request->user();

        $transaction = $user->transactions()->create($request->validated());

        DB::commit();

        return $transaction->toResource();
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
