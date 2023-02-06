@extends('components.main')
@include('components.navbar')
@section('container')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('components.sidebar')
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Dashboard</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                    <div>
                                        <div class="p-2 fs-3"><i class="bi bi-ticket-fill"></i></div>
                                        <div>Ticket All</div>
                                    </div>
                                    <div class="fs-1" id="ticketTotal"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                    <div class="">
                                        <div class="p-2 fs-3"><i class="bi bi-clock-fill"></i></div>
                                        <div>Ticket Proccess</div>
                                    </div>
                                    <div class="fs-1" id="ticketProccess"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                    <div class="">
                                        <div class="p-2 fs-3"><i class="bi bi-check-circle-fill"></i></div>
                                        <div>Ticket Approve</div>
                                    </div>
                                    <div class="fs-1" id="ticketApprove"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body d-flex justify-content-between">
                                    <div class="">
                                        <div class="p-2 fs-3"><i class="bi bi-x-circle-fill"></i></div>
                                        <div>Ticket Reject</div>
                                    </div>
                                    <div class="fs-1" id="ticketReject"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="" id="token" value="{{ Auth::user()->api_token }}">
                <div class="container-fluid p-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Summary Ticket By Regional in {{ date('M-Y') }}
                                </div>
                                <div class="card-body"><canvas id="myBarChart" width="100%" height="50"></canvas></div>
                                <div class="card-footer small text-muted" id="timeBar"></div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-pie me-1"></i>
                                    Summary Status Ticket Today
                                </div>
                                <div class="card-body"><canvas id="myPieChart" width="100%" height="50"></canvas></div>
                                <div class="card-footer small text-muted" id="timePie"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="{{ asset('action/chart/chart_demo.js') }}"></script>
    
@endsection

