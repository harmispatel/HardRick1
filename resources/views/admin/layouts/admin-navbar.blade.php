@php
    // UserDetails
    if(auth()->user())
    {
      
        $userID = (auth()->user()->id);
        $userName = auth()->user()->name;
        $userImage =  asset('public/profileImage/'.auth()->user()->profile_pic);
    }
    else
    {
        $userID = '';
        $userName = '';
        $userImage = asset('public/admin_images/demo_images/profiles/profile3.png');
    }

@endphp



<!-- ======= Header ======= -->
 <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block">Dleny</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->


    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="{{$userImage}}" alt="Profile" class="rounded-circle editp">
            <span class="d-none d-md-block dropdown-toggle ps-2">{{ $userName }}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ $userName }}</h6>
              <span>Administartor</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#" onclick="openaddmodel('Edit  Profile','{{$userID}}','profile','#userProfileForm','#userProfileModelLabel','#userProfileModel')">
                <i class="bi bi-person"></i>
                <span>Profile</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="{{ route('adminlogout') }}">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

{{-- Modal for Edit Profile --}}
    <div class="modal fade" id="userProfileModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="userProfileModelLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userProfileModelLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" class="form" id="userProfileForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label"> Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="user_name" name="name" class="form-control"
                                        placeholder="Enter Name">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">email<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="user_email" name="email" class="form-control"
                                        placeholder="Enter Email">
                                </div>
                            </div>
                             <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="name" class="form-label">Image</label>
                                    <input type="file" id="user_image" oninput="pic.src=window.URL.createObjectURL(this.files[0])" name="image" class="form-control"
                                        placeholder="Enter Image">
                                </div>
                                <br>
                                <img src="" alt="" id="user_pic" width="70px">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a onclick="saveUpdateData('edit','#userProfileModel','userProfileForm','#blankTable','updateProfile')"
                        class="btn btn-success" id="saveupdatebtn">Save</a>
                </div>
            </div>
        </div>
    </div>
