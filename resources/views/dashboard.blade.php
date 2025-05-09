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

            <!-- Widgets Row -->
            <div class="pb-20 row">
                <div class="mb-20 col-xl-3 col-lg-3 col-md-6">
                    <div class="card-box height-100-p widget-style3">
                        <div class="flex-wrap d-flex">
                            <div class="widget-data">
                                <div class="weight-700 font-24 text-dark" id="total-items">..</div>
                                <div class="font-14 text-secondary weight-500">Total items</div>
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
                                <div class="font-14 text-secondary weight-500">Total Categories</div>
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
                                <div class="font-14 text-secondary weight-500">Total Lenders</div>
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

            <!-- E-commerce Activities -->
            <div class="pb-10 row">
                <div class="mb-20 col-md-8">
                    <div class="card-box height-100-p pd-20">
                        <div class="flex-wrap d-flex justify-content-between align-items-center pb-3 gap-2">
                            <div class="h5 mb-0">e-commerce Activities</div>

                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <select id="adminRangeSelect" class="form-control form-control-sm">
                                    <option value="last_week">Last Week</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="last_6_month">Last 6 Month</option>
                                    <option value="last_year">Last 1 year</option>
                                </select>
                                <input type="date" id="fromDate" class="form-control form-control-sm"
                                    placeholder="From">
                                <input type="date" id="toDate" class="form-control form-control-sm" placeholder="To">
                                <button id="filterBtn" class="btn btn-sm btn-success">Apply</button>
                            </div>
                        </div>
                        <div>
                            <canvas id="adminRentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customers and Lenders Report Section -->
            <div class="row">
                <div class="mb-20 col-lg-6 col-md-12">
                    <div class="card-box height-100-p pd-20 min-height-200px">
                        <div class="d-flex justify-content-between">
                            <div class="mb-0 h5">Customers Report</div>
                        </div>
                        <div>
                            <canvas id="diseases_chart" style="width: 100%; height: 300px;"></canvas>
                        </div>
                    </div>
                </div>

                <div class="mb-20 col-lg-6 col-md-12">
                    <div class="card-box height-100-p pd-20 min-height-200px">
                        <div class="d-flex justify-content-between">
                            <div class="mb-0 h5">Lenders Report</div>
                        </div>
                        <div>
                            <canvas id="lenders_chart" style="width: 100%; height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

@endsection

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

        function loadAdminChart(range = null, from = null, to = null) {
            let url = '/admin/rents/chart?';
            if (range) {
                url += `range=${range}`;
            } else if (from && to) {
                url += `from=${from}&to=${to}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const labels = data.map(d => d.date);
                    const values = data.map(d => d.total);

                    if (adminChart) adminChart.destroy();

                    const ctx = document.getElementById('adminRentChart').getContext('2d');
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
                .catch(error => console.error('Error loading rents data:', error));
        }

        document.getElementById('adminRangeSelect')?.addEventListener('change', function() {
            loadAdminChart(this.value);
        });

        document.getElementById('filterBtn')?.addEventListener('click', function() {
            const from = document.getElementById('fromDate').value;
            const to = document.getElementById('toDate').value;
            if (from && to) {
                loadAdminChart(null, from, to);
            }
        });

        loadAdminChart('last_week'); // Default
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        fetch("{{ route('customer.chart.score') }}") // غيّر المسار لو مختلف
            .then(response => response.json())
            .then(chartData => {
                const ctx = document.getElementById('diseases_chart').getContext('2d');

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                            label: 'Customer Scores',
                            data: chartData.scores,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)'
                            ],
                            borderColor: 'white',
                            borderWidth: 2
                        }]
                    }
                });
            })
            .catch(error => {
                console.error("Error loading chart data:", error);
            });
    });
</script>

<script>
    $(document).ready(function() {
        $.ajax({
            url: '/top-rented-items',
            method: 'GET',
            success: function(items) {
                let html = '';
                items.forEach(item => {
                    // تحديد لون الـ badge بناءً على عدد الإيجارات
                    let badgeColor = 'badge-secondary'; // الافتراضي
                    if (item.rents_count >= 5) {
                        badgeColor =
                            'badge-success'; // إذا عدد الإيجارات أكبر من أو يساوي 5
                    } else if (item.rents_count >= 3) {
                        badgeColor =
                            'badge-warning'; // إذا عدد الإيجارات أكبر من أو يساوي 3
                    }

                    html += `
                        <li class="d-flex align-items-center justify-content-between">
                            <div class="pr-2 name-avatar d-flex align-items-center">

                                <div class="txt">
                                    <span class="badge ${badgeColor} badge-pill badge-sm">${item.rents_count}</span>
                                    <div class="font-14 weight-600">${item.title}</div>
                                    <div class="font-12 weight-500" data-color="#b2b1b6">
                                        ${item.price} EGP
                                    </div>
                                </div>
                            </div>
                        </li>
                    `;
                });
                $('.user-list ul').html(html);
            },
            error: function() {
                console.error('Failed to load top rented items.');
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetch('/lender-chart-score')
            .then(res => res.json())
            .then(data => {
                const ctx = document.getElementById('lenders_chart').getContext('2d');

                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Lender Scores',
                            data: data.scores,
                           backgroundColor: [
                                'rgba(255, 99, 132, 0.7)',
                                'rgba(54, 162, 235, 0.7)',
                                'rgba(255, 206, 86, 0.7)',
                                'rgba(75, 192, 192, 0.7)',
                                'rgba(153, 102, 255, 0.7)',
                                'rgba(255, 159, 64, 0.7)'
                            ],
                            borderColor: 'white',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Score'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Lender Name'
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error loading lender chart:', error));
    });
</script>
