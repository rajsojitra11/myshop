@extends('admin.index')
@section('title', 'Income')
@section('page-title', 'Income')
@section('page', 'Income')

@section('content')

<div class="container-fluid">
    <div class="row">
        <!-- Total Income Card -->
        <div class="col-md-8">
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Income Overview</h3>
                        <form method="GET" action="{{ route('income.index') }}" class="form-inline ml-auto">
                            <select name="month" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                                @foreach(range(date('Y') - 5, date('Y')) as $y)
                                    <option value="{{ $y }}" {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height:300px; width:100%">
                            <canvas id="monthlyIncomeChart"></canvas>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Add Income Form -->
    <div class="col-md-4">
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
            <div class="card-body table-responsive" id="data-container">
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
                                <td>{{ $loop->iteration }}</td>
                                <td>₹ {{ number_format($income->amount, 2) }}</td>
                                <td>{{ $income->source ?? '-' }}</td>
                                <td>{{ $income->description ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($income->income_date)->format('d M Y') }}</td>                                
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No income records found.</td>
                            </tr>
                        @endforelse
                    </tbody>                        
                </table>
                <div class="mt-2 d-flex justify-content-end">
                    {{ $incomes->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>


</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('monthlyIncomeChart').getContext('2d');
        
        const rawChartData = {!! json_encode($chartData) !!};
        const incomeDataMap = {};
        
        // Ensure same dates add up
        rawChartData.forEach(item => {
            const date = item.date;
            const total = parseFloat(item.total);
            incomeDataMap[date] = (incomeDataMap[date] || 0) + total;
        });

        const selectedMonth = {{ request('month', date('m')) }};
        const selectedYear = {{ request('year', date('Y')) }};
        const totalDays = new Date(selectedYear, selectedMonth, 0).getDate();

        const labels = [];
        const values = [];

        for (let day = 1; day <= totalDays; day++) {
            const fullDateKey = `${selectedYear}-${String(selectedMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            labels.push(day);
            values.push(incomeDataMap[fullDateKey] || 0);
        }

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Income (₹)',
                    data: values,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: '#28a745',
                    borderWidth: 1,
                    borderRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { callback: v => '₹' + v.toLocaleString() } 
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 10 }, autoSkip: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            title: (items) => `Day ${items[0].label}`,
                            label: (context) => ` ₹${context.raw.toLocaleString()}`
                        }
                    }
                }
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.pagination a')) {
            e.preventDefault();
            let url = e.target.closest('.pagination a').href;
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(response => response.text())
                .then(html => {
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    let newContainer = doc.getElementById('data-container');
                    if (newContainer) {
                        document.getElementById('data-container').innerHTML = newContainer.innerHTML;
                        window.history.pushState(null, '', url);
                    }
                })
                .catch(err => console.error('Error fetching pagination:', err));
        }
    });
</script>

@endsection
