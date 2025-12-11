@extends('layouts.master')
@section('title', __('messages.view_sale'))

@section('content')

    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="pagetitle mb-4">
            <h1 class="display-6 fw-bold mb-3">Sale Receipt</h1>
            <nav>
                <ol class="breadcrumb rounded-3 p-2">
                    <li class="breadcrumb-item">
                        <a href="#" class="text-primary text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="#" class="text-primary text-decoration-none">Sales</a>
                    </li>
                    <li class="breadcrumb-item active">View Receipt</li>
                </ol>
            </nav>
        </div>


        <section class="section">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            {{-- <h5 class="card-title mb-3">{{ __('messages.sale_details') }}</h5> --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label ">{{ __('messages.ref') }}</label>
                                    <div>{{ $sale->id ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label ">{{ __('messages.date') }}</label>
                                    <div>
                                        <span>
                                            <span>{{ isset($sale->date) ? \Carbon\Carbon::parse($sale->date)->format('d/m/Y') : 'N/A' }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label ">{{ __('messages.status') }}</label>
                                    <div>
                                        <span
                                            class="badge {{ isset($sale->status) && $sale->status == 'completed' ? 'bg-success' : (isset($sale->status) && $sale->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ isset($sale->status) ? ucfirst($sale->status) : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label ">{{ __('messages.customer') }}</label>
                                    <div>{{ $sale->customer->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-12">
                                    {{-- <h5 class="mb-3">Items</h5> --}}
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Sale Price</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Product Name 1</td>
                                                <td>2</td>
                                                <td>$25.00</td>
                                                <td>$50.00</td>
                                            </tr>
                                            <tr>
                                                <td>Product Name 2</td>
                                                <td>1</td>
                                                <td>$75.00</td>
                                                <td>$75.00</td>
                                            </tr>
                                            <tr>
                                                <td>Product Name 3</td>
                                                <td>3</td>
                                                <td>$15.00</td>
                                                <td>$45.00</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total Amount</th>
                                                <th>$170.00</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <hr>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 d-flex align-items-center">
                                    <a href="{{ route('sales.index') }}"
                                        class="btn btn-secondary btn-sm rounded-2 me-2">{{ __('messages.back') }}</a>
                                    <a href="{{ route('sales.edit', $sale->id) }}"
                                        class="btn btn-primary btn-sm rounded-2 me-2">{{ __('messages.edit') }}</a>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <button onclick="window.print()"
                                        class="btn btn-success btn-sm rounded-2">{{ __('messages.print') }}</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="mb-3">
                        <div class="card">
                            <div class="card-body">
                                <!-- added shopping-bag icon -->
                                <h6 class="card-title text-muted"><i class="bi bi-box-seam text-primary me-2"></i>Total
                                    Products</h6>

                                <h3>248</h3>
                                <hr>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            {{-- <i class="bi bi-arrow-up-short text-success me-2 fs-4"></i> --}}
                                            <div>
                                                {{-- <h5 class="mb-0">+5.4%</h5> --}}
                                                <small class="text-muted">Items: 2</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="d-flex align-items-center">
                                            {{-- <i class="bi bi-arrow-up-short text-success me-2 fs-4"></i> --}}
                                            <div>
                                                {{-- <h5 class="mb-0">+5.4%</h5> --}}
                                                <small class="text-muted">Status: <span
                                                        class="badge {{ $sale->status == 'completed' ? 'bg-success' : ($sale->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                                        {{ ucfirst($sale->status) }}</span></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="card">
                            <div class="card-body">
                                <!-- added bag-check icon -->
                                <h6 class="card-title text-muted"><i class="bi bi-bag-check text-info me-2"></i>Summary</h6>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted">Subtotal</small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small>${{ number_format($sale->total_amount, 2) }}</small>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted">Tax (0%)</small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small>$0.00</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Discount (0%)</small>
                                    </div>
                                    <div class="col-6 text-end">
                                        <small>$0.00</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="card">
                            <div class="card-body">
                                <!-- added cash-coin icon -->
                                <h6 class="card-title text-muted"><i class="bi bi-cash-coin text-success me-2"></i>Quick Actions</h6>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-sm">Download PDF</button>
                                    <button class="btn btn-secondary btn-sm">Share</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3">{{ __('messages.sale_details') }}</h5>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label ">{{ __('messages.total_amount') }}</label>
                                    <div>{{ number_format($sale->total_amount, 2) }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label ">{{ __('messages.status') }}</label>
                                    <div>
                                        <span
                                            class="badge
                                        {{ $sale->status == 'completed' ? 'bg-success' : ($sale->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ ucfirst($sale->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label ">{{ __('messages.date') }}</label>
                                    <div>{{ $sale->date ?? '-' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label ">{{ __('messages.customer') }}</label>
                                    <div>{{ $sale->customer->name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h5>{{ __('messages.items') }}</h5>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('messages.product') }}</th>
                                            <th>{{ __('messages.quantity') }}</th>
                                            <th>{{ __('messages.sale_price') }}</th>
                                            <th>{{ __('messages.subtotal') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sale->items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->product->name ?? '-' }}
                                                    @if ($item->product && $item->product->stock_quantity <= 0)
                                                        <span
                                                            class="badge bg-danger ms-2">{{ __('messages.out_of_stock') }}</span>
                                                    @elseif($item->product && $item->quantity > $item->product->stock_quantity)
                                                        <span
                                                            class="badge bg-warning ms-2">{{ __('messages.exceeds_stock') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->sale_price, 2) }}</td>
                                                <td>{{ number_format($item->quantity * $item->sale_price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-end">{{ __('messages.total_amount') }}</th>
                                            <th>{{ number_format($sale->total_amount, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mt-3 row">
                                <div class="col-md-6 d-flex align-items-center">
                                    <a href="{{ route('sales.index') }}"
                                        class="btn btn-secondary btn-sm rounded-2 me-2">{{ __('messages.back') }}</a>
                                    <a href="{{ route('sales.edit', $sale->id) }}"
                                        class="btn btn-primary btn-sm rounded-2 me-2">{{ __('messages.edit') }}</a>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <button onclick="window.print()"
                                        class="btn btn-success btn-sm rounded-2">{{ __('messages.print') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </section>
    </div>
@endsection
