@php  
 $weekName =  Config::get('commonVariable.weekName'); 
@endphp


@extends('admin.layouts.admin-layout')
<style>
    .select2-container.select2-container--default.select2-container--open {
        z-index: 5000;
    }
</style>
@section('title', 'Doctor')

@section('content')
    {{-- Modal for Add New Category & Edit Category --}}
    <div class="modal fade" id="doctorModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="doctorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="doctorModalLabel">Add New Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="form" id="newDoctorForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Enter Doctor Name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Name Arabic <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name_ar" name="name_ar" class="form-control"
                                        placeholder="Enter Doctor Name Arabic">
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
                                            <label for="name" class="form-label">Picture clinic<span
                                                    class="text-danger">*</span></label>
                                            <input type="file" id="clinicImage" oninput="pic1.src=window.URL.createObjectURL(this.files[0])" name="clinicImage" class="form-control"
                                                placeholder="Enter Image">
                                        </div>
                                        <br>
                                        <img src="" alt="" id="pic1" width="70px">
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
                                <label for="email" class="form-label"> mobile <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="phone" name="phone" class="form-control"
                                    placeholder="Enter Mobile No">
                            </div>
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
                                    <label for="email" class="form-label">Specialists<span
                                            class="text-danger">*</span></label>
                                        
                                    <select class="form-control select2" multiple="multiple" name="specialists[]"
                                        id="specialists">
                                        @foreach ($specialists as $key =>  $value)
                                        
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">Year of Experience<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="yearofexp" name="yearofexp" class="form-control"
                                        placeholder="Enter your year of experience">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">certificate<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="certificates" name="certificates" class="form-control"
                                        placeholder="Enter your certificate">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Days<span
                                            class="text-danger">*</span></label>
                                    <select class="form-control" name="status_data" id="status_data" onchange="statusData(this.value)" placeholder="choose days" required="" />
                                    <option value="">Select Days</option>
                                    <option value="1">Week</option>
                                    <option value="2">Month</option>
                                    <option value="3">Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">Form<span
                                        class="text-danger">*</span></label>
                                        <input type="text" id="datefrom" name="datefrom" class="setDate1 form-control"
                                        placeholder="form_date" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="name" class="form-label">To<span
                                            class="text-danger">*</span></label>
                                            <input type="text" id="dateto" name="dateto" class="form-control"
                                            placeholder="to_date" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Avarage hour<span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" name="averagehour" id="averagehour" required="" />
                                            <option value="10">10 min</option>
                                            <option value="15">15 min</option>
                                            <option value="20">20 min</option>
                                            <option value="30">30 min</option>
                                            <option value="45">45 min</option>
                                            <option value="60">1  hour</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Discount<span
                                                class="text-danger">*</span></label>
                                                <input type="text" id="discounts" name="discounts" class="form-control"
                                                placeholder="Enter  discounts">
                                            </div>
                                        </div>
                            
                                        <div class="col-md-6 mb-2">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Discount Rule<span class="text-danger">*</span></label>
                                                <textarea  id="discount_rule" name="discount_rule" class="date form-control"
                                                    placeholder="Enter discount_rule"></textarea>
                                            </div>
                                        </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="email" class="form-label">Information<span class="text-danger">*</span></label>
                                    <textarea  id="info" name="info" class="date form-control"
                                        placeholder="Enter Information"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">Instagram link<span
                                        class="text-danger">*</span></label>
                                        <input type="text" id="insta_link" name="insta_link" class="form-control"
                                        placeholder="Enter your Instagram link">
                                    </div>
                                </div>
                                 <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label for="name" class="form-label">Fackbook link<span
                                            class="text-danger">*</span></label>
                                            <input type="text" id="facebook_link" name="facebook_link" class="form-control"
                                            placeholder="Enter your facebook_link">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Price of ticket<span
                                                class="text-danger">*</span></label>
                                                <input type="text" id="price_of_ticket" name="price_of_ticket" class="form-control"
                                                placeholder="Enter your price_of_ticket">
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
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <h3>Work Hours</h3>
                                    <a href="javascript:void(0)" class="btn btn-success" onclick="addTimeSlot()">Add new</a>
                                </div>
                            </div>
                            <input class="form-control" type = "hidden" name="timeid[]" value="0">
                           <div id="workHours" class="row">
                             <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">Choose Day</label>
                                        <select name="dayName[]" class="select2 form-control">
                                            @foreach($weekName as $key=>$dayNames)
                                            <option value="{{$key}}">{{$dayNames}}</option>
                                            @endforeach
                                        </select>
                                </div>
                             </div>
                             <div class="col-md-2 mb-2">
                                 <div class="form-group">
                                    <label for="name" class="control-label text-right">Start Time </label>
                                      <input class="form-control timepicker" type="text" name="startTime[]" value="" title="Choose Time" />
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <div class="form-group">
                                      <label for="name" class="control-label text-right">End Time </label>
                                      <input class="form-control timepicker" type="text" name="endTime[]" value="" title="Choose Time" />
                                     </div>
                                 </div>
                                </div>
                                 <div id="addTime">
                        
                                 </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a onclick="saveUpdateData('add','#doctorModal','newDoctorForm','#DoctorTable','store-Doctor')"
                        class="btn btn-success" id="saveupdatebtn">Save</a>
                </div>
            </div>
        </div>
    </div>


    <div class="pagetitle">
        <h1>Doctor</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Doctor</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4" style="text-align: right;">
                <a data-bs-toggle="modal" data-bs-target="#doctorModal"
                    onclick="openaddmodel('Add New Doctor','0','store-Doctor','#newDoctorForm','#doctorModalLabel','#doctorModal')"
                    class="btn btn-sm new-bloodtype btn-primary">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a onclick="deletedata('2','0','delete-Doctor','#DoctorTable')" class="btn btn-sm btn-danger me-1 ">
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
                            <h4>Doctor List</h4>
                        </div>
                        <table class="table table-striped" id="DoctorTable">
                            <thead>
                                <tr>
                                    <th><input class="form-check-input " type="checkbox" id="master"
                                            onclick="selectall()" value=""></th>
                                    <th>Id</th>
                                    <th>Name</th>
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
<!-- Updated JavaScript url -->
    <script type="text/javascript">
        var url = "{{ route('loadDoctor-data') }}";
        // Load ChronicDiseases
        loadData('1', '#DoctorTable', url);
        var timeId = 0;

       function addTimeSlot(){
         timeId++;
        
         var timeDiv = '<div id="time'+timeId+'">'+
                            '<div class="row">'+
                                '<div class="col-md-6 mb-2">'+
                                    '<div class="form-group">'+
                                        '<label for="name" class="form-label">Choose Day</label>'+
                                            '<select name="dayName[]" class="select2 form-control">'+
                                                '@foreach($weekName as $key=>$dayNames)'+
                                                    '<option value="{{$key}}">{{$dayNames}}</option>'+
                                                '@endforeach'+
                                            '</select>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-2 mb-2">'+
                                    '<div class="form-group">'+
                                        '<label for="name" class="control-label text-right">Start Time </label>'+
                                        '<input class="form-control timepicker" type="text" name="startTime[]" value="" title="Choose Time" />'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-2 mb-2">'+
                                    '<div class="form-group">'+
                                        '<label for="name" class="control-label text-right">End Time </label>'+
                                        '<input class="form-control timepicker" type="text" name="endTime[]" value="" title="Choose Time" />'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-md-2 mb-2">'+
                                    '<div class="form-group">'+
                                        '<a href="javascript:void(0)" class="btn btn-danger" style="margin-top: 25px;" onclick="removeTime('+timeId+')">Remove</a>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                        '</div>';
                jQuery("#addTime").append(timeDiv);
                select2Refresh();
                timePickerRefresh();
       }
      
    </script>
@endsection
