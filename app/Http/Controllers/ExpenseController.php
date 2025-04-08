<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $expenses = Expense::where('user_id', $user->id)
            ->orderBy('expense_date', 'desc')
            ->paginate(10);

        $total = Expense::where('user_id', $user->id)->sum('amount');

        $chartData = Expense::where('user_id', $user->id)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        return view('admin.expense', compact('expenses', 'total', 'chartData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        Expense::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'category' => $request->category,
            'description' => $request->description,
            'expense_date' => $request->expense_date,
        ]);

        return redirect()->route('expense')->with('success', 'Expense added successfully!');
    }
}
