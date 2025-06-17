@extends('layouts.tenant')
@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('breadcrumb')
    {{-- <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li> --}}
@endsection

<style>
    .dashboard-card {
        position: relative;
        height: 100%;
        margin-bottom: 0;
        background-color: #032636;
        border-radius: 10px;
        z-index: 1;
    }

    .dashboard-card-layer {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .dashboard-card .card-inner {
        position: relative;
        align-items: center;
        justify-content: space-between;
        padding: 15px 20px;
        border-radius: 10px;
        height: 100%;
        color: #fff;
        gap: 20px;
    }

    .dashboard-card .card-inner .card-content {
        max-width: 70%;
        width: 100%;
    }

    .dashboard-card .card-inner .card-content h2 {
        color: #ffffff;
        text-transform: capitalize;
    }

    .dashboard-card .card-inner .card-content p {
        font-size: 14px;
        max-width: 80%;
        width: 100%;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .dashboard-card .card-inner .card-content .btn {
        padding: 5px 10px;
        border-radius: 4px;
        border: none;
        transition: all ease-in-out 500ms 0s;
    }

    @media screen and (max-width: 1440px) {
        .dashboard-card .card-inner .card-icon {
            padding: 20px;
        }
    }

    .dashboard-card .card-inner .card-icon {
        position: relative;
        background: #1C3B4A;
        border-radius: 50%;
        padding: 25px;
        z-index: 1;
    }

    .dashboard-card .card-inner .card-icon::before {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 80%;
        height: 80%;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        z-index: -1;
    }

    @media screen and (max-width: 1440px) {
        .dashboard-card .card-inner .card-icon svg {
            width: 70px;
            height: 70px;
        }
    }

    .dashboard-card .card-inner::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background-color: rgba(12, 175, 96, 0.4);
        border-radius: 80% 0 10px;
    }

    /* .dashboard-wrp {
        row-gap: 15px;
        height: 100%;
    }

    .dashboard-project-card {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        align-items: center;
        display: flex;
        background-color: rgba(255, 58, 110, 0.1);
        height: 100%;
        width: 100%;
    }

    .dashboard-project-card .card-inner {
        align-items: flex-start;
        padding: 15px;
        width: 100%;
    }

    .dashboard-project-card .card-content {
        max-width: 70%;
        width: 100%;
    }

    .dashboard-wrp .dashboard-project-card .theme-avtar {
        position: relative;
        border-radius: 4px;
    }

    .theme-avtar {
        width: 45px;
        height: 45px;
        border-radius: 17.3552px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .dashboard-wrp .dashboard-project-card .theme-avtar::before {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background-color: #ff3a6e;
        opacity: 30%;
        bottom: 0;
        right: -110%;
        z-index: -1;
    }

    .dashboard-wrp .dashboard-project-card .theme-avtar::after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #ff3a6e;
        opacity: 30%;
        top: 15%;
        right: -25px;
        z-index: -1;
    }

    .dashboard-project-card .card-content h3 {
        font-size: 18px;
        text-transform: capitalize;
        word-break: break-word;
    }*/

    .card {
        margin-bottom: 30px;
        border: 0px;
        border-radius: 0.625rem;
        box-shadow: 6px 11px 41px -28px #a99de7;
    }

    .gradient-1 {
        color: #fff !important;
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.25rem;
    }

    .gradient-1,
    .dropdown-mega-menu .ext-link.link-1 a,
    .morris-hover,
    .datamaps-hoverover {
        background-image: linear-gradient(230deg, #759bff, #843cf6);
    }

    .card .card-body {
        padding: 1.88rem 1.81rem;
    }

    .card-title {
        font-size: 16px;
        font-weight: 500;
        line-height: 18px;
    }

    .gradient-2,
    .dropdown-mega-menu .ext-link.link-3 a {
        background-image: linear-gradient(230deg, #fc5286, #fbaaa2);
    }

    .gradient-3,
    .dropdown-mega-menu .ext-link.link-2 a,
    .header-right .icons .user-img .activity {
        background-image: linear-gradient(230deg, #ffc480, #ff763b);
    }

    .gradient-4,
    .sidebar-right .nav-tabs .nav-item .nav-link.active::after,
    .sidebar-right .nav-tabs .nav-item .nav-link.active span i::before {
        background-image: linear-gradient(230deg, #0e4cfd, #6a8eff);
    }
</style>
@section('content')
    <div class="row  mb-4 ">

        <div class="row">
            <div class="col-lg-12">
                <div class="row ">
                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-1">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Total Properties</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">{{ $total_propeties }}</h2>
                                    </div>
                                </div>

                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-2">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Total Units</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white"> {{ $total_units }}</h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="card gradient-3">
                            <div class="card-body d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <h3 class="card-title text-white">Total Amount</h3>
                                    <div class="d-inline-block">
                                        <h2 class="text-white">{{ $total_amount }}</h2>
                                    </div>
                                </div>
                                <span class="float-right display-5 opacity-5"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>



                </div>
            </div>


        </div>

    </div>
@endsection
