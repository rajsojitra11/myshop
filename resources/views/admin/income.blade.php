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
                    <span class="info-box-number">₹ 5000</span>
                </div>
            </div>

            <!-- Static Income Chart -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Income Overview (Static)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="staticIncomeChart" height="100"></canvas>
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
                <form method="POST" action="#">
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
                            {{-- @foreach ($incomes as $index => $income) --}}
                            <tr>
                                <td> $index + 1 </td>
                                <td>₹ $income->amount </td>
                                <td> $income->source </td>
                                <td> $income->description </td>
                                <td> Carbon\Carbon::parse($income->income_date)->format('d M Y') </td>
                            </tr>
                            {{-- @endforeach --}}
                        </tbody>
                    </table>
                    {{-- {{ $incomes->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('staticIncomeChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['01 Apr', '02 Apr', '03 Apr', '04 Apr', '05 Apr'],
            datasets: [{
                label: 'Income (₹)',
                data: [1000, 1200, 800, 1500, 500],
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
