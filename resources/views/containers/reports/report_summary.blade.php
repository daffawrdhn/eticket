@extends('components.main')
@include('components.navbar')
@section('container')

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('components.sidebar')
        </div>
        <div id="layoutSidenav_content" class="report_sumary">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Reports</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Report Data Summary</li>
                    </ol>

                    <div class="container-fluid p-4 rounded shadow position-relative">
                        <div class="container-fluid" id="overflow">    
                            <table class="table" id="summaryTable">
                                <thead>
                                    <tr>
                                    <th scope="col">no</th>
                                    <th scope="col">Regional</th>
                                    <th scope="col">ticket approval</th>
                                    <th scope="col">ticket reject</th>
                                    <th scope="col">total ticket</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider" id="table-summary">
                                    
                                </tbody>
                                </table>

                        </div>    
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- <script src="{{ asset('action/sub_feature/sub_feature.js') }}"></script> --}}
@endsection


