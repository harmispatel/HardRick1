@extends('admin.layouts.admin-layout')

@section('title', 'Hospital')

@section('content')
    {{-- Modal for Add New Category & Edit Category --}}
    <div class="modal fade" id="hospitalModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="hospitalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="hospitalModalLabel">Add New Hospital</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="form" id="newHospitalForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Enter Hospital Name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Mobile No <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        placeholder="Enter Mobile NO">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a onclick="saveUpdateData('add','#hospitalModal','newHospitalForm','#HospitalTable','store-Hospital')"
                        class="btn btn-success" id="saveupdatebtn">Save</a>
                </div>
            </div>
        </div>
    </div>


    <div class="pagetitle">
        <h1>Hospital</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Hospital</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
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
            </div>
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
                            <h4>Hospital List</h4>
                        </div>
                        <table class="table table-striped" id="HospitalTable">
                            <thead>
                                <tr>
                                    <th><input class="form-check-input " type="checkbox" id="master" value=""></th>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Actions</th>
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
        var url = "{{ route('loadHospital-data') }}";
        // Load Hospital
        loadData('11', '#HospitalTable', url);

        $('#master').on('click', function(e) {
        if ($(this).is(':checked', true)) {
            $(".sub_chk").prop('checked', true);
        } else {
            $(".sub_chk").prop('checked', false);
        }
    });
    </script>
@endsection
