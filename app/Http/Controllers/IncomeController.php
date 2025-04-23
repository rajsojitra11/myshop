<?php

namespace App\Http\Controllers;

use App\Models\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Fetch all income records for the logged-in user
        $incomes = Income::where('user_id', $userId)
            ->orderBy('income_date', 'desc')
            ->paginate(10);

        // Calculate total income
        $totalIncome = Income::where('user_id', $userId)->sum('amount');

        // Prepare data for the income chart (grouped by date)
        $chartData = Income::where('user_id', $userId)
            ->select(DB::raw("DATE(income_date) as date"), DB::raw("SUM(amount) as total"))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.income', compact('incomes', 'totalIncome', 'chartData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'source' => 'required|string|max:255',
            'description' => 'nullable|string',
            'income_date' => 'required|date',
        ]);

        Income::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'source' => $request->source,
            'description' => $request->description,
            'income_date' => $request->income_date,
        ]);

        return redirect()->route('income.index')->with('success', 'Income added successfully!');
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $userId = Auth::id();
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Calculate total income between the given dates
        $filteredIncome = Income::where('user_id', $userId)
            ->whereBetween('income_date', [$startDate, $endDate])
            ->sum('amount');

        // Fetch existing incomes and total income for the main page
        $incomes = Income::where('user_id', $userId)
            ->orderBy('income_date', 'desc')
            ->paginate(10);

        $totalIncome = Income::where('user_id', $userId)->sum('amount');

        $chartData = Income::where('user_id', $userId)
            ->select(DB::raw("DATE(income_date) as date"), DB::raw("SUM(amount) as total"))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.income', compact('incomes', 'totalIncome', 'chartData', 'filteredIncome', 'startDate', 'endDate'));
    }

    public function destroy($id)
    {
        $income = Income::where('user_id', Auth::id())->findOrFail($id);
        $income->delete();

        return redirect()->route('income.index')->with('success', 'Income record deleted successfully!');
    }
}
