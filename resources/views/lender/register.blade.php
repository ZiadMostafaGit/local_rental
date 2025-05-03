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
    <h2 class="page-title">تسجيل حساب جديد</h2>
    <form action="{{ route('lender.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="first_name">الاسم الأول</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label for="last_name">الاسم الأخير</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">كلمة المرور</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="gender">الجنس</label>
            <select class="form-control" id="gender" name="gender">
                <option value="M">ذكر</option>
                <option value="F">أنثى</option>
            </select>
        </div>
        <div class="form-group">
            <label for="state">المحافظة</label>
            <input type="text" class="form-control" id="state" name="state" required>
        </div>
        <div class="form-group">
            <label for="city">المدينة</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        <div class="form-group">
            <label for="street">الشارع</label>
            <input type="text" class="form-control" id="street" name="street" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">تسجيل</button>
    </form>
</div>
