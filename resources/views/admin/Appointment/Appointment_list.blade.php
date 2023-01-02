@extends('admin.layouts.admin-layout')

@section('title', 'Appointment')

@section('content')

<div class="pagetitle">
    <h1>Appointment</h1>
    <div class="row">
        <div class="col-md-8">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Appointment</li>
                </ol>
            </nav>
        </div>
        {{-- <div class="col-md-4" style="text-align: right;">
            <a data-bs-toggle="modal" data-bs-target="#hospitalModal"
                onclick="openaddmodel('Add New Hospital','0','store-Hospital','#newHospitalForm','#hospitalModalLabel','#hospitalModal')"
                class="btn btn-sm new-bloodtype btn-primary">
                <i class="bi bi-plus-lg"></i>
            </a>
            <a onclick="deletedata('2','0','delete-Hospital','#HospitalTable')"
                class="btn btn-sm btn-danger me-1 ">
                <i class="bi bi-trash">
                </i>
            </a>
        </div> --}}
    </div>
</div><!-- End Page Title -->
<section class="section dashboard">
    <div class="row">

        {{-- Error Message Section --}}
        @if (session()->has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        {{-- Success Message Section --}}
        @if (session()->has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4>Appointment List</h4>
                    </div>
                    <table class="table table-striped" id="AppointmentTable">
                        <thead>
                            <tr>
                                {{-- <th><input class="form-check-input " type="checkbox" id="master" value=""></th> --}}
                                <th>Id</th>
                                <th>Doctor</th>
                                <th>User</th>
                                <th>Bookdate</th>
                                <th>Booktime</th>
                                <th>Bookday</th>
                                <th>Discount</th>
                                <th>Price</th>
                                <th>Location</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
{{-- Custom Script --}}
@section('page-js')
    <script type="text/javascript">
        var url = "{{ route('loadAppointment-data') }}";
        // Load ChronicDiseases
        loadData('10', '#AppointmentTable', url);

    </script>
@endsection 