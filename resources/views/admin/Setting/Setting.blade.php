@extends('admin.layouts.admin-layout')

@section('title', 'Setting')

@section('content')



<div class="pagetitle">
    <h1>Setting</h1>
    <div class="row">
        <div class="col-md-8">
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Setting</li>
                </ol>
            </nav>
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
<div class="content-wrapper"> 
    {{-- <section class="content-header">
        <h1>Site settings <small>{{$siteSettings->id ? 'Edit' : 'Add' }} Site settings </small></h1>
        <ol class="breadcrumb">
          <li class="active"><i class="fa fa-dashboard"></i>{{$siteSettings->id ? 'Edit' : 'Add' }} Site settings </li>
        </ol>
    </section> --}}

    <section class="content"> 
     
        <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{$siteSettings->id ? 'Edit' : 'Add new' }}</h3>
                    {{-- <a href="{{URL::to('admin/dashboard')}}"><button class="btn btn-primary pull-right box-title" type="submit">Back</button></a> --}}
                </div>
          
                  <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-info">
                                <!-- form start -->                        
                                <div class="box-body">
                                    @if( count($errors) > 0)
                                        @foreach($errors->all() as $error)
                                            <div class="alert alert-danger" role="alert">
                                                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                                <span class="sr-only">Error:</span>
                                                {{ $error }}
                                            </div>
                                        @endforeach
                                    @endif
                                    @if(session()->has('message'))
                                        <div class="alert alert-success" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            {{ session()->get('message') }}
                                        </div>
                                    @endif
                                  
                                    @if(session()->has('errorMessage'))
                                        <div class="alert alert-danger" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            {{ session()->get('errorMessage') }}
                                        </div>
                                    @endif
                                   {{-- {{ dd($siteSettings->toArray()) }} --}}
                                  {{-- {{ dd($siteSettings->logo) }}  --}}
                                    <form method="post" action="{{route('settings.save')}}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input class="form-control" type = "hidden" name="id" value="{{$siteSettings->id}}">
                
                                        <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="api_key" class="control-label text-right">API Key<span style="color: red">*</span></label>
                                                <input class="form-control" type="text" name="api_key" value="{{$siteSettings->api_key}}" id="" title="enter site_settings api key" required="" />
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="api_key" class="control-label text-right">Doctor<span style="color: red">*</span></label>
                                                <select class="form-control" name="docId" required="" />
                                                @foreach($doctor as $doc)
                                                     <option value="{{$doc->id}}" @if($doc->id==$siteSettings->docId) selected  @endif>{{$doc->name}}</option>
                                                @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="api_key" class="control-label text-right">Company Name <span style="color: red">*</span></label>
                                                 <input class="form-control" type="text" name="company_name" value="{{$siteSettings->company_name}}" id="" title="enter company name" required="" />
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="api_key" class="control-label text-right">Company Email <span style="color: red">*</span></label>
                                                 <input class="form-control" type="text" name="company_email" value="{{$siteSettings->company_email}}" id="" title="enter company email" required="" />
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="api_key" class="control-label text-right">Company Phone<span style="color: red">*</span></label>
                                                 <input class="form-control" type="text" name="company_phone" value="{{$siteSettings->company_phone}}" id="" title="enter company phone" required="" />
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="api_key" class="control-label text-right">Company Address<span style="color: red">*</span></label>
                                                <input class="form-control" type="text" name="company_address" value="{{$siteSettings->company_address}}" id="" title="enter company address" required="" />
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="api_key" class="control-label text-right">Company website<span style="color: red">*</span></label>
                                                <input class="form-control" type="text" name="company_site" value="{{$siteSettings->company_site}}" id="" title="enter company site" required="" />
                                            </div>
                                        </div>
                                        
                                         <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="logo" class="control-label text-right">Image</label>
                                                <input type="file" name="image" class="form-control" >
                    
                                                <img src="{{asset('public/site_settings/'.$siteSettings->logo)}}" alt="tag">
                                            </div>
                                        </div>
                                         
                                        <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="about_us" class="control-label text-right">About Us</label>
                                                <textarea id='about_us' name='about_us'  >{{$siteSettings->about_us}}</textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="term_condition" class="control-label text-right">Term and Condition</label>
                                                <textarea id='term_condition' name='term_condition' >{{$siteSettings->term_condition}}</textarea>
                                            </div>
                                        </div>
                                        
                                            <div class="form-group">
                                            <div class="col-sm-12 col-md-4">
                                                <label for="privacy_policy" class="control-label text-right">Privacy policy</label>
                                                <textarea id='privacy_policy' name='privacy_policy' >{{$siteSettings->privacy_policy}}</textarea>
                                            </div>
                                        </div> 


                                        <div class="box-footer text-center col-md-4">
                                            <input class="btn btn-primary"  id="normal-btn" type="submit" name="submit" value="Save">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </section> --}}
</div>


@endsection 



@section('page-js')
<script type="text/javascript">

    // Initialize CKEditor
    CKEDITOR.replace( 'about_us',{
    width: "800px",
    height: "200px"
    
    });
    
    CKEDITOR.replace( 'privacy_policy',{
    width: "800px",
    height: "200px"
    
    });
    
    CKEDITOR.replace( 'term_condition',{
    width: "800px",
    height: "200px"
    
    });
    </script>
@endsection