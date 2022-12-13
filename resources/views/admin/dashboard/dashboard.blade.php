@extends('admin.layouts.admin-layout')

@section('title', 'D A S H B O A R D')

@section('content')

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            @if (session()->has('errors'))
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('errors')  }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_green">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>14</h3>
                            <h5>Doctors</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Doctors <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_aqua">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>82</h3>
                            <h5>Patients</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Patients <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_yellow">
                            <i class="fa-solid fa-square-virus"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>82</h3>
                            <h5>Chronic Diseases</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Chronic Diseases <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_red">
                            <i class="fa-solid fa-droplet"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>8</h3>
                            <h5>Blood Type</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Blood Type <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_green">
                            <i class="fa-solid fa-pills"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>14</h3>
                            <h5>Drags allergy</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Drags allergy <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_aqua">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>4</h3>
                            <h5>Food allergy</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Food allergy <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_yellow">
                            <i class="fa-solid fa-hospital-user"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>4</h3>
                            <h5>specialist</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all specialist <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_red">
                            <i class="fa-solid fa-money-bill"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>4</h3>
                            <h5>Subcription plan</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Subcription plan <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_green">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>4</h3>
                            <h5>Setting</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Setting <i class="fa-solid fa-right-long ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
