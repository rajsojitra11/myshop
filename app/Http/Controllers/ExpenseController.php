<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request = null)
    {
        $request = $request ?? request();
        $user = Auth::user();
        
        $month = $request->query('month', date('m'));
        $year = $request->query('year', date('Y'));

        $query = Expense::where('user_id', $user->id);

        // Expenses for the table
        $expenses = $query->orderBy('expense_date', 'desc')->paginate(10);

        // Overall total
        $total = Expense::where('user_id', $user->id)->sum('amount');

        // Daily chart data
        $chartData = Expense::where('user_id', $user->id)
            ->whereMonth('expense_date', $month)
            ->whereYear('expense_date', $year)
            ->select(DB::raw("DATE(expense_date) as date"), DB::raw("SUM(amount) as total"))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.expense', compact('expenses', 'total', 'chartData'));
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
