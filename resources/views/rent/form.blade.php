<div class="container">
    <h2>Rent an Item</h2>
    <form id="rent-form" method="POST" action="{{ route('rent.store') }}">
        @csrf
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="delivery_address">Delivery Address</label>
            <input type="text" id="delivery_address" name="delivery_address" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
    </form>
</div>
