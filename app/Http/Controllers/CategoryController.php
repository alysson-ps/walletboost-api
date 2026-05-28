<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $categories = Category::where('user_id', $request->user()->id)->get();

        return response()->json([
            'success' => true,
            'data' => $categories->toResourceCollection(),
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        DB::beginTransaction();

        $category = Category::create(
            array_merge(['user_id' => $request->user()->id], $request->validated())
        );

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $category->toResource(),
        ]);
    }

    public function destroy(int $id, Request $request)
    {
        DB::beginTransaction();

        $category = Category::where('user_id', $request->user()->id)
            ->findOrFail($id);

        $category->delete();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ]);
    }

    public function create(Request $request)
    {
        $categories = Category::whereNull('user_id')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'type' => [
                    [
                        'value' => 'income',
                        'label' => 'Receita',
                    ],
                    [
                        'value' => 'expense',
                        'label' => 'Despesa',
                    ]
                ],
                'categories' => $categories->toResourceCollection(),
            ]
        ]);
    }

    public function edit(int $id, Request $request)
    {
        $category = Category::where('user_id', $request->user()->id)
            ->findOrFail($id);
        
        $categories = Category::whereNull('user_id')->get();

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category->toResource(),
                'type' => [
                    [
                        'value' => 'income',
                        'label' => 'Receita',
                    ],
                    [
                        'value' => 'expense',
                        'label' => 'Despesa',
                    ]
                ],
                'categories' => $categories->toResourceCollection(),
            ]
        ]);
    }

    public function update(int $id, CategoryUpdateRequest $request)
    {
        $category = Category::where('user_id', $request->user()->id)
            ->findOrFail($id);

        DB::beginTransaction();

        $category->update($request->validated());

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $category->toResource(),
        ]);
    }
}
