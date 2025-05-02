@extends('admin_dashboard.layout.pages-layout')
@section('pagetitle', 'dashboard')
@section('content')

    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
            integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    </head>

    <body>
        <div class="xs-pd-20-10 pd-ltr-20">
            <div class="pb-20 title">
                <h2 class="mb-0 h3">dashboard Overview</h2>
            </div>
            <div class="pb-10 row">
                <div class="mb-20 col-xl-3 col-lg-3 col-md-6">
                    <div class="card-box height-100-p widget-style3">
                        <div class="flex-wrap d-flex">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark" id="total-items">..</div>
                                <div class="font-14 text-secondary weight-500">
                                    Total items
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#00eccf">
                                    <i class="icon-copy dw dw-calendar1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-20 col-xl-3 col-lg-3 col-md-6">
                    <div class="card-box height-100-p widget-style3">
                        <div class="flex-wrap d-flex">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark" id="total-categories">..</div>
                                <div class="font-14 text-secondary weight-500">
                                    Total Categories
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#ff5b5b">
                                    <span class="icon-copy ti-heart"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-20 col-xl-3 col-lg-3 col-md-6">
                    <div class="card-box height-100-p widget-style3">
                        <div class="flex-wrap d-flex">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark" id="total-lenders">..</div>
                                <div class="font-14 text-secondary weight-500">
                                    Total Lenders
                                </div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon">
                                    <i class="icon-copy fa fa-stethoscope" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-20 col-xl-3 col-lg-3 col-md-6">
                    <div class="card-box height-100-p widget-style3">
                        <div class="flex-wrap d-flex">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark" id="total-customers">..</div>
                                <div class="font-14 text-secondary weight-500">Total customers</div>
                            </div>
                            <div class="widget-icon">
                                <div class="icon" data-color="#09cc06">
                                    <i class="icon-copy fa fa-money" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pb-10 row">
                <div class="mb-20 col-md-8">
                    <div class="card-box height-100-p pd-20">
                        <div class="flex-wrap pb-0 d-flex justify-content-between align-items-center pb-md-3">
                            <div class="h5 mb-md-0">e-commerce Activities</div>
                            <div class="form-group mb-md-0">
                                <select id="adminRangeSelect" class="form-control form-control-sm selectpicker">
                                    <option value="last_week">Last Week</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="last_6_month">Last 6 Month</option>
                                    <option value="last_year">Last 1 year</option>
                                </select>

                            </div>
                        </div>
                        <div>
                            <canvas id="adminRentChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="mb-20 col-md-4">
                    <div class="card-box min-height-200px pd-20" data-bgcolor="#455a64">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-white h5 mb-md-0">Reviews by Item</div>
                            <div class="form-group mb-md-0">
                                <select id="reviewsItemSelect" class="form-control form-control-sm selectpicker">
                                    <option value="last_week">Last Week</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="last_6_month">Last 6 Month</option>
                                    <option value="last_year">Last 1 Year</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <div class="card-box height-100-p pd-20">
                        <canvas id="reviewsItemChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card-box min-height-200px pd-20" data-bgcolor="#265ed7">
                <div class="pb-20 text-white d-flex justify-content-between">
                    <div class="text-white icon h1">
                        <i class="fa fa-stethoscope" aria-hidden="true"></i>
                    </div>
                    <div class="text-right font-14">
                        <div><i class="icon-copy ion-arrow-down-c"></i> 3.69%</div>
                        <div class="font-12">Since last month</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div class="text-white">
                        <div class="font-14">Surgery</div>
                        <div class="font-24 weight-500">250</div>
                    </div>
                    <div class="max-width-150">
                        <div id="surgery-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
            <div class="mb-20 col-lg-4 col-md-6">
                <div class="card-box height-100-p pd-20 min-height-200px">
                    <div class="pb-10 d-flex justify-content-between">
                        <div class="mb-0 h5">Top products</div>
                        <div class="dropdown">
                            <a class="p-0 btn btn-link font-24 line-height-1 no-arrow dropdown-toggle"
                                data-color="#1b3133" href="#" role="button" data-toggle="dropdown">
                                <i class="dw dw-more"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                <a class="dropdown-item" href="#"><i class="dw dw-eye"></i> View</a>
                                <a class="dropdown-item" href="#"><i class="dw dw-edit2"></i> Edit</a>
                                <a class="dropdown-item" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    <div class="user-list">
                        <ul>

                            <li class="d-flex align-items-center justify-content-between">
                                <div class="pr-2 name-avatar d-flex align-items-center">
                                    <div class="flex-shrink-0 mr-2 avatar">
                                        <img src="#" class="border-radius-100 box-shadow" width="50"
                                            height="50" alt="">
                                    </div>
                                    <div class="txt">
                                        <span class="badge badge-pill badge-sm" data-bgcolor="#e7ebf5"
                                            data-color="#265ed7">4.9</span>
                                        <div class="font-14 weight-600">hello</div>
                                        <div class="font-12 weight-500" data-color="#b2b1b6">
                                            3000
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 cta">
                                    <a href="#" class="btn btn-sm btn-outline-primary">addproduct</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mb-20 col-lg-4 col-md-6">
                <div class="card-box height-100-p pd-20 min-height-200px">
                    <div class="d-flex justify-content-between">
                        <div class="mb-0 h5">Product Report</div>
                        <div class="dropdown">

                        </div>
                    </div>
                    <div>
                        <canvas id="diseases_chart"></canvas>
                    </div>
                </div>
            </div>
        </div>


        </div>
    @endsection
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $.ajax({
            url: '/gettotalitem',
            method: 'GET',
            success: function(response) {
                $('#total-items').text(response.total);
            },
            error: function() {
                console.log('Error fetching total items');
            }
        });

        $.ajax({
            url: '/gettotalcategory',
            method: 'GET',
            success: function(response) {
                $('#total-categories').text(response.total);
            },
            error: function() {
                console.log('Error fetching total category');
            }
        });

        $.ajax({
            url: '/gettotallender',
            method: 'GET',
            success: function(response) {
                $('#total-lenders').text(response.total);
            },
            error: function() {
                console.log('Error fetching total lenders');
            }
        });

        $.ajax({
            url: '/gettotalcustomer',
            method: 'GET',
            success: function(response) {
                $('#total-customers').text(parseFloat(response.total));
            },
            error: function() {
                console.log('Error fetching total customers');
            }
        });
    });
</script>


<script>
    let adminChart;

    document.addEventListener('DOMContentLoaded', function() {
        let adminChart;

        function loadAdminChart(range = 'last_week') {
            fetch(`/admin/rents/chart?range=${range}`)
                .then(res => res.json())
                .then(data => {
                    const labels = data.map(d => d.date);
                    const values = data.map(d => d.total);

                    // إذا كان هناك رسم بياني قديم، قم بتدميره أولاً
                    if (adminChart) {
                        adminChart.destroy();
                    }

                    // الحصول على العنصر canvas
                    const ctx = document.getElementById('adminRentChart').getContext('2d');

                    // إنشاء الرسم البياني الجديد
                    adminChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Rents',
                                data: values,
                                borderColor: 'green',
                                backgroundColor: 'rgba(40, 167, 69, 0.2)',
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Date'
                                    }
                                },
                                y: {
                                    title: {
                                        display: true,
                                        text: 'Rent Count'
                                    },
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error loading rents data:',
                    error)); // لحل أي مشاكل في جلب البيانات
        }

        // إضافة حدث تغيير الـ select
        const selectElement = document.getElementById('adminRangeSelect');
        if (selectElement) {
            selectElement.addEventListener('change', function() {
                loadAdminChart(this.value);
            });
        }

        // تحميل الرسم البياني عند تحميل الصفحة لأول مرة
        loadAdminChart();
    });

    // Initial chart load
    loadAdminChart();
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        let reviewsItemChart;

        function loadReviewsItemChart(range = 'last_week') {
            fetch(`/admin/reviews/item/chart?range=${range}`)
                .then(res => res.json())
                .then(data => {
                    const labels = data.map(d => d.name); // Names of the items
                    const values = data.map(d => d.avg_rating); // Average ratings

                    // If the chart exists, destroy it and reinitialize
                    if (reviewsItemChart) {
                        reviewsItemChart.destroy();
                    }

                    const ctx = document.getElementById('reviewsItemChart').getContext('2d');
                    reviewsItemChart = new Chart(ctx, {
                        type: 'pie', // Pie chart type
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Reviews Distribution by Item',
                                data: values,
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.6)',
                                    'rgba(54, 162, 235, 0.6)',
                                    'rgba(255, 206, 86, 0.6)',
                                    'rgba(75, 192, 192, 0.6)',
                                    'rgba(153, 102, 255, 0.6)',
                                    'rgba(255, 159, 64, 0.6)',
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (tooltipItem) {
                                            return `${tooltipItem.label}: ${tooltipItem.raw} rating`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error loading reviews by item data:', error));
        }

        const selectElement = document.getElementById('reviewsItemSelect');
        if (selectElement) {
            selectElement.addEventListener('change', function () {
                loadReviewsItemChart(this.value);
            });
        }

        // Initial chart load
        loadReviewsItemChart();
    });
</script>