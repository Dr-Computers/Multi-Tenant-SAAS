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

        .tenant-bg {
            margin: -25px;
            height: 230px;
            overflow: hidden;
            position: relative;
        }

        .tenant-bg .overlay-box {
            background: #117ba9;
            opacity: .9;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            text-align: center;
        }

        .tenant-bg .overlay-box .tenant-content {
            padding: 15px;
            margin-top: 30px;
        }

        .tenant-btm-box {
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
                data-url="{{ route('company.realestate.tenants.edit', $tenant->id) }}" data-size="xl" data-ajax-popup="true"
                data-original-title="{{ __('Edit') }}" class="edit_pro btn btn-dark btn-circle" data-toggle="tooltip"
                data-original-title="Edit"><i class="ti ti-pencil text-light" aria-hidden="true"></i></a>
            <div class="tenant-bg">
                <img src="{{ asset('storage/' . $tenant->avatar_url) }}" alt="tenant" width="100%">
                <div class="overlay-box">
                    <div class="tenant-content"> <a href="javascript:void(0)">
                            <img src="{{ asset('storage/' . $tenant->avatar_url) }}" alt="tenant"
                                class="thumb-lg rounded-circle mb-2 mx-auto">
                        </a>
                        <h4 class="text-white mb-2">{{ $tenant->name }}</h4>
                        <h5 class="text-white mb-2">{{ $tenant->email }}</h5>
                        <h5 class="text-white mb-2">{{ $tenant->mobile }}</h5>


                    </div>
                </div>
            </div>
            <div class="tenant-btm-box">
                <div class="row row-in">
                    <div class="col-md-6 row-in-br">
                        <div class="col-in row">
                            <h3 class="box-title">Total Properties</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-checkbox fs-2 text-success"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">0</div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6 row-in-br b-r-none">
                        <div class="col-in row">
                            <h3 class="box-title">Documents</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-file fs-2 text-danger"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-in">
                    <div class="col-md-6 row-in-br">
                        <div class="col-in row">
                            <h3 class="box-title">Total Invoices</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-files fs-2 text-warning"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 row-in-br b-r-none">
                        <div class="col-in row">
                            <h3 class="box-title">Pending Invoices</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-files fs-2 text-danger"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">15</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-in">

                    <div class="col-md-6 row-in-br b-r-none">
                        <div class="col-in row">
                            <h3 class="box-title">Total Amount</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-2"><i class="ti ti-cash fs-2 text-info"></i></div>
                                <div class="col-xs-10 text-right fs-3 fw-bold">0 </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 row-in-br">
                        <div class="col-in row">
                            <h3 class="box-title">Due Amount</h3>
                            <div class="d-flex justify-content-between">
                                <div class="col-xs-4"><i class="ti ti-cash fs-2 text-danger"></i></div>
                                <div class="col-xs-8 text-right fs-3 fw-bold">0</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="white-box" style="min-height:450px !important;max-height:650px !important;overflow:auto;">
            <div class="row my-4">
                <div class="col-md-12">
                    <label class="mb-2"><b>Personal Details</b></label>
                    <h6 class="mb-2">Address: {{ $tenant->personal ? $tenant->personal->address : '---' }}</h6>
                    <h6 class="mb-2">City: {{ $tenant->personal ? $tenant->personal->city : '---' }}</h6>
                    <h6 class="mb-2">State: {{ $tenant->personal ? $tenant->personal->state : '---' }}</h6>
                    <h6 class="mb-2">Postal code: {{ $tenant->personal ? $tenant->personal->postal_code : '---' }}
                    </h6>
                    <h6 class="mb-2">County: {{ $tenant->personal ? $tenant->personal->country : '---' }}</h6>
                </div>
            </div>
            <div class="row ">
                <hr>
                <div class="col-md-12 my-4 ">
                    <label class="mb-2"><b>Role</b></label>
                    <div class="d-flex gap-2">
                        <img src="https://avatar.iran.liara.run/tenantname?background=000&color=fff&uppercase=true&tenantname={{ $tenant->role_name }}"
                            alt="tenant" class="rounded-circle mb-2 w-10 h-10">
                        <label
                            class="text-capitalize fw-bold text-primary mt-2">{{ $tenant->role_name }}</label>
                    </div>
                </div>

            </div>
            <div class="row">
                <hr>
                <div class="col-md-12 my-4">
                    <label class="mb-2 text-primary"><b>Activity Details</b></label>
                    <h6 class="mb-2"><strong>Joined date : </strong>{!! dateTimeFormat($tenant->created_at) !!}</h6>
                    <h6 class="mb-2"><strong>Account create at : </strong>{!! dateTimeFormat($tenant->created_at) !!}</h6>
                    <h6 class="mb-2"><strong>Last login at : </strong>{!! dateTimeFormat($tenant->last_login_at) !!}</h6>
                    <h6 class="mb-2"><strong>Account status: </strong>
                        @if ($tenant->is_active)
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
