@extends('layouts.company')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('content')
    @php
        $user = auth()->user();
        $isExpired = $user->company && \Carbon\Carbon::parse($user->company->activeSubscription->end_of_dat)->isPast();
    @endphp

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <img src="{{ asset('assets/images/error.png') }}" alt="Notification" class="img-fluid rounded-circle mb-4 mx-auto"
                    style="width: 150px; height: 150px;">

                @if ($user->status === 'pending')
                    <h2 class="text-warning mb-3">Account Under Review</h2>
                    <p class="lead">Thank you for your patience! Your account is currently under review.</p>
                    <p>This helps us ensure everything is properly set up for your experience. For urgent issues, please
                        contact customer support.</p>
                @elseif ($user->status === 'suspended')
                    <h2 class="text-danger mb-3">Account Suspended</h2>
                    <p class="lead">Unfortunately, your account has been suspended due to policy violations or unresolved
                        issues.</p>
                    <p>Please contact customer support to resolve this matter.</p>
                @elseif ($isExpired)
                    <h2 class="text-danger mb-3">Plan Expired</h2>
                    <p class="lead">Your subscription plan has expired.</p>
                    <p>To continue accessing our features, please renew your plan. If you need help, contact our support
                        center.</p>
                @endif

                <a href="tel:+971562346398" class="btn btn-primary my-3 ">
                    <i class="bi bi-telephone-forward-fill"></i> Call Support Center
                </a>
                 <ul class="list-unstyled mb-0">
                        <li><strong>Mobile:</strong> <a href="tel:+971562346398">+971 56 234 6398</a></li>
                        <li><strong>Landline:</strong> <a href="tel:+97142831175">+971 4 283 1175</a></li>
                        <li><strong>Email:</strong> <a href="mailto:info@drcomputers.com">info@drcomputers.com</a></li>
                    </ul>
            </div>
        </div>
    </div>
@endsection
