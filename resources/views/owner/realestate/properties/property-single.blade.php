@extends('layouts.owner')
@section('page-title')
    {{ __('Properties') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('owner.realestate.properties.index') }}">{{ __('Properties') }}</a></li>
    <li class="breadcrumb-item">Property Information : {{ $property->name }} </li>
@endsection
@section('action-btn')
    <a href="{{ route('owner.realestate.properties.index') }}" class="btn btn-sm btn-primary">
        <i class="ti ti-arrow-left"></i> {{ __('Back') }}
    </a>
@endsection
@section('content')
    @can('property details')
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
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <strong class="mb-3 text-success font-bold">Fire Safety
                                                                Dates</strong><br><br>
                                                            <p class="mb-2">Start : {{ $property->fire_safty_start_date }}
                                                            </p>
                                                            <p class="mb-2">End : {{ $property->fire_safty_end_date }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <strong class="mb-3 text-success font-bold ">Insurance
                                                                Dates</strong><br><br>
                                                            <p class="mb-2">Start : {{ $property->insurance_start_date }}</p>
                                                            <p class="mb-2">End : {{ $property->insurance_end_date }}</p>
                                                        </div>
                                                    </div>
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

                                                    @foreach ($property->amenities ?? [] as $key => $amenity)
                                                        <div class="col-lg-3 mb-3">
                                                            <div class="d-flex  flex-warp items-center">
                                                                <img src="{{ $amenity->image_url }}" class="">
                                                                <span class="ms-2 text-sm">{{ $amenity->name }}</span>
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
                        <p class="mb-2"><strong>Property Landmarks:</strong>
                            @if ($property->landmarks)
                                @foreach ($property->landmarks ?? [] as $landmark)
                                    <div class="row mb-2">
                                        <div class="col-lg-4">
                                            {{ $landmark->name }}
                                        </div>
                                        <div class="col-lg-8">
                                            {{ $landmark->pivot->landmark_value }}
                                        </div>
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
                        <p><strong>Property Images:</strong></p>
                        <div class="row mt-4">
                            @foreach ($property->propertyImages ?? [] as $key => $image)
                                @php
                                    $isImage2 = Str::startsWith($image->mime_type, 'image/');
                                    $icon2 = match (true) {
                                        Str::contains($image->mime_type, 'pdf') => '/assets/icons/pdf-icon.png',
                                        Str::contains($image->mime_type, 'msword'),
                                        Str::contains($image->mime_type, 'wordprocessingml')
                                            => '/assets/icons/docx-icon.png',
                                        default => '/assets/icons/file-icon.png',
                                    };
                                    $thumbnail2 = $isImage2 ? asset('storage/' . $image->file_url) : asset($icon2);
                                @endphp
                                <div class="col-lg-2 mb-2">
                                    <div class="relative text-center group border rounded-lg overflow-hidden ">
                                        <img src="{{ $thumbnail2 }}" alt="{{ $image->alt ?? $image->name }}"
                                            class="w-auto object-cover mx-auto mb-2 rounded"
                                            style="height: 100px;width: 100%;">
                                        <span title="{{ $image->name }}">{{ $image->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card shadow">
                    <div class="card-body">
                        <p><strong>Property Documents:</strong></p>
                        <div class="row mt-4">
                            @foreach ($property->propertyDocuments ?? [] as $key => $document)
                                @php
                                    $isImage = Str::startsWith($document->mime_type, 'image/');
                                    $icon = match (true) {
                                        Str::contains($document->mime_type, 'pdf') => '/assets/icons/pdf-icon.png',
                                        Str::contains($document->mime_type, 'msword'),
                                        Str::contains($document->mime_type, 'wordprocessingml')
                                            => '/assets/icons/docx-icon.png',
                                        default => '/assets/icons/file-icon.png',
                                    };
                                    $thumbnail = $isImage ? asset('storage/' . $document->file_url) : asset($icon);
                                @endphp
                                <div class="col-lg-2 mb-2">
                                    <div class="relative text-center group border rounded-lg overflow-hidden ">
                                        <img src="{{ $thumbnail }}" alt="{{ $document->alt ?? $document->name }}"
                                            class="w-auto  object-cover mx-auto mb-2 py-3 rounded"
                                            style="height: 100px;width: 100%;">
                                        <span title="{{ $document->name }}">{{ $document->name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-black fs-20 font-bold mb-3">Unit Informations</h4>
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Rent Type</th>
                                    <th class="text-start">Price</th>
                                    <th class="text-center">Rooms</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($property->units ?? [] as $key2 => $unit)
                                    <tr>
                                        <td>{{ $key2 + 1 }}</td>
                                        <td class="text-start text-capitalize">
                                            <a title="{{ $unit->name }}"
                                                href="{{ route('company.realestate.property.units.show', [$property->id, $unit->id]) }}">
                                                {{ $unit->name }}
                                            </a>
                                        </td>
                                        <td class="text-start text-capitalize">{{ $unit->rent_type }}</td>
                                        <td class="text-start">{{ $unit->price }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-dark p-1 px-3 rounded">Bedrooms :
                                                {{ $unit->bed_rooms }}</span>
                                            <span class="badge bg-dark p-1 px-3 rounded">Bathrooms :
                                                {{ $unit->bath_rooms }}</span><br>
                                            <span class="badge bg-dark p-1 px-3 rounded">Kitchen : {{ $unit->kitchen }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($unit->status == '1')
                                                <span class="badge bg-success p-1 px-3 rounded">
                                                    {{ ucfirst('Leased') }}</span>
                                            @else
                                                <span class="badge bg-danger p-1 px-3 rounded">
                                                    {{ ucfirst('Unleased') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">

                                            <div class="btn-group card-option">
                                                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    @can('unit details')
                                                        <a href="{{ route('company.realestate.property.units.show', [$property->id, $unit->id]) }}"
                                                            class="dropdown-item">
                                                            <i class="ti ti-eye text-dark"></i> {{ __('Show') }}
                                                        </a>
                                                    @endcan
                                                   
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    @endcan
@endsection
