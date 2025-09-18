<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\DdExpenseCategory;
use App\Models\DdIncomeCategory;
use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $this->filter($request)->orderBy('id', 'desc')->paginate(10);
        return view('keyword.index', compact('keywords'));
    }

    private function filter(Request $request)
    {
        $query = Keyword::query();

        if ($request->has('title')) {
            $query->where('title', 'like', $request->input('title') . '%');
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        return $query;
    }

    public function create()
    {
        return view('keyword.create');
    }

    public function getCategories(Request $request)
    {
        $type = $request->type;
        $categories = [];

        if ($type == 'expense') {
            $categories = DdExpenseCategory::get(['id', 'title']);
        } elseif ($type == 'income') {
            $categories = DdIncomeCategory::get(['id', 'title']);
        }
        return response()->json($categories);

    }

    public function store(Request $request)
    {
        $this->validateKeyword($request);

        $data = $request->only(['category_id', 'title', 'type']);
        $data['created_by'] = auth()->id();
        $keyword = Keyword::create($data);

        return redirect()->route('keywords.edit', $keyword)
            ->with('success', 'Keyword created successfully.');
    }

    public function show(Keyword $keyword)
    {
        return view('keyword.show', compact('keyword'));
    }

    public function edit(Keyword $keyword)
    {
        return view('keyword.edit', compact('keyword'));
    }

    public function update(Request $request, Keyword $keyword)
    {
        $this->validateKeyword($request);

        $data = $request->only(['category_id', 'title', 'type']);
        $keyword->update($data);

        return redirect()->route('keywords.edit', $keyword)
            ->with('success', 'Keyword updated successfully.');
    }

    public function destroy(Keyword $keyword)
    {
        // Determine the type of the keyword and check its usage
        if ($keyword->type === 'income') {
            $isUsed = Income::where('income_category_id', $keyword->category_id)->exists();
        } elseif ($keyword->type === 'expense') {
            $isUsed = Expense::where('expense_category_id', $keyword->category_id)->exists();
        } else {
            $isUsed = false; // In case of an invalid type, assume it's not in use
        }
    
        // If the keyword is in use, prevent deletion
        if ($isUsed) {
            return redirect()->route('keywords.index')
                ->with('error', 'Keyword cannot be deleted as it is in use.');
        }
    
        // Proceed with deletion if not in use
        $keyword->delete();
    
        return redirect()->route('keywords.index')
            ->with('success', 'Keyword deleted successfully.');
    }
    

    public function fetchCategories(Request $request)
    {
        $type = $request->get('type');

        if ($type === 'expense') {
            $categories = DdExpenseCategory::where('status', 1)->pluck('title', 'id');
        } elseif ($type === 'income') {
            $categories = DdIncomeCategory::where('status', 1)->pluck('title', 'id');
        } else {
            $categories = [];
        }

        return response()->json($categories);
    }

    private function validateKeyword(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'title' => 'required|string|max:255',
            'category_id' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $type = $request->input('type');

                    if ($type === 'income') {
                        if (!DdIncomeCategory::where('id', $value)->exists()) {
                            $fail('The selected category ID is invalid for income.');
                        }
                    } elseif ($type === 'expense') {
                        if (!DdExpenseCategory::where('id', $value)->exists()) {
                            $fail('The selected category ID is invalid for expense.');
                        }
                    } else {
                        $fail('Invalid category type.');
                    }
                },
            ],
        ]);
    }
}
