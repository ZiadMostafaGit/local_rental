
<div class="container">
    <form method="POST" action="{{ route('conversations.create') }}">
        @csrf
        <div class="form-group">
            <label>العميل ID:</label>
            <input type="number" name="customer_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label>المالك ID:</label>
            <input type="number" name="lender_id" class="form-control" required>
        </div>
        <div class="form-group">
            <label>العنصر ID:</label>
            <input type="number" name="item_id" class="form-control" required>
        </div>
        <button type="submit" class="mt-2 btn btn-success">ابدأ محادثة</button>
    </form>
</div>

