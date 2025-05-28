@extends('layouts.company')
@section('page-title')
    {{ __('Properties') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('company.realestate.properties.index') }}">{{ __('Properties') }}</a></li>
    <li class="breadcrumb-item">Property Information : {{ $property->name }} </li>
@endsection
@section('action-btn')
    <a href="{{ route('company.realestate.properties.index') }}" class="btn btn-sm btn-primary">
        <i class="ti ti-arrow-left"></i> {{ __('Back') }}
    </a>
@endsection
@section('content')
    @can('unit details')
        <div class="row">
            <div class="col-lg-7">
                <section class="mt-3">
                    <div class="col-lg-12 text-start">
                        <div class=" mt-4">
                            <div class="row">
                                <!-- Room Details -->
                                <div class="col-md-12 mb-3">
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <h2 class="fs-4 font-bold mb-3">{{ $property->name }}</h2>

                                                    <div class="my-3">
                                                        <div class="mb-2"> Category :
                                                            <span class="fw-bold">
                                                                {{ $property->category->name }}</span>
                                                        </div>

                                                        <div class="mb-2">
                                                            Flat No/Villa No : <span class="badge bg-dark">
                                                                {{ $property->building_no ?? '--' }}</span>
                                                        </div>

                                                        <div class="mt-2">
                                                            Created At : <span class="text-dark">
                                                                {{ date('d M, Y h:i a'), strtotime($property->created_at) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="my-2">
                                                        <span class="font-bold">Description</span>
                                                        <p class="my-2">{!! $property->unique_info !!}</p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">

                                                    <h3 class="text-success font-bold mb-4"><u>Reserved Parking</u>
                                                    </h3>
                                                    <p class="mb-3"><strong>Covered Parking:</strong>
                                                        {{ $property->covered_parking }}
                                                    </p>
                                                    <p><strong>Open Parking:</strong> {{ $property->open_parking }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <h5 class="text-success font-bold mb-4"><u>Floor Details</u>
                                                    </h5>
                                                    <p class="mb-3"><strong>Carpet Area:</strong>
                                                        {{ $property->carpet_area }} Sq.ft</p>
                                                    <p class="mb-3"><strong>Super Built-up Area:</strong>
                                                        {{ $property->square }} Sq.ft</p>


                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <h5 class="text-success font-bold mb-4"><u>Amenities : </u></h5>
                                                <div class="row">
                                                    @foreach ($property->features ?? [] as $key => $feature)
                                                        <div class="col-lg-3 mb-3">
                                                            <div class="d-flex  flex-warp items-center">
                                                                <img src="{{ $feature->image_url }}" class="">
                                                                <span class="ms-2 text-sm">{{ $feature->name }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="card shadow">
                                        <div class="card-body">
                                            <div class="row">
                                                <h5 class="text-success font-bold mb-4"><u>Furnishing :</u> <span
                                                        class="text-dark text-capitalize">{{ str_replace('-', ' ', $property->furnishing_status) }}</span>
                                                </h5>
                                                <div class="row">
                                                    @foreach ($property->furnishing ?? [] as $key => $furnish)
                                                        <div class="col-lg-3 mb-3">
                                                            <div class="d-flex  flex-warp items-center">
                                                                <img src="{{ $furnish->image_url }}" class="">
                                                                <span class="ms-2 text-sm">{{ $furnish->name }}</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
            <div class="col-lg-5">
                <div class="col-xl-12 col-lg-12 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-success font-bold mb-4"><u>Owner Information : </u></h5>
                            <h4 class="text-black fs-20 font-bold mb-3"Name : {{ $property->owner->name }}</h4>
                                <div class="mt-2 text-bold mb-3">Email : <a class="text-black"
                                        href="mailto:{{ $property->owner->email }}">{{ $property->owner->email }}</a>
                                    <br>
                                </div>
                                <span class="mt-2 text-bold">Mobile :
                                    <a class="text-black"
                                        href="tel:{{ $property->owner->phone }}">{{ $property->owner->phone }}</a></span>
                        </div>
                    </div>
                </div>

                <!-- General Location Details -->
                <div class="col-md-12 mb-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="text-muted font-bold mb-3">Location Details</h5>
                            <p class="mb-2"><strong>Location:</strong><br> {{ $property->location }}</p>
                            <p class="mb-2"><strong>City:</strong> {{ $property->city }}</p>
                            <p class="mb-2"><strong>Locality:</strong> {{ $property->locality }}</p>
                            <p class="mb-2"><strong>Sublocality:</strong> {{ $property->sub_locality }}</p>
                            <p class="mb-2"><strong>Landmark:</strong> {{ $property->landmark }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <p><strong>Current Property Status:</strong>
                                <span
                                    class="badge text-capitalize bg-{{ $property->moderation_status == 'approved' ? 'success' : 'warning' }}">
                                    {{ $property->moderation_status == 'approved' ? $property->status : $property->moderation_status }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <p><strong>Property Images:</strong>
                            @if (is_array($property->images))
                                @foreach ($property->images ?? [] as $image)
                                    <div>
                                        <img src="{{ asset('images/' . $image) }}" class="w-100 rounded-3 object-cover" />
                                    </div>
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <p><strong>Property Documents:</strong>
                            @if (is_array($property->images))
                                @foreach ($property->images ?? [] as $image)
                                    <div>
                                        <img src="{{ asset('images/' . $image) }}" class="w-100 rounded-3 object-cover" />
                                    </div>
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-3">
                <h4 class="text-black fs-20 font-bold mb-3">Unit Information</h4>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h2 class="fs-4 font-bold mb-3">{{ $unit->name }}</h2>
                                <div class="my-3">
                                    <div class="mb-2"> Reg No :
                                        <span class="fw-bold">
                                            {{ $unit->registration_no }}</span>
                                    </div>

                                    <div class="mb-2">
                                        Rent Type: <span class="fw-bold">
                                            {{ $unit->rent_type ?? '--' }}</span>
                                    </div>

                                    <div class="mt-2">
                                        Created At : <span class="text-dark">
                                            {{ date('d M, Y h:i a'), strtotime($property->created_at) }}</span>
                                    </div>
                                </div>
                                <div class="my-2">
                                    <span class="font-bold">Description</span>
                                    <p class="my-2">{!! $unit->notes !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">

                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-success font-bold mb-4"><u>Floor Details</u>
                                </h5>
                                <p class="mb-3"><strong>No:of Bed Rooms:</strong>
                                    {{ $unit->bed_rooms }}</p>
                                <p class="mb-3"><strong>No:of Bath Rooms:</strong>
                                    {{ $unit->bath_rooms }}</p>
                                <p class="mb-3"><strong>No:of Balconies:</strong>
                                    {{ $unit->balconies }}</p>
                                <p class="mb-3"><strong>No:of Kitchen:</strong>
                                    {{ $unit->kitchen }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="text-success font-bold mb-4"><u>Price Details</u>
                                </h5>
                                <p class="mb-3"><strong>Deposite type:</strong>
                                    {{ $unit->deposite_type }}</p>
                                <p class="mb-3"><strong>Deposite amount:</strong>
                                    {{ $unit->deposite_amount }}</p>
                                <p class="mb-3"><strong>Late fee type:</strong>
                                    {{ $unit->late_fee_type }}</p>
                                <p class="mb-3"><strong>Late fee amount:</strong>
                                    {{ $unit->late_fee_amount }}</p>
                                <p class="mb-3"><strong>Incident reicept amount:</strong>
                                    {{ $unit->incident_reicept_amount }}</p>
                                <p class="mb-3"><strong>Price:</strong>
                                    {{ $unit->price }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card shadow">
                            <div class="card-body">
                                <p><strong>Property Documents:</strong>
                                    @if (is_array($property->images))
                                        @foreach ($property->images ?? [] as $image)
                                            <div>
                                                <img src="{{ asset('images/' . $image) }}"
                                                    class="w-100 rounded-3 object-cover" />
                                            </div>
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($unit->lease)
                <div class="col-lg-12 mb-3">
                    <h4 class="text-black fs-20 font-bold mb-3">Lease Information</h4>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-success font-bold mb-4"><u>Tenent Information : </u></h5>
                                    <div class="col-lg-6 mb-1">
                                        <span>Name : </span> <strong>{{ $unit->lease->tenant->name }}</strong>
                                    </div>
                                    <div class="col-lg-6 mb-1">
                                        <span>Email : </span> <strong>{{ $unit->lease->tenant->email }}</strong>
                                    </div>
                                    <div class="col-lg-6 mb-1">
                                        <span>Phone No : </span> <strong>{{ $unit->lease->tenant->mobile }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-success font-bold mb-4"><u>Leasing Information : </u></h5>
                                    <div class="row">
                                        <div class="col-lg-6 mb-1"><span>Lease Start Date : </span>
                                            <strong>{{ dateFormat($unit->lease->lease_start_date) }}</strong>
                                        </div>
                                        <div class="col-lg-6 mb-1"><span>Lease End Date : </span>
                                            <strong>{{ dateFormat($unit->lease->lease_end_date) }}</strong>
                                        </div>
                                        <div class="col-lg-6 mb-1"><span>Free Period Start : </span>
                                            <strong>{{ dateFormat($unit->lease->free_period_start) }}</strong>
                                        </div>
                                        <div class="col-lg-6 mb-1"><span>Free Period End : </span>
                                            <strong>{{ dateFormat($unit->lease->free_period_end) }}</strong>
                                        </div>
                                        <div class="col-lg-6 mb-1"><span>Property Number : </span>
                                            <strong>{{ $unit->lease->property_number }}</strong>
                                        </div>
                                        <div class="col-lg-6 mb-1"><span>Contract Number : </span>
                                            <strong>{{ $unit->lease->contract_number }}</strong>
                                        </div>
                                        <div class="col-lg-6 mb-1"><span>No of Payments : </span>
                                            <strong>{{ $unit->lease->no_of_payments }}</strong>
                                        </div>

                                        <div class="col-lg-6 mb-1 text-capitalize"><span>Status : </span>
                                            <strong>{{ $unit->lease->status }}</strong>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="text-success font-bold mb-4"><u>Cheque Information : </u></h5>
                                    <div class="table-responsive mt-3">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="text-start">Cheque Number</th>
                                                    <th class="text-start">Cheque Date</th>
                                                    <th class="text-end">Payee</th>
                                                    <th class="text-end">Amount</th>
                                                    <th class="text-center">Bank Name</th>
                                                    <th class="text-center">Cheque Image</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($unit->lease->cheques ?? [] as $key => $cheque)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $cheque->cheque_number }}</td>
                                                        <td>{{ dateFormat($cheque->cheque_date) }}</td>
                                                        <td class="text-end">{{ $cheque->payee }}</td>
                                                        <td class="text-end">{{ $cheque->amount }}</td>
                                                        <td class="text-center">{{ $cheque->bank_name }}</td>
                                                        <td class="text-center mx-auto"><a
                                                                href="{{ asset('storage/' . $cheque->chequeImage->file_url) }}"
                                                                target="_blank"><img class="w-10 h-10 mx-auto"
                                                                    src="{{ asset('storage/' . $cheque->chequeImage->file_url) }}" /></a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @can('edit a unit')
                    <div class="col-lg-12 mb-3 text-center">
                        <div class="btn-group">
                            @if ($unit->lease->status == 'under review')
                                {!! Form::open([
                                    'method' => 'POST',
                                    'route' => ['company.realestate.properties.lease.approve', $unit->id],
                                    'id' => 'approve-form-' . $unit->id,
                                ]) !!}
                                <a href="#" class="me-3 rounded-4 text-light btn btn-success bs-pass-para"
                                    data-bs-toggle="tooltip" title="{{ __('Approve') }}">
                                    <i class="ti ti-check  "></i>
                                    {{ __('Approve') }}</a>
                                {!! Form::close() !!}
                            @endif
                            @if ($unit->lease->status != 'case' && $unit->lease->status != 'under review')
                                {!! Form::open([
                                    'method' => 'POST',
                                    'route' => ['company.realestate.properties.lease.in-hold', $unit->id],
                                    'id' => 'hold-form-' . $unit->id,
                                ]) !!}

                                <a href="#" class="me-3 rounded-4 text-light btn btn-info  bs-pass-para"
                                    data-bs-toggle="tooltip" title="{{ __('In Hold') }}">
                                    <i class="ti ti-refresh  "></i>
                                    {{ __('In Hold') }}</a>
                                {!! Form::close() !!}
                            @endif
                            @if ($unit->lease->status != 'canceled' && $unit->lease->status != 'under review')
                                {!! Form::open([
                                    'method' => 'POST',
                                    'route' => ['company.realestate.properties.lease.cancel', $unit->id],
                                    'id' => 'cancel-form-' . $unit->id,
                                ]) !!}
                                <a href="#" class=" me-3 rounded-4 text-light btn btn-warning bs-pass-para"
                                    data-bs-toggle="tooltip" title="{{ __('Cancel') }}">
                                    <i class="ti ti-refresh  "></i>
                                    {{ __('Cancel') }}</a>
                                {!! Form::close() !!}
                            @endif
                            {!! Form::open([
                                'method' => 'POST',
                                'route' => ['company.realestate.properties.lease.destroy', $unit->id],
                                'id' => 'destroy-form-' . $unit->id,
                            ]) !!}
                            <a href="#" class=" me-3 rounded-4 text-light btn btn-danger bs-pass-para"
                                data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                <i class="ti ti-trash  "></i>
                                {{ __('Delete') }}</a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                @endcan
            @endif
        </div>
    @endcan
@endsection
