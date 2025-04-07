@extends('admin.index')
@section('title', 'Expense')
@section('page-title', 'Expense')
@section('page', 'Expense')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Total Expense Card -->
        <div class="col-md-4">
            <div class="info-box bg-danger">
                <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Expense</span>
                    <span class="info-box-number">₹ 2000</span>
                </div>
            </div>
            <!-- Static Expense Chart -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Expense Overview (Static)</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="staticExpenseChart" height="100"></canvas>
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
                <form method="POST" action="#">
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
                            {{-- @foreach ($expenses as $index => $expense) --}}
                            <tr>
                                <td>$index + 1 </td>
                                <td>₹$expense->amount</td>
                                <td>$expense->category</td>
                                <td>$expense->description</td>
                                <td>$expense->expense_date->format('d M Y') </td>
                            </tr>
                            {{-- @endforeach --}}
                        </tbody>
                    </table>
                    {{-- {{ $expenses->links() }} <!-- Pagination --> --}}
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('staticExpenseChart').getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Food', 'Transport', 'Bills', 'Shopping', 'Other'],
            datasets: [{
                label: 'Expense Categories',
                data: [1200, 800, 500, 700, 300],
                backgroundColor: [
                    '#f56954', // red
                    '#00a65a', // green
                    '#f39c12', // yellow
                    '#00c0ef', // aqua
                    '#3c8dbc'  // blue
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
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
</script>
@endsection
    