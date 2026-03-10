<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Dashboard - {{ $supplier->company_name }}</title>
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Top Navbar */
        .supplier-navbar {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 15px 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .navbar-brand h2 {
            margin: 0;
            color: #333;
            font-size: 24px;
            font-weight: 600;
        }

        .navbar-brand .supplier-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .navbar-actions a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #dc3545;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .navbar-actions a:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        /* Main Container */
        .supplier-container {
            padding: 40px 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Welcome Section */
        .welcome-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .welcome-text {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .welcome-subtitle {
            color: #666;
            font-size: 14px;
        }

        /* Profile Cards Grid */
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #667eea;
        }

        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .card-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .card-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .card-value {
            font-size: 18px;
            color: #333;
            font-weight: 600;
            word-break: break-word;
        }

        .card-value a {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s;
        }

        .card-value a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        /* Stock Records Section */
        .stock-section {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .section-header-icon {
            font-size: 28px;
        }

        .section-header h3 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
        }

        .section-header-meta {
            margin-left: auto;
            font-size: 13px;
            opacity: 0.9;
        }

        /* Table Styles */
        .table-wrapper {
            overflow-x: auto;
        }

        .stock-section table {
            margin: 0;
        }

        .stock-section table thead {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .stock-section table thead th {
            color: #333;
            font-weight: 600;
            padding: 18px 15px;
            border: none;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stock-section table tbody tr {
            border-bottom: 1px solid #dee2e6;
            transition: background-color 0.2s;
        }

        .stock-section table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .stock-section table tbody td {
            padding: 16px 15px;
            color: #555;
            vertical-align: middle;
        }

        .quantity-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .price-text {
            color: #28a745;
            font-weight: 600;
            font-size: 15px;
        }

        .date-text {
            color: #999;
            font-size: 13px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
        }

        .empty-icon {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .empty-text {
            color: #999;
            font-size: 16px;
        }

        /* Pagination */
        .pagination-wrapper {
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: center;
        }

        .pagination {
            margin: 0;
        }

        .pagination .page-link {
            color: #667eea;
            border-color: #dee2e6;
            transition: all 0.3s;
        }

        .pagination .page-link:hover {
            color: #764ba2;
            background-color: #f0f0f0;
            border-color: #667eea;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
        }

        /* Footer Actions */
        .footer-actions {
            text-align: center;
            padding: 30px 0;
        }

        .footer-actions a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 30px;
            background: white;
            color: #dc3545;
            border: 2px solid #dc3545;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .footer-actions a:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-content {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .navbar-actions {
                width: 100%;
            }

            .navbar-actions a {
                width: 100%;
                justify-content: center;
            }

            .supplier-container {
                padding: 20px 10px;
            }

            .welcome-text {
                font-size: 22px;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-header-meta {
                margin-left: 0;
                margin-top: 10px;
            }

            .stock-section table {
                font-size: 13px;
            }

            .stock-section table th,
            .stock-section table td {
                padding: 12px 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <div class="supplier-navbar">
        <div class="navbar-content">
            <div class="navbar-brand">
                <div class="supplier-icon">
                    <i class="fas fa-building"></i>
                </div>
                <h2>MyShop Supplier</h2>
            </div>
            <div class="navbar-actions">
                <a href="{{ route('supplier.password.form') }}">
                    <i class="fas fa-lock"></i> Change Password
                </a>
                <a href="{{ route('supplier.logout') }}">
                    <i class="fas fa-sign-out-alt"></i>Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="supplier-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-text">
                <i class="fas fa-hand-wave" style="color: #667eea; margin-right: 10px;"></i>
                Welcome back, {{ $supplier->company_name }}!
            </div>
            <div class="welcome-subtitle">
                <i class="fas fa-calendar-alt" style="margin-right: 5px;"></i>
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>

        <!-- Profile Information Grid -->
        <div class="profile-grid">
            <!-- Company Name -->
            <div class="profile-card">
                <div class="card-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="card-label">Company Name</div>
                <div class="card-value">{{ $supplier->company_name }}</div>
            </div>

            <!-- Supplier ID -->
            <div class="profile-card">
                <div class="card-icon">
                    <i class="fas fa-id-card"></i>
                </div>
                <div class="card-label">Supplier ID</div>
                <div class="card-value">{{ $supplier->supplier_id }}</div>
            </div>

            <!-- Email -->
            <div class="profile-card">
                <div class="card-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="card-label">Email Address</div>
                <div class="card-value">
                    <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                </div>
            </div>

            <!-- Contact Number -->
            <div class="profile-card">
                <div class="card-icon">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="card-label">Contact Number</div>
                <div class="card-value">{{ $supplier->contact_no }}</div>
            </div>

            <!-- Country -->
            <div class="profile-card">
                <div class="card-icon">
                    <i class="fas fa-globe"></i>
                </div>
                <div class="card-label">Country</div>
                <div class="card-value">{{ $supplier->country }}</div>
            </div>

            <!-- Product Categories -->
            <div class="profile-card">
                <div class="card-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="card-label">Product Categories</div>
                <div class="card-value">{{ $supplier->product_categories }}</div>
            </div>
        </div>

        <!-- Additional Info Cards -->
        <div class="profile-grid">
            <!-- Address -->
            <div class="profile-card" style="grid-column: span 1;">
                <div class="card-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="card-label">Address</div>
                <div class="card-value">{{ $supplier->address }}</div>
            </div>

            <!-- Bank Details -->
            <div class="profile-card" style="grid-column: span 1;">
                <div class="card-icon">
                    <i class="fas fa-university"></i>
                </div>
                <div class="card-label">Bank Details</div>
                <div class="card-value">{{ $supplier->bank_details }}</div>
            </div>
        </div>

        <!-- Stock Records Section -->
        <div class="stock-section">
            <div class="section-header">
                <div class="section-header-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div>
                    <h3>Stock Records</h3>
                </div>
                @if($stocks->count() > 0)
                    <div class="section-header-meta">
                        Total Records: <strong>{{ $stocks->total() }}</strong>
                    </div>
                @endif
            </div>

            @if($stocks->count() > 0)
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Product Name</th>
                                <th style="width: 120px;">Quantity</th>
                                <th style="width: 130px;">Amount</th>
                                <th style="width: 150px;">Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $index => $stock)
                                <tr>
                                    <td>{{ ($stocks->currentPage() - 1) * $stocks->perPage() + $loop->iteration }}</td>
                                    <td><strong>{{ $stock->product }}</strong></td>
                                    <td>
                                        <span class="quantity-badge">{{ $stock->quantity }} units</span>
                                    </td>
                                    <td>
                                        <span class="price-text">{{ number_format($stock->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="date-text">{{ \Carbon\Carbon::parse($stock->date)->format('M d, Y') }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($stocks->hasPages())
                    <div class="pagination-wrapper">
                        {{ $stocks->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <div class="empty-text">
                        <strong>No Stock Records Yet</strong><br>
                        <small>Your stock records will appear here once they are added.</small>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
