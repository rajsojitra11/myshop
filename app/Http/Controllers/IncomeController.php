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

        $incomes = Income::where('user_id', $userId)
            ->orderBy('income_date', 'desc')
            ->paginate(10);

        $totalIncome = Income::where('user_id', $userId)->sum('amount');

        // Group by date and sum income for chart
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
}
