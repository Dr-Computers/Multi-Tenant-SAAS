<form method="POST" action="{{ route('company.addon.features.store') }}" id="purchase-form">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="row">
                {{-- Allowed Sections --}}
                <div class="form-group col-md-12">
                    @foreach ($sections->groupBy('category') as $category => $groupedSections)
                        <div class="mt-3">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white p-2">
                                    <h6 class="mb-0 fw-bold text-light">{{ $category }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        @foreach ($groupedSections as $section)
                                            @php
                                                // Check if this section already exists
                                                $alreadyPurchased = $existingSections->contains(
                                                    'section_id',
                                                    $section->id,
                                                );
                                            @endphp

                                            @if (!$alreadyPurchased)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="checkbox" name="features[]"
                                                            class="form-check-input input-primary pointer section-checkbox"
                                                            data-name="{{ $section->name }}"
                                                            data-price="{{ $section->price }}"
                                                            value="{{ $section->id }}"
                                                            id="section_{{ $section->id }}">
                                                        <label class="form-check-label text-sm text-capitalize"
                                                            for="section_{{ $section->id }}">
                                                            {{ $section->name }} -
                                                            ₹{{ number_format($section->price, 2) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check form-switch custom-switch-v1">
                                                        <input type="checkbox" disabled checked
                                                            class="form-check-input input-primary pointer"
                                                            value="{{ $section->id }}"
                                                            id="section_{{ $section->id }}">
                                                        <label class="form-check-label text-sm text-capitalize"
                                                            for="section_{{ $section->id }}">
                                                            {{ $section->name }} -
                                                            ₹{{ number_format($section->price, 2) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold">Added Sections</h6>
                    <ul class="list-group mb-3" id="cart-list">
                        <li class="list-group-item text-muted small">No sections added yet.</li>
                    </ul>

                    <h6 class="my-4">Sub Total: {{ adminPrice() }}<span id="subtotal">0.00</span></h6>
                    <div class="my-4">
                        <label class="form-label small mb-1">Coupon Code</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="coupon_code_input">
                            <button class="btn btn-primary" type="button" id="apply_coupon">Apply</button>
                        </div>
                        <small class="text-success" id="coupon_result"></small>
                    </div>

                    <h6 class="my-4">Discount: {{ adminPrice() }}<span id="discount">0.00</span></h6>
                    <div class="my-2 d-flex gap-3">
                        <h6 class="form-label  mb-1">Tax (5%) : {{ adminPrice() }}</h6><h6 id="tax">0.00</h6>
                    </div>

                    {{-- Hidden Fields to submit values --}}
                    <input type="hidden" name="subtotal" id="hidden_subtotal" value="0">
                    <input type="hidden" name="discount" id="hidden_discount" value="0">
                    <input type="hidden" name="grandtotal" id="hidden_grandtotal" value="0">
                    <input type="hidden" name="coupon_code" id="hidden_coupon_code" value="0">

                    <h6 class="my-4">Discount : {{ adminPrice() }}<span id="grandtotal">0.00</span></h6>

                    <div class="my-2">
                        <label class="form-label small mb-1">Tax (%)</label>
                        <input type="number" class="form-control form-control-sm" id="tax" name="tax"
                            value="0" min="0">
                    </div>
                    <h6 class="my-4">Grand Total: {{ adminPrice() }}<span id="grandtotal">0.00</span></h6>

                    <div class="my-4">
                        <button type="submit" class="btn btn-success btn-sm w-100">Purchase</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
