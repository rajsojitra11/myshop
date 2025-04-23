@extends('admin.index')
@section('title', 'Income')
@section('page-title', 'Income')
@section('page', 'Income')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Total Income Card -->
        <div class="col-md-4">
            <div class="info-box bg-success">
                <span class="info-box-icon"><i class="fas fa-coins"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Income</span>
                    <span class="info-box-number">₹ {{ number_format($totalIncome, 2) }}</span>
                </div>
            </div>

            <!-- Static Income Chart -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Income Overview</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="staticIncomeChart" height="100"></canvas>
                        </div>
                    </div>
                     <!-- Filter Income by Date Range -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">Find Income Between Dates</h3>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('income.filter') }}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" name="end_date" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info w-100 mt-3">Search</button>
                            </form>
                            
                        </div>
                    </div>

                    <!-- Display Filtered Total Income -->
                    @if(isset($filteredIncome))
<div class="row mt-3">
    <div class="col-md-12">
        <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-coins"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Income (Filtered)</span>
                <span class="info-box-number">₹ {{ number_format($filteredIncome, 2) }}</span>
                <span class="info-box-text small">From {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
            </div>
        </div>
    </div>
</div>
@endif

                </div>
            </div>
                </div>
            </div>
        </div>

        <!-- Add Income Form -->
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add New Income</h3>
                </div>
                <form method="POST" action="{{ route('income.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="source">Source</label>
                            <input type="text" class="form-control" name="source" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="income_date">Date</label>
                            <input type="date" class="form-control" name="income_date" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">Add Income</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Income List Table -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Income List</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Source</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($incomes as $index => $income)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>₹ {{ number_format($income->amount, 2) }}</td>
                                    <td>{{ $income->source }}</td>
                                    <td>{{ $income->description }}</td>
                                    <td>{{ \Carbon\Carbon::parse($income->income_date)->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No income records found.</td>
                                </tr>
                            @endforelse
                        </tbody>                        
                    </table>
                    <div class="mt-2">
                        {{ $incomes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('staticIncomeChart').getContext('2d');

    const incomeLabels = {!! json_encode($chartData->pluck('date')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d M'))->toArray()) !!};
    const incomeData = {!! json_encode($chartData->pluck('total')->toArray()) !!};

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: incomeLabels,
            datasets: [{
                label: 'Income (₹)',
                data: incomeData,
                backgroundColor: '#28a745',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value;
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₹' + context.raw;
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
