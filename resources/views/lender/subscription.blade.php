<style>
    /* تحسين تنسيق الحاوية */
.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 40px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* تحسين تنسيق العنوان */
.page-title {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 30px;
}

/* تنسيق الحقول */
.form-group label {
    font-size: 1rem;
    color: #555;
    margin-bottom: 8px;
    display: block;
}

/* تنسيق الحقول النصية */
.form-control {
    padding: 12px 15px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 100%;
    margin-bottom: 15px;
}

/* تنسيق الزر */
.btn {
    padding: 12px 20px;
    font-size: 1.1rem;
    font-weight: bold;
    border-radius: 5px;
    text-align: center;
    background-color: #007bff;
    color: white;
    border: none;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

/* تحسين الهوامش */
.mb-4 {
    margin-bottom: 20px;
}

/* تحسين الحقول الخاصة بالاختيارات */
select.form-control {
    padding: 12px 15px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 100%;
    margin-bottom: 15px;
}
</style>
<div class="container">
    <h2 class="page-title">اختر مدة الاشتراك</h2>
    <form action="{{ route('lender.subscribe') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="months">مدة الاشتراك (بالأشهر)</label>
            <select class="form-control" id="months" name="months" required>
                <option value="1">شهر واحد</option>
                <option value="3">3 أشهر</option>
                <option value="6">6 أشهر</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary w-100">اشتراك</button>
    </form>
</div>
