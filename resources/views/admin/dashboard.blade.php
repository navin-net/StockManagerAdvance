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
            <div class="col-md-6">
                <div class="section-title text-end">{{ __('messages.ip_address') }}: {{ auth()->user()->ip_address }}</div>
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
                        <h3>{{ $productCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <!-- added cash-coin icon -->
                        <h6 class="card-title text-muted"><i class="bi bi-cash-coin text-success me-2"></i>Total Revenue
                        </h6>
                        <h3>{{ '$' . $saleTotal }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <!-- added bag-check icon -->
                        <h6 class="card-title text-muted"><i class="bi bi-bag-check text-info me-2"></i>Products Sold</h6>
                        <h3>{{ $salesCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <!-- added graph-up icon -->
                        <h6 class="card-title text-muted"><i class="bi bi-graph-up text-warning me-2"></i>Purchases
                        </h6>
                        <h3>{{ $avg_sales }}</h3>
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
                                        <table class="table table-hover align-middle mb-0" id="billersTable">
                                            <thead class="bg-success text-white">
                                                <tr>
                                                    <th scope="col" class="py-3"><input type="checkbox" id="selectAll">
                                                    </th>
                                                    <th scope="col" class="py-3">Name</th>
                                                    <th scope="col" class="py-3">Group</th>
                                                    <th scope="col" class="py-3">Warehouse</th>
                                                    <th scope="col" class="py-3">Email</th>
                                                    <th scope="col" class="py-3">Phone</th>
                                                    <th scope="col" class="py-3">City</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Sales Tab -->
                                <div class="tab-pane fade" id="permissions" role="tabpanel">
                                    <!-- Actions -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">

                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive rounded-3 overflow-hidden">
                                        <table class="table table-hover align-middle mb-0" id="SalesTable">
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

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Purchases Tab -->
                                <div class="tab-pane fade" id="roles" role="tabpanel">
                                    <!-- Actions -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">

                                    </div>

                                    <!-- Table -->
                                    <div class="table-responsive rounded-3 overflow-hidden">
                                        <table class="table table-hover align-middle mb-0" id="purchasesTable">
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



    </div>


    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Biller Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    Loading...
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        const table = $('#billersTable').DataTable({
            processing: true,
            serverSide: true,
            searching: false, // hide search input box
            info: false, // hide "Showing X to X of X entries"
            paging: true,
            ajax: "{{ route('billers.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false,
                    orderable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    orderable: false
                },
                {
                    data: 'group_name',
                    name: 'group_name',
                    orderable: false
                },
                {
                    data: 'warehouse_name',
                    name: 'warehouse_name',
                    orderable: false
                },
                {
                    data: 'email',
                    name: 'email',
                    orderable: false
                },
                {
                    data: 'phone',
                    name: 'phone',
                    orderable: false
                },
                {
                    data: 'city',
                    name: 'city',
                    orderable: false
                }
            ],


            language: {
                paginate: {
                    previous: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>',
                    next: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>'
                },
                lengthMenu: '{{ __('messages.show') }} _MENU_{{ __('messages.entries') }}',
                search: '{{ __('messages.search') }}',
                emptyTable: "{{ __('messages.no_data_available') }}",
                processing: "{{ __('messages.processing') }}",
                zeroRecords: "{{ __('messages.no_matching_records') }}",
                infoEmpty: "{{ __('messages.showing_0_to_0_of_0_entries') }}",
                infoFiltered: "{{ __('messages.filtered_from_total_entries', ['total' => '_MAX_']) }}"
            }
        });
        $('#billersTable tbody').on('click', 'tr', function() {
            let data = table.row(this).data();

        $('#modalContent').html(`
            <p><b>Name:</b> ${data.name}</p>
            <p><b>Group:</b> ${data.group_name}</p>
            <p><b>Warehouse:</b> ${data.warehouse_name}</p>
            <p><b>Email:</b> ${data.email}</p>
            <p><b>Phone:</b> ${data.phone}</p>
            <p><b>City:</b> ${data.city}</p>
        `);

            $('#detailsModal').modal('show');
        });
    </script>
@endpush
