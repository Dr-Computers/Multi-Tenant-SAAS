@push('header')
    <style>
        .thumb-lg {
            height: 88px;
            width: 88px;
        }

        .white-box {
            background: #fff;
            padding: 25px;
            margin-bottom: 15px;
        }

        a.edit_pro {
            position: absolute;
            right: 0px;
            top: 0px;
            z-index: 5;
            border-radius: 0px;
        }

        .btn-circle {
            width: 30px;
            height: 30px;
            padding: 6px 0;
            border-radius: 15px;
            text-align: center;
            font-size: 12px;
            line-height: 1.428571429;
        }

        .user-bg {
            margin: -25px;
            height: 230px;
            overflow: hidden;
            position: relative;
        }

        .user-bg .overlay-box {
            background: #117ba9;
            opacity: .9;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            text-align: center;
        }

        .user-bg .overlay-box .user-content {
            padding: 15px;
            margin-top: 30px;
        }

        .user-btm-box {
            padding: 40px 0 10px;
            clear: both;
            overflow: hidden;
        }

        .row-in-br {
            border-right: 1px solid rgba(120, 130, 140, .13);
        }

        .col-in {
            padding: 20px;
        }

        .white-box .box-title {
            margin: 0 0 12px;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 14px;
        }

        .row-in-br {
            border-right: 1px solid rgba(120, 130, 140, .13);
        }
    </style>
@endpush
<div class="row">
    <div class="col-md-6">
        <div class="white-box position-relative">
            <a data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                data-url="{{ route('company.hrms.users.edit', $user->id) }}" data-size="xl" data-ajax-popup="true"
                data-original-title="{{ __('Edit') }}" class="edit_pro btn btn-dark btn-circle" data-toggle="tooltip"
                data-original-title="Edit"><i class="ti ti-pencil text-light" aria-hidden="true"></i></a>
            <div class="user-bg">
                <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="user" width="100%">
                <div class="overlay-box">
                    <div class="user-content"> <a href="javascript:void(0)">
                            <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="user"
                                class="thumb-lg rounded-circle mb-2 mx-auto">
                        </a>
                        <h4 class="text-white mb-2">{{ $user->name }}</h4>
                        <h5 class="text-white mb-2">{{ $user->email }}</h5>
                        <h5 class="text-white mb-2">{{ $user->mobile }}</h5>


                    </div>
                </div>
            </div>
            <div class="user-btm-box">
                <div class="row row-in">
                   {{--  <div class="col-md-6 row-in-br">
                        <div class="col-in row">
                            <h3 class="box-title">Total Working Days</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-checkbox fs-2 text-success"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">0</div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6 row-in-br b-r-none">
                        <div class="col-in row">
                            <h3 class="box-title">Hours Logged</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-2"><i class="ti ti-clock fs-2 text-info"></i></div>
                                <div class="col-xs-10 text-right fs-3 fw-bold">0 hrs </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="row row-in">
                    {{-- <div class="col-md-6 row-in-br">
                        <div class="col-in row">
                            <h3 class="box-title">Leaves Taken</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-logout fs-2 text-warning"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 row-in-br b-r-none">
                        <div class="col-in row">
                            <h3 class="box-title">Remaining Leaves</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-logout fs-2 text-danger"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">15</div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="row row-in">
                    <div class="col-md-6 row-in-br">
                        <div class="col-in row">
                            <h3 class="box-title">Documents</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-file fs-2 text-danger"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">0</div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6 row-in-br b-r-none">
                        <div class="col-in row">
                            <div class="d-flex justify-content-between">
                                <h3 class="box-title">View Task on Calendar</h3>
                                <div class="col-xs-4"><i class="ti ti-calendar fs-2 text-success"></i></div>
                            </div>
                        </div>
                    </div> --}}
                </div>

            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="white-box" style="min-height:450px !important;max-height:650px !important;overflow:auto;">
            <div class="row my-4">
                <div class="col-md-12">
                    <label class="mb-2"><b>Personal Details</b></label>
                    <h6 class="mb-2">Address: {{ $user->personal ? $user->personal->address  : '---' }}</h6>
                    <h6 class="mb-2">City: {{ $user->personal ?  $user->personal->city : '---'  }}</h6>
                    <h6 class="mb-2">State: {{ $user->personal ?  $user->personal->state : '---'   }}</h6>
                    <h6 class="mb-2">Postal code: {{ $user->personal ?  $user->personal->postal_code : '---'  }}</h6>
                    <h6 class="mb-2">County: {{ $user->personal ?  $user->personal->country : '---'  }}</h6>
                </div>
            </div>
            <div class="row ">
                <hr>
                <div class="col-md-12 my-4 ">
                    <label class="mb-2"><b>Role</b></label>
                    <div class="d-flex gap-2">
                        <img src="https://avatar.iran.liara.run/username?background=000&color=fff&uppercase=true&username={{ $user->role_name }}"
                            alt="user" class="rounded-circle mb-2 w-10 h-10">
                        <label
                            class="text-capitalize fw-bold text-primary mt-2">{{ $user->role_name }}</label>
                    </div>
                </div>

            </div>
            <div class="row">
                <hr>
                <div class="col-md-12 my-4">
                    <label class="mb-2 text-primary"><b>Activity Details</b></label>
                    <h6 class="mb-2"><strong>Joined date : </strong>{!! dateTimeFormat($user->created_at) !!}</h6>
                    <h6 class="mb-2"><strong>Account create at : </strong>{!! dateTimeFormat($user->created_at) !!}</h6>
                    <h6 class="mb-2"><strong>Last login at : </strong>{!! dateTimeFormat($user->last_login_at) !!}</h6>
                    <h6 class="mb-2"><strong>Account status: </strong>
                        @if ($user->is_active)
                            <span class="badge bg-success">Enabled</span>
                        @else
                            <span class="badge bg-danger">Disabled</span>
                        @endif
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>
