<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\AccountTypeEnum;
use App\Http\Requests\AccountStoreRequest;
use App\Http\Requests\AccountUpdateRequest;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

final class AccountController extends Controller
{
    public function index(Request $request)
    {
        $accounts = Account::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => $accounts->toResourceCollection(),
        ]);
    }

    public function create()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'types' => array_map(
                    fn(AccountTypeEnum $type) => ['value' => $type->value, 'label' => $type->value],
                    AccountTypeEnum::cases()
                ),
            ],
        ]);
    }

    public function store(AccountStoreRequest $request)
    {
        DB::beginTransaction();

        $account = Account::create(
            array_merge(['user_id' => $request->user()->id], $request->validated())
        );

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $account->toResource(),
        ], 201);
    }

    public function update(int $id, AccountUpdateRequest $request)
    {
        $account = Account::where('user_id', $request->user()->id)->findOrFail($id);

        DB::beginTransaction();

        $account->update($request->validated());

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $account->toResource(),
        ]);
    }

    public function destroy(int $id, Request $request)
    {
        $account = Account::where('user_id', $request->user()->id)->findOrFail($id);

        DB::beginTransaction();

        $account->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Account deleted successfully',
        ]);
    }
}
