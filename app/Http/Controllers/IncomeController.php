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

        // Build income query (manual incomes)
        $incomeQuery = Income::where('user_id', $userId)
            ->select('amount', 'source', 'description', 'income_date');

        // Build invoice query (mapped to income-like fields)
        $invoiceQuery = DB::table('invoices')
            ->where('user_id', $userId)
            ->select([
                'total as amount',
                'to_name as source',
                'bill_no as description',
                'created_at as income_date',
            ]);

        // Merge both sources
        $incomes = $incomeQuery
            ->unionAll($invoiceQuery)
            ->orderBy('income_date', 'desc')
            ->paginate(10);

        // Total income (incomes + invoices)
        $totalIncome = Income::where('user_id', $userId)->sum('amount') +
            DB::table('invoices')->where('user_id', $userId)->sum('total');

        // Chart data
        $incomeChart = Income::where('user_id', $userId)
            ->select(DB::raw("DATE(income_date) as date"), DB::raw("SUM(amount) as total"))
            ->groupBy('date');

        $invoiceChart = DB::table('invoices')
            ->where('user_id', $userId)
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("SUM(total) as total"))
            ->groupBy('date');

        $chartData = $incomeChart->unionAll($invoiceChart)
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.income', compact('incomes', 'totalIncome', 'chartData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
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

        // Filtered manual incomes
        $incomeFiltered = Income::where('user_id', $userId)
            ->whereBetween('income_date', [$startDate, $endDate])
            ->sum('amount');

        // Filtered invoice incomes
        $invoiceFiltered = DB::table('invoices')
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        $filteredIncome = $incomeFiltered + $invoiceFiltered;

        // Reload full page data
        return $this->index()->with(compact('filteredIncome', 'startDate', 'endDate'));
    }

    public function destroy($id)
    {
        $income = Income::where('user_id', Auth::id())->findOrFail($id);
        $income->delete();

        return redirect()->route('income.index')->with('success', 'Income record deleted successfully!');
    }
}
