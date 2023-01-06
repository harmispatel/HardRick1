@extends('admin.layouts.admin-layout')
<style>
    .select2-container.select2-container--default.select2-container--open {
        z-index: 5000;
    }

</style>
@section('title', 'Patient')


@section('content')
    {{-- Modal for Add New Category & Edit Category --}}
    <div class="modal fade" id="patientModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="patientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="patientModalLabel">Add New Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="form" id="newPatientForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Enter Patient Name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Name Arabic <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name_ar" name="name_ar" class="form-control"
                                        placeholder="Enter Patient Name Arabic">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label"> Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" id="email" name="email" class="form-control"
                                        placeholder="Enter Email">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label"> Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Enter password">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label"> mobile <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="phone" name="phone" class="form-control"
                                        placeholder="Enter Mobile No">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">Image<span
                                            class="text-danger">*</span></label>
                                    <input type="file" id="image" oninput="pic.src=window.URL.createObjectURL(this.files[0])" name="image" class="form-control"
                                        placeholder="Enter Image">
                                </div>
                                <br>
                                <img src="" alt="" id="pic" width="70px">
                        </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Address<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="address" name="address" class="form-control"
                                        placeholder="Enter address">
                                </div>
                            </div>
                            <input type="hidden" name="latitude" id="latitude" class="form-control read-only"
                                        readonly>
                                        <input type="hidden" id="longitude" name="longitude" class="form-control read-only"
                                        readonly>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">location<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="location" id="location" required="" />
                                    <option value="basra">Basra</option>
                                    <option value="baghdad">Baghdad</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Drags Allergy<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" multiple="multiple" name="dragsAllergy[]"
                                        id="dragsAllergy">
                                        @foreach ($DragsAllergy as $Drags)
                                            <option value="{{ $Drags->id }}">{{ $Drags->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Chronic Diseases<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" multiple="multiple" name="ChronicDiseases[]"
                                        id="Chronicdiseases">
                                        @foreach ($Chronicdiseases as $Chronic)
                                            <option value="{{ $Chronic->id }}">{{ $Chronic->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Food Allergy<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control select2" multiple="multiple" name="FoodAllergy[]"
                                        id="Foodallergy">
                                        @foreach ($FoodAllergy as $Food)
                                            <option value="{{ $Food->id }}">{{ $Food->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Blood Type<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control"  name="blood_type_id"
                                        id="Bloodtype">
                                        @foreach ($BloodType as $Blood)
                                            <option value="{{ $Blood->id }}">{{ $Blood->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Birth Date<span class="text-danger">*</span></label>
                                    <input type="date" id="birth_date" name="birth_date" class="date form-control"
                                        placeholder="Enter Birth Date">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Status<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="status" id="status" required="" />
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a onclick="saveUpdateData('add','#patientModal','newPatientForm','#PatientTable','store-Patient')"
                        class="btn btn-success" id="saveupdatebtn">Save</a>
                </div>
            </div>
        </div>
    </div>


    <div class="pagetitle">
        <h1>Patient</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Patient</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a data-bs-toggle="modal" data-bs-target="#patientModal"
                    onclick="openaddmodel('Add New Patient','0','store-Patient','#newPatientForm','#patientModalLabel','#patientModal')"
                    class="btn btn-sm new-bloodtype btn-primary">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a onclick="deletedata('2','0','delete-Patient','#PatientTable')" class="btn btn-sm btn-danger me-1 ">
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
                            <h4>Patient List</h4>
                        </div>
                        <table class="table table-striped" id="PatientTable">
                            <thead>
                                <tr>
                                    <th><input class="form-check-input " type="checkbox" id="master"
                                            onclick="selectall()" value=""></th>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Status</th>
                                    <th>Image</th>
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
        var url = "{{ route('loadPatient-data') }}";
        // Load ChronicDiseases
        loadData('2', '#PatientTable', url);
        
       
    </script>

@endsection
