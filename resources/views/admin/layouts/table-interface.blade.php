@php
    $tableText = fn ($key, $fallback) => \Illuminate\Support\Facades\Lang::has('messages.' . $key) ? __('messages.' . $key) : $fallback;
$tableInterfaceConfig = [
    'labels' => [
        'records' => __('messages.table_records'), 'subtitle' => __('messages.table_subtitle'),
        'search' => __('messages.table_search'), 'rows' => __('messages.table_rows'),
        'compact' => __('messages.table_compact'), 'comfortable' => __('messages.table_comfortable'),
        'refresh' => __('messages.table_refresh'), 'clearSearch' => __('messages.table_clear_search'),
        'empty' => __('messages.table_empty'), 'emptyHint' => __('messages.table_empty_hint'),
        'noResults' => __('messages.table_no_results'), 'searchHint' => __('messages.table_search_hint'),
        'selected' => __('messages.table_selected'), 'selectRow' => __('messages.table_select_row'),
        'selectAll' => __('messages.table_select_all'), 'scroll' => __('messages.table_scroll'),
    ],
    'tables' => [
        'productsTable' => [$tableText('products', 'Products'), 'box-seam'],
        'salesTable' => [$tableText('sales', 'Sales'), 'receipt'],
        'SalesTable' => [$tableText('sales', 'Sales'), 'receipt'],
        'purchasesTable' => [$tableText('purchases', 'Purchases'), 'bag-check'],
        'usersTable' => [$tableText('users', 'Users'), 'people'],
        'billersTable' => [$tableText('billers', 'Billers'), 'person-badge'],
        'customersTable' => [$tableText('customers', 'Customers'), 'people'],
        'suppliersTable' => [$tableText('suppliers', 'Suppliers'), 'truck'],
        'brandsTable' => [$tableText('brands', 'Brands'), 'tags'],
        'categoriesTable' => [$tableText('categories', 'Categories'), 'grid'],
        'subCategoriesTable' => [$tableText('sub_categories', 'Subcategories'), 'diagram-3'],
        'unitsTable' => [$tableText('units', 'Units'), 'rulers'],
        'groupsTable' => [$tableText('groups', 'Groups'), 'shield-check'],
        'warehousesTable' => [$tableText('warehouse', 'Warehouses'), 'building'],
        'qualitysTable' => [$tableText('quality', 'Quality grades'), 'patch-check'],
        'ms-table' => [$tableText('monthly_sales', 'Monthly sales'), 'calendar3'],
        'dsr-table' => [$tableText('daily_sales', 'Daily sales'), 'calendar-check'],
        'payment-table' => [$tableText('payments', 'Payments'), 'credit-card'],
    ],
];
@endphp
<script>
window.tableInterface = @json($tableInterfaceConfig);
</script>
<script src="{{ asset('backend/js/table-interface.js') }}?v={{ filemtime(public_path('backend/js/table-interface.js')) }}"></script>
