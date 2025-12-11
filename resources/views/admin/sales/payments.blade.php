<div>
    <h5>Sale Invoice: {{ $sale->invoice_no ?? 'N/A' }}</h5>

    <form id="paymentForm">
        <input type="hidden" name="sale_id" value="{{ $sale->id }}">

        <div class="mb-2">
            <label>Amount</label>
            <input type="number" name="amount" class="form-control" required>
        </div>

        <div class="mb-2">
            <label>Payment Date</label>
            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <button class="btn btn-primary">Save</button>
    </form>
</div>
