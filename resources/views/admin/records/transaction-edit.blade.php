@php
    $isPurchase = $kind === 'purchase';
    $routePrefix = $isPurchase ? 'purchases' : 'sales';
    $priceKey = $isPurchase ? 'cost_price' : 'sale_price';
    $lines = old('items', $record->items->map(fn($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity, $priceKey => $item->$priceKey])->all());
    $productLookup = $products->keyBy('id');
@endphp
<x-admin.page :title="$isPurchase ? __('Edit purchase') : __('Edit sale')" :subtitle="__('Review the order details and update its products before saving.')" :eyebrow="$isPurchase ? 'Purchases' : 'Sales'" :back="route($routePrefix.'.index')">
    <x-slot:actions><a href="{{ route($routePrefix.'.show', $record->id) }}" class="btn record-button"><i class="bi bi-eye" aria-hidden="true"></i>{{ __('View details') }}</a></x-slot:actions>
    <form id="transaction-form" action="{{ route($routePrefix.'.update', $record->id) }}" method="POST" enctype="multipart/form-data" data-record-form data-transaction-editor="{{ $kind }}" data-next-index="{{ count($lines) }}" data-success-url="{{ route($routePrefix.'.show', $record->id) }}">
        @csrf @method('PUT')
        <script type="application/json" data-product-data>@json($products->values())</script>
        <div class="record-feedback record-feedback-error" data-record-feedback tabindex="-1" role="alert" hidden></div>
        <div class="record-layout">
            <div class="record-stack">
                <x-admin.panel :title="__('Order information')" :subtitle="__('Reference, date, and order status.')" icon="bi-receipt">
                    <div class="record-fields">
                        <div class="record-field"><label for="reference">{{ __('Reference') }}</label><input class="form-control" id="reference" value="{{ $record->reference }}" readonly></div>
                        <x-admin.field name="date" :label="__('Order date')" type="date" :value="\Carbon\Carbon::parse($record->date)->toDateString()" required />
                        <x-admin.field name="status" :label="__('Status')" required><select name="status" id="status" class="form-select" required>@foreach(['pending','completed','cancelled'] as $status)<option value="{{ $status }}" @selected(old('status', $record->status) === $status)>{{ __(ucfirst($status)) }}</option>@endforeach</select></x-admin.field>
                        @if($isPurchase)
                            <x-admin.field name="supplier_id" :label="__('Supplier')" required><select name="supplier_id" id="supplier_id" class="form-select" required><option value="">{{ __('Select supplier') }}</option>@foreach($suppliers as $supplier)<option value="{{ $supplier->id }}" @selected(old('supplier_id', $record->supplier_id) == $supplier->id)>{{ $supplier->name }}</option>@endforeach</select></x-admin.field>
                        @else
                            <x-admin.field name="customer_id" :label="__('Customer')"><select name="customer_id" id="customer_id" class="form-select"><option value="">{{ __('Walk-in customer') }}</option>@foreach($customers as $customer)<option value="{{ $customer->id }}" @selected(old('customer_id', $record->customer_id) == $customer->id)>{{ $customer->name }}</option>@endforeach</select></x-admin.field>
                        @endif
                    </div>
                </x-admin.panel>
                <x-admin.panel :title="__('Order items')" :subtitle="__('Add products and set the quantity and unit price for each line.')" icon="bi-box-seam">
                    <div class="record-add-product"><select class="form-select" aria-label="{{ __('Choose a product to add') }}" data-product-picker><option value="">{{ __('Choose a product…') }}</option>@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }} · {{ $product->code }}</option>@endforeach</select><button type="button" class="btn record-button" data-add-product><i class="bi bi-plus-lg" aria-hidden="true"></i>{{ __('Add item') }}</button></div>
                    <div class="record-table-wrap"><table class="record-table"><thead><tr><th>{{ __('Product') }}</th><th>{{ __('Quantity') }}</th><th>{{ $isPurchase ? __('Cost price') : __('Sale price') }}</th><th class="text-end">{{ __('Total') }}</th><th><span class="visually-hidden">{{ __('Remove') }}</span></th></tr></thead><tbody data-items-body>
                        @foreach($lines as $index => $line)
                            @php($lineProduct = $productLookup->get($line['product_id']))
                            <tr data-product-id="{{ $line['product_id'] }}"><td><strong>{{ $lineProduct?->name ?? __('Unavailable product') }}</strong><small>{{ $lineProduct?->code }}</small><input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $line['product_id'] }}"></td>
                                <td><input class="form-control" type="number" name="items[{{ $index }}][quantity]" value="{{ $line['quantity'] }}" min="1" max="1000000" required data-line-quantity aria-label="{{ __('Quantity for :product', ['product' => $lineProduct?->name]) }}"></td>
                                <td><input class="form-control" type="number" name="items[{{ $index }}][{{ $priceKey }}]" value="{{ $line[$priceKey] }}" min="0" max="999999.99" step="0.01" required data-line-price aria-label="{{ __('Unit price for :product', ['product' => $lineProduct?->name]) }}"></td>
                                <td class="number" data-line-total>{{ number_format($line['quantity'] * $line[$priceKey], 2) }}</td><td><button type="button" class="record-remove" data-remove-line aria-label="{{ __('Remove :product', ['product' => $lineProduct?->name]) }}"><i class="bi bi-trash3" aria-hidden="true"></i></button></td></tr>
                        @endforeach
                    </tbody></table><div class="record-empty" data-empty-items hidden><i class="bi bi-box d-block fs-3 mb-2" aria-hidden="true"></i>{{ __('No items yet. Choose a product above to get started.') }}</div></div>
                </x-admin.panel>
                @if($isPurchase)<x-admin.panel :title="__('Additional information')" :subtitle="__('Notes and supporting documents for this purchase.')" icon="bi-paperclip"><div class="record-fields"><x-admin.field name="note" :label="__('Note')" wide><textarea class="form-control" id="note" name="note" maxlength="5000" rows="3">{{ old('note', $record->note) }}</textarea></x-admin.field><x-admin.field name="attachments" :label="__('Replace attachment')" type="file" accept=".jpg,.jpeg,.png,.pdf" :hint="__('JPG, PNG, or PDF. Maximum size: 2 MB.')" wide /></div></x-admin.panel>@endif
            </div>
            <aside class="record-sidebar">
                <x-admin.panel :title="__('Order summary')" icon="bi-clipboard-data"><div class="record-totals"><div><span>{{ __('Product lines') }}</span><strong data-order-lines>{{ count($lines) }}</strong></div><div><span>{{ __('Total quantity') }}</span><strong data-order-units>0</strong></div><div><span>{{ __('Payment status') }}</span><x-admin.status :value="$record->payment_status ?? 'pending'" /></div><div class="record-grand-total"><span>{{ __('Order total') }}</span><strong data-order-total>{{ number_format($record->total_amount, 2) }}</strong></div></div></x-admin.panel>
                <div class="record-note"><strong><i class="bi bi-info-circle" aria-hidden="true"></i> {{ __('Before you save') }}</strong>{{ $isPurchase ? __('Completed purchases add stock. Stock already sold cannot be removed by reducing a purchase.') : __('Available stock is checked when you save. Payments must be refunded before reducing a total below the amount already paid.') }}</div>
            </aside>
        </div>
        <x-admin.form-actions :back="route($routePrefix.'.index')" :label="$isPurchase ? 'Save purchase' : 'Save sale'" />
    </form>
</x-admin.page>
