<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Expense::where('user_id', $user->id);

        if ($startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        }

        // Clone for filtered sum BEFORE paginate modifies the query
        $filteredQuery = clone $query;

        // Expenses for the table (paginate must be last)
        $expenses = $query->orderBy('expense_date', 'desc')->paginate(10);

        // Overall total (not filtered)
        $total = Expense::where('user_id', $user->id)->sum('amount');

        // Filtered total
        $filteredTotal = ($startDate && $endDate) ? $filteredQuery->sum('amount') : null;

        // Chart data (can also be filtered if needed)
        $chartData = Expense::where('user_id', $user->id)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        return view('admin.expense', compact(
            'expenses',
            'total',
            'filteredTotal',
            'chartData',
            'startDate',
            'endDate'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
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

    public function destroy($id)
    {
        $expense = Expense::where('user_id', Auth::id())->findOrFail($id);
        $expense->delete();

        return redirect()->route('expense')->with('success', 'Expense deleted successfully!');
    }
}
