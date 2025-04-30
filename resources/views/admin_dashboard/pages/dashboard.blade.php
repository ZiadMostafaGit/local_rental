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
        <div class="row pb-10">
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">75</div>
                            <div class="font-14 text-secondary weight-500">
                                Appointment
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
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">124,551</div>
                            <div class="font-14 text-secondary weight-500">
                                Total Patient
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
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">400+</div>
                            <div class="font-14 text-secondary weight-500">
                                Total Doctor
                            </div>
                        </div>
                        <div class="widget-icon">
                            <div class="icon">
                                <i
                                    class="icon-copy fa fa-stethoscope"
                                    aria-hidden="true"
                                ></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 mb-20">
                <div class="card-box height-100-p widget-style3">
                    <div class="d-flex flex-wrap">
                        <div class="widget-data">
                            <div class="weight-700 font-24 text-dark">$50,000</div>
                            <div class="font-14 text-secondary weight-500">Earning</div>
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

        <div class="row pb-10">
            <div class="col-md-8 mb-20">
                <div class="card-box height-100-p pd-20">
                    <div
                        class="d-flex flex-wrap justify-content-between align-items-center pb-0 pb-md-3"
                    >
                        <div class="h5 mb-md-0">Hospital Activities</div>
                        <div class="form-group mb-md-0">
                            <select class="form-control form-control-sm selectpicker">
                                <option value="">Last Week</option>
                                <option value="">Last Month</option>
                                <option value="">Last 6 Month</option>
                                <option value="">Last 1 year</option>
                            </select>
                        </div>
                    </div>
                    <div id="activities-chart"></div>
                </div>
            </div>
            <div class="col-md-4 mb-20">
                <div
                    class="card-box min-height-200px pd-20 mb-20"
                    data-bgcolor="#455a64"
                >
                    <div class="d-flex justify-content-between pb-20 text-white">
                        <div class="icon h1 text-white">
                            <i class="fa fa-calendar" aria-hidden="true"></i>
                            <!-- <i class="icon-copy fa fa-stethoscope" aria-hidden="true"></i> -->
                        </div>
                        <div class="font-14 text-right">
                            <div><i class="icon-copy ion-arrow-up-c"></i> 2.69%</div>
                            <div class="font-12">Since last month</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="text-white">
                            <div class="font-14">Appointment</div>
                            <div class="font-24 weight-500">1865</div>
                        </div>
                        <div class="max-width-150">
                            <div id="appointment-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="card-box min-height-200px pd-20" data-bgcolor="#265ed7">
                    <div class="d-flex justify-content-between pb-20 text-white">
                        <div class="icon h1 text-white">
                            <i class="fa fa-stethoscope" aria-hidden="true"></i>
                        </div>
                        <div class="font-14 text-right">
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
                                            <img src="#"
                                                class="border-radius-100 box-shadow" width="50" height="50"
                                                alt="">
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
                                        <a href="#"
                                            class="btn btn-sm btn-outline-primary">addproduct</a>
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
