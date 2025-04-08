@extends('admin.index')
@section('title', 'Expense')
@section('page-title', 'Expense')
@section('page', 'Expense')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Total Expense Card -->
        <div class="col-md-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Expense</span>
                    <span class="info-box-number">₹ {{ number_format($total, 2) }}</span>
                </div>
            </div>

            <!-- Dynamic Expense Chart -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Expense Overview</h3>
                        </div>
                        <div class="card-body">
                            @if($chartData->isEmpty())
                                <p class="text-center text-muted">No expense data to display in the chart.</p>
                            @else
                                <canvas id="staticExpenseChart" height="100"></canvas>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Expense Form -->
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add New Expense</h3>
                </div>
                <form method="POST" action="{{ route('expenses.store') }}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="text" class="form-control" name="category" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="expense_date">Date</label>
                            <input type="date" class="form-control" name="expense_date" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-danger">Add Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Expense List Table -->
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-outline card-secondary">
                <div class="card-header">
                    <h3 class="card-title">Expense List</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenses as $index => $expense)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>₹{{ $expense->amount }}</td>
                                    <td>{{ $expense->category }}</td>
                                    <td>{{ $expense->description }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No expenses recorded.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    @if(!$chartData->isEmpty())
        const ctx = document.getElementById('staticExpenseChart').getContext('2d');
        const chartLabels = {!! json_encode($chartData->pluck('category')) !!};
        const chartValues = {!! json_encode($chartData->pluck('total')) !!};

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Expense Categories',
                    data: chartValues,
                    backgroundColor: [
                        '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc',
                        '#8e44ad', '#1abc9c', '#d35400', '#2c3e50', '#c0392b'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ₹' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    @endif
</script>
@endsection
