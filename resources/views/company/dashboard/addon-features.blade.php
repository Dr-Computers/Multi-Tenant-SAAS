@extends('layouts.company')
@section('page-title')
    {{ __('Addon Features') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Addon Features') }}</li>
@endsection


@section('action-btn')
    <div class="float-end">
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('Back') }}">
            <i class="ti ti-arrow-back"></i> {{ __('Back') }}
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="userTabs">
                        @php
                            $tab = request()->get('tab', 'addon-features');
                        @endphp
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'addon-features' ? 'active' : '' }}"
                                href="{{ route('company.addon.features') }}?tab=addon-features">Addon
                                Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'existing-features' ? 'active' : '' }}"
                                href="{{ route('company.addon.features') }}?tab=existing-features">Existing
                                Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $tab == 'existing-requests' ? 'active' : '' }}"
                                href="{{ route('company.addon.features') }}?tab=existing-requests">Existing
                                Requestes</a>
                        </li>
                    </ul>
                    {{-- Tab Content --}}
                    <div class="tab-content p-4 border border-top-0 rounded-bottom">
                        @if ($tab == 'addon-features')
                            @include('company.dashboard.addon-features-list', [
                                'user' => $user,
                                'sections' => $addonSections,
                                'existingSections' => $existingSectionIds,
                            ])
                        @elseif($tab == 'existing-features')
                            @include('company.dashboard.existing-features-list', [
                                'user' => $user,
                                'sections' => $addonSections,
                                'existingSections' => $existingSections,
                            ])
                        @elseif($tab == 'existing-requests')
                            @include('company.dashboard.existing-requests-list', [
                                'user' => $user,
                                'existingRequests' => $existingRequests,
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@push('footer')
    <script>
        let cart = [];
        let discount = 0;

        function updateCart() {

            var discountAmount = parseFloat(document.getElementById('hidden_discount').value) || 0;
            let subtotal = cart.reduce((sum, item) => sum + item.price, 0);
            let tax = parseFloat(0.05) || 0.00;
            let taxAmount = (subtotal - discountAmount) * tax;
            let grandtotal = subtotal + taxAmount - discountAmount;

            document.getElementById('subtotal').innerText = `{{ adminPrice() }} ` + subtotal.toFixed(2);
            document.getElementById('grandtotal').innerText = `{{ adminPrice() }} ` + grandtotal.toFixed(2);
            document.getElementById('tax').innerText = `{{ adminPrice() }} ` + taxAmount.toFixed(2);


            // Update hidden fields
            document.getElementById('hidden_subtotal').value = subtotal.toFixed(2);
            document.getElementById('hidden_grandtotal').value = grandtotal.toFixed(2);
            document.getElementById('hidden_discount').value = discountAmount.toFixed(2);

            let cartList = document.getElementById('cart-list');
            cartList.innerHTML = '';

            if (cart.length === 0) {
                cartList.innerHTML = '<li class="list-group-item text-muted small">No sections added yet.</li>';
            } else {
                cart.forEach(item => {
                    cartList.innerHTML += `<li class="list-group-item d-flex justify-content-between align-items-center small">
                    ${item.name}
                    <span>₹${item.price.toFixed(2)}</span>
                </li>`;
                });
            }
        }

        document.querySelectorAll('.section-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let id = this.value;
                let name = this.dataset.name;
                let price = parseFloat(this.dataset.price);

                if (this.checked) {
                    cart.push({
                        id,
                        name,
                        price
                    });
                } else {
                    cart = cart.filter(item => item.id !== id);
                }
                updateCart();
            });
        });

        document.getElementById('tax').addEventListener('input', function() {
            updateCart();
        });
        document.getElementById('coupon_code_input').addEventListener('input', function() {
            document.getElementById('apply_coupon').click();
        });




        document.getElementById('apply_coupon').addEventListener('click', function() {
            let code = document.getElementById('coupon_code_input').value.trim();
            if (!code) return;

            fetch('{{ route('company.coupon.validate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: code
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        discount = parseFloat(data.amount);
                        console.log(data);

                        document.getElementById('coupon_result').innerText =
                            "Coupon applied Discount: {{ adminPrice() }}" + discount
                            .toFixed(2);
                        document.getElementById('hidden_coupon_code').value = code;
                        document.getElementById('discount').innerText = `- {{ adminPrice() }} ` + discount;
                        document.getElementById('hidden_discount').value = discount;

                    } else {
                        discount = 0;
                        document.getElementById('coupon_result').innerText = "Invalid coupon.";
                        document.getElementById('hidden_coupon_code').value = "";
                        document.getElementById('discount').innerText = `- {{ adminPrice() }} ` + 0;

                        document.getElementById('hidden_discount').value = 0;
                    }
                    updateCart();
                })
                .catch(error => console.error('Error:', error));
        });

        // Before form submit, update all hidden fields again
        document.getElementById('purchase-form').addEventListener('submit', function() {
            updateCart(); // ensure final values updated
        });


        document.querySelectorAll('.remove-existing-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const company_id = `{{ $user->id }}`;

                if (confirm('Are you sure you want to remove this feature?')) {
                    fetch(`/admin/company/existing-feature-remove/${company_id}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data); // Fixed typo here

                            if (data.status) { // ← You should check data.status (not data.success)
                                document.getElementById(`existing_section_${id}`).closest('.col-md-6')
                                    .remove();
                                alert('Feature removed successfully.');
                            } else {
                                alert(data.message || 'Failed to remove feature.');
                            }
                        })
                        .catch(() => alert('Server error.'));
                }
            });
        });
    </script>
@endpush
