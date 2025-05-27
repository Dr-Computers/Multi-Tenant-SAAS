@extends('layouts.company')
@section('page-title')
    {{ __('Invoice') }}
@endsection
@section('breadcrumb')
    <ul class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <h1>{{ __('Dashboard') }}</h1>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <a href="#">{{ __('Assets') }}</a>
        </li>
    </ul>
@endsection
@section('action-btn')
    {{-- @can('create asset') --}}
    <a class="btn btn-primary btn-sm ml-20" href="{{ route('company.assets.create') }}"> <i
            class="ti ti-plus "></i>{{ __('Create Asset') }}</a>
    {{-- @endcan --}}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body table-bUsers-style">
                    <table class="table ">
                        <thead>
                            <tr>

                                <th>Asset Name</th>
                                <th>Asset Type</th>
                                <th>Property</th>
                                <th>Location</th>
                                <th>Purchase Date</th>
                                <th>Purchase Price</th>
                                <th>Market Value</th>
                                <th>Vendor Name</th>

                                {{-- @if (Gate::check('edit asset') || Gate::check('delete asset') || Gate::check('show asset')) --}}
                                    <th class="text-right">{{ __('Action') }}</th>
                                {{-- @endif --}}
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = '1'; ?>
                            @forelse($assets as $asset)
                                <tr>

                                    <td>{{ $asset->name }}</td>
                                    <td>
                                        @if ($asset->type == 'fixed_asset')
                                            Fixed Asset
                                        @elseif($asset->type == 'current_asset')
                                            Current Asset
                                        @elseif($asset->type == 'bank')
                                            Bank
                                        @else
                                            {{ $asset->type }}
                                        @endif
                                    </td>

                                    <td>{{ $asset->property ? $asset->property->name : 'N/A' }}</td>
                                    <td>{{ $asset->location }}</td>
                                    <td>{{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('Y-m-d') : 'N/A' }}
                                    </td>
                                    <td>{{ $asset->purchase_price ? priceFormat($asset->purchase_price) : 'N/A' }}</td>
                                    <td>{{ $asset->current_market_value ? priceFormat($asset->current_market_value) : 'N/A' }}
                                    </td>
                                    <td>{{ $asset->vendor_name ? $asset->vendor_name : 'N/A' }}</td>
                                    {{-- @if (Gate::check('edit asset') || Gate::check('delete asset') || Gate::check('show asset')) --}}
                                        <td class="text-right">
                                            <div class="cart-action">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['company.assets.destroy', $asset->id]]) !!}

                                                {{-- @can('edit asset') --}}
                                                    <a class="text-success" href="{{ route('company.assets.edit', $asset->id) }}"
                                                        data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}">
                                                        <i data-feather="edit"></i></a>
                                                {{-- @endcan
                                                @can('delete asset') --}}
                                                    <a class=" text-danger bs-pass-para" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Detete') }}" href="#"> <i
                                                            data-feather="trash-2"></i></a>
                                                {{-- @endcan --}}
                                                {!! Form::close() !!}
                                            </div>

                                        </td>
                                    {{-- @endif --}}
                                </tr>
                                <?php $count++; ?>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="text-center">
                                            No data found..!
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                    <div class="d-flex justify-content-end" style="margin-top: 10px;">
                        {!! $assets->onEachSide(2)->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
