@extends('admin.layouts.admin-layout')

@section('title', 'Drags Allergy')

@section('content')
    {{-- Modal for Add New Category & Edit Category --}}
    <div class="modal fade" id="dragsAllergyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="dragsAllergyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dragsAllergyModalLabel">Add New Dragsallergy</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="form" id="newDragsAllergyForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Enter Drags-allergy Name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Name Arabic <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name_ar" name="name_ar" class="form-control"
                                        placeholder="Enter Drags-allergy Name Arabic">
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a onclick="saveUpdateData('add','#dragsAllergyModal','newDragsAllergyForm','#DragsAllergyTable','store-Drags-allergy')"
                        class="btn btn-success" id="saveupdatebtn">Save</a>
                </div>
            </div>
        </div>
    </div>


    <div class="pagetitle">
        <h1>Drags Allergy</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Drags Allergy</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a data-bs-toggle="modal" data-bs-target="#dragsAllergyModal"
                    onclick="openaddmodel('Add New Drag-allergy','0','store-Drag-allergy','#newDragsAllergyForm','#dragsAllergyModalLabel','#dragsAllergyModal')"
                    class="btn btn-sm new-bloodtype btn-primary">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a onclick="deletedata('2','0','delete-Drags-allergy','#DragsAllergyTable')"
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
                            <h4>Drags Allergy List</h4>
                        </div>
                        <table class="table table-striped" id="DragsAllergyTable">
                            <thead>
                                <tr>
                                    <th><input class="form-check-input " type="checkbox" id="master" value=""></th>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Name Arabic</th>
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
        var url = "{{ route('loadDeagsallrgy-data') }}";
        // Load ChronicDiseases
        loadData('5', '#DragsAllergyTable', url);

        $('#master').on('click', function(e) {
        if ($(this).is(':checked', true)) {
            $(".sub_chk").prop('checked', true);
        } else {
            $(".sub_chk").prop('checked', false);
        }
    });
    </script>
@endsection
