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
                        <a href="#">View all Doctors</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_aqua">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>82</h3>
                            <h5>Patients</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Patients</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_yellow">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>82</h3>
                            <h5>Chronic Diseases</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Chronic Diseases</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="dash_card">
                    <div class="dash_card_info">
                        <div class="dash_card_icon icon_bg_red">
                            <i class="fa-solid fa-user-doctor"></i>
                        </div>
                        <div class="card_info_inr">
                            <h3>8</h3>
                            <h5>Blood Type</h5>
                        </div>
                    </div>
                    <div class="dash_card_title">
                        <a href="#">View all Blood Type</a>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
