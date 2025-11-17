@extends('layouts.master')
@section('title', __('messages.dashboard'))

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="section-title text-start">{{ __('messages.dashboard') }}</div>
                <h1 class="display-6 fw-bold mb-3 text-start">{{ __('messages.stock_management_system') }}</h1>
                <p class="text-muted mb-4 text-start">{{ __('messages.dashboard_welcome') }}</p>
            </div>
        </div>

        {{-- Success Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"
                    aria-label="{{ __('messages.close') }}"></button>
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <!-- added shopping-bag icon -->
                        <h6 class="card-title text-muted"><i class="bi bi-box-seam text-primary me-2"></i>Total Products
                        </h6>
                        <h3>248</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <!-- added cash-coin icon -->
                        <h6 class="card-title text-muted"><i class="bi bi-cash-coin text-success me-2"></i>Total Revenue
                        </h6>
                        <h3>$12,450</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <!-- added bag-check icon -->
                        <h6 class="card-title text-muted"><i class="bi bi-bag-check text-info me-2"></i>Units Sold</h6>
                        <h3>1,850</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <!-- added graph-up icon -->
                        <h6 class="card-title text-muted"><i class="bi bi-graph-up text-warning me-2"></i>Avg. Order Value
                        </h6>
                        <h3>$67.30</h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="h-75 d-inline-block"></div> --}}
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4">

                            <!-- Centered Tabs -->
                            <div class="d-flex justify-content-center mb-4">
                                <ul class="nav nav-tabs card-header-tabs border-bottom" role="tablist">
                                    <li class="nav-item">
                                        <button class="nav-link active" id="groups-tab" data-bs-toggle="tab"
                                            data-bs-target="#groups" type="button" role="tab">
                                            {{ __('messages.billers') }}
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="permissions-tab" data-bs-toggle="tab"
                                            data-bs-target="#permissions" type="button" role="tab">
                                            {{ __('messages.sales') }}
                                        </button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles"
                                            type="button" role="tab">
                                            {{ __('messages.purchases') }}
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- Tab Content -->
                            <div class="tab-content">
                                <!-- Billers Tab -->
                                <div class="tab-pane fade show active" id="groups" role="tabpanel">
                                    <!-- Actions -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        {{-- <h5 class="mb-0">{{ __('messages.billers_list') }}</h5>
                                        <button class="btn btn-primary rounded-pill">
                                            <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add_biller') }}
                                        </button> --}}
                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive rounded-3 overflow-hidden">
                                        <table class="table table-hover align-middle mb-0" id="groupsTable">
                                            <thead class="">
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('messages.name') }}</th>
                                                    <th>{{ __('messages.company') }}</th>
                                                    <th>{{ __('messages.email') }}</th>
                                                    <th>{{ __('messages.phone') }}</th>
                                                    <th>{{ __('messages.status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal"
                                                    onclick="showDetails('John Doe', 'ABC Corporation', 'john@abc.com', '+1 234 567 8900', 'Active', '123 Main St, New York, NY 10001', 'B001')">
                                                    <td>1</td>
                                                    <td>John Doe</td>
                                                    <td>ABC Corporation</td>
                                                    <td>john@abc.com</td>
                                                    <td>+1 234 567 8900</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal"
                                                    onclick="showDetails('Jane Smith', 'XYZ Ltd', 'jane@xyz.com', '+1 234 567 8901', 'Active', '456 Oak Ave, Los Angeles, CA 90001', 'B002')">
                                                    <td>2</td>
                                                    <td>Jane Smith</td>
                                                    <td>XYZ Ltd</td>
                                                    <td>jane@xyz.com</td>
                                                    <td>+1 234 567 8901</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal"
                                                    onclick="showDetails('Mike Johnson', 'Global Traders', 'mike@global.com', '+1 234 567 8902', 'Pending', '789 Pine Rd, Chicago, IL 60601', 'B003')">
                                                    <td>3</td>
                                                    <td>Mike Johnson</td>
                                                    <td>Global Traders</td>
                                                    <td>mike@global.com</td>
                                                    <td>+1 234 567 8902</td>
                                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal"
                                                    onclick="showDetails('Sarah Wilson', 'Tech Solutions', 'sarah@tech.com', '+1 234 567 8903', 'Active', '321 Elm St, Houston, TX 77001', 'B004')">
                                                    <td>4</td>
                                                    <td>Sarah Wilson</td>
                                                    <td>Tech Solutions</td>
                                                    <td>sarah@tech.com</td>
                                                    <td>+1 234 567 8903</td>
                                                    <td><span class="badge bg-success">Active</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#detailModal"
                                                    onclick="showDetails('Robert Brown', 'Prime Suppliers', 'robert@prime.com', '+1 234 567 8904', 'Inactive', '654 Maple Dr, Phoenix, AZ 85001', 'B005')">
                                                    <td>5</td>
                                                    <td>Robert Brown</td>
                                                    <td>Prime Suppliers</td>
                                                    <td>robert@prime.com</td>
                                                    <td>+1 234 567 8904</td>
                                                    <td><span class="badge bg-danger">Inactive</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Sales Tab -->
                                <div class="tab-pane fade" id="permissions" role="tabpanel">
                                    <!-- Actions -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        {{-- <h5 class="mb-0">{{ __('messages.sales_list') }}</h5>
                                        <button class="btn btn-success rounded-pill">
                                            <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add_sale') }}
                                        </button> --}}
                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive rounded-3 overflow-hidden">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-success text-white">
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('messages.invoice') }}</th>
                                                    <th>{{ __('messages.customer') }}</th>
                                                    <th>{{ __('messages.date') }}</th>
                                                    <th>{{ __('messages.amount') }}</th>
                                                    <th>{{ __('messages.status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#salesModal"
                                                    onclick="showSalesDetails('INV-001', 'John Customer', '2024-01-15', '$1,250.00', 'Paid', 'Product A, Product B')">
                                                    <td>1</td>
                                                    <td>INV-001</td>
                                                    <td>John Customer</td>
                                                    <td>2024-01-15</td>
                                                    <td>$1,250.00</td>
                                                    <td><span class="badge bg-success">Paid</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#salesModal"
                                                    onclick="showSalesDetails('INV-002', 'Mary Client', '2024-01-16', '$890.50', 'Pending', 'Service Package')">
                                                    <td>2</td>
                                                    <td>INV-002</td>
                                                    <td>Mary Client</td>
                                                    <td>2024-01-16</td>
                                                    <td>$890.50</td>
                                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#salesModal"
                                                    onclick="showSalesDetails('INV-003', 'Tom Business', '2024-01-17', '$2,100.00', 'Paid', 'Product C, Product D, Product E')">
                                                    <td>3</td>
                                                    <td>INV-003</td>
                                                    <td>Tom Business</td>
                                                    <td>2024-01-17</td>
                                                    <td>$2,100.00</td>
                                                    <td><span class="badge bg-success">Paid</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#salesModal"
                                                    onclick="showSalesDetails('INV-004', 'Lisa Corp', '2024-01-18', '$650.00', 'Overdue', 'Product F')">
                                                    <td>4</td>
                                                    <td>INV-004</td>
                                                    <td>Lisa Corp</td>
                                                    <td>2024-01-18</td>
                                                    <td>$650.00</td>
                                                    <td><span class="badge bg-danger">Overdue</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#salesModal"
                                                    onclick="showSalesDetails('INV-005', 'David Store', '2024-01-19', '$1,500.00', 'Paid', 'Bulk Order Items')">
                                                    <td>5</td>
                                                    <td>INV-005</td>
                                                    <td>David Store</td>
                                                    <td>2024-01-19</td>
                                                    <td>$1,500.00</td>
                                                    <td><span class="badge bg-success">Paid</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Purchases Tab -->
                                <div class="tab-pane fade" id="roles" role="tabpanel">
                                    <!-- Actions -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        {{-- <h5 class="mb-0">{{ __('messages.purchases_list') }}</h5>
                                        <button class="btn btn-info text-white rounded-pill">
                                            <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add_purchase') }}
                                        </button> --}}
                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive rounded-3 overflow-hidden">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="">
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('messages.po_number') }}</th>
                                                    <th>{{ __('messages.supplier') }}</th>
                                                    <th>{{ __('messages.date') }}</th>
                                                    <th>{{ __('messages.amount') }}</th>
                                                    <th>{{ __('messages.status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#purchaseModal"
                                                    onclick="showPurchaseDetails('PO-001', 'Supplier A', '2024-01-10', '$5,200.00', 'Received', 'Raw Materials')">
                                                    <td>1</td>
                                                    <td>PO-001</td>
                                                    <td>Supplier A</td>
                                                    <td>2024-01-10</td>
                                                    <td>$5,200.00</td>
                                                    <td><span class="badge bg-success">Received</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#purchaseModal"
                                                    onclick="showPurchaseDetails('PO-002', 'Supplier B', '2024-01-12', '$3,800.00', 'Pending', 'Office Supplies')">
                                                    <td>2</td>
                                                    <td>PO-002</td>
                                                    <td>Supplier B</td>
                                                    <td>2024-01-12</td>
                                                    <td>$3,800.00</td>
                                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#purchaseModal"
                                                    onclick="showPurchaseDetails('PO-003', 'Supplier C', '2024-01-14', '$7,500.00', 'Received', 'Equipment')">
                                                    <td>3</td>
                                                    <td>PO-003</td>
                                                    <td>Supplier C</td>
                                                    <td>2024-01-14</td>
                                                    <td>$7,500.00</td>
                                                    <td><span class="badge bg-success">Received</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#purchaseModal"
                                                    onclick="showPurchaseDetails('PO-004', 'Supplier D', '2024-01-16', '$2,100.00', 'Cancelled', 'Packaging Materials')">
                                                    <td>4</td>
                                                    <td>PO-004</td>
                                                    <td>Supplier D</td>
                                                    <td>2024-01-16</td>
                                                    <td>$2,100.00</td>
                                                    <td><span class="badge bg-danger">Cancelled</span></td>
                                                </tr>
                                                <tr style="cursor: pointer;" data-bs-toggle="modal"
                                                    data-bs-target="#purchaseModal"
                                                    onclick="showPurchaseDetails('PO-005', 'Supplier E', '2024-01-18', '$4,300.00', 'In Transit', 'Inventory Stock')">
                                                    <td>5</td>
                                                    <td>PO-005</td>
                                                    <td>Supplier E</td>
                                                    <td>2024-01-18</td>
                                                    <td>$4,300.00</td>
                                                    <td><span class="badge bg-primary">In Transit</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Biller Detail Modal -->
        <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h5 class="modal-title" id="detailModalLabel">{{ __('messages.biller_details') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.biller_id') }}:</label>
                                <p id="modal-id" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.name') }}:</label>
                                <p id="modal-name" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.company') }}:</label>
                                <p id="modal-company" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.email') }}:</label>
                                <p id="modal-email" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.phone') }}:</label>
                                <p id="modal-phone" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.status') }}:</label>
                                <p id="modal-status">-</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('messages.address') }}:</label>
                                <p id="modal-address" class="text-muted">-</p>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="modal-footer"> --}}
                        <div>
                        {{-- <button type="button" class="btn btn-outline-primary rounded-pill">
                            <i class="bi bi-pencil me-2"></i>{{ __('messages.edit') }}
                        </button>
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                            {{ __('messages.close') }}
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Detail Modal -->
        <div class="modal fade" id="salesModal" tabindex="-1" aria-labelledby="salesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="salesModalLabel">{{ __('messages.sale_details') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.invoice') }}:</label>
                                <p id="sales-invoice" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.customer') }}:</label>
                                <p id="sales-customer" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.date') }}:</label>
                                <p id="sales-date" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.amount') }}:</label>
                                <p id="sales-amount" class="text-success fw-bold fs-5">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.status') }}:</label>
                                <p id="sales-status">-</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('messages.items') }}:</label>
                                <p id="sales-items" class="text-muted">-</p>
                            </div>
                        </div>
                    </div>
                    <div >
                        {{-- <button type="button" class="btn btn-outline-success rounded-pill">
                            <i class="bi bi-printer me-2"></i>{{ __('messages.print') }}
                        </button>
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                            {{ __('messages.close') }}
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Purchase Detail Modal -->
        <div class="modal fade" id="purchaseModal" tabindex="-1" aria-labelledby="purchaseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h5 class="modal-title" id="purchaseModalLabel">{{ __('messages.purchase_details') }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.po_number') }}:</label>
                                <p id="purchase-po" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.supplier') }}:</label>
                                <p id="purchase-supplier" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.date') }}:</label>
                                <p id="purchase-date" class="text-muted">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.amount') }}:</label>
                                <p id="purchase-amount" class="text-info fw-bold fs-5">-</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">{{ __('messages.status') }}:</label>
                                <p id="purchase-status">-</p>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-bold">{{ __('messages.description') }}:</label>
                                <p id="purchase-description" class="text-muted">-</p>
                            </div>
                        </div>
                    </div>
                    <div >
                        {{-- <button type="button" class="btn btn-outline-info text-dark rounded-pill">
                            <i class="bi bi-pencil me-2"></i>{{ __('messages.edit') }}
                        </button>
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                            {{ __('messages.close') }}
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
