<form method="POST" action="{{ route('admin.company.addon-features.store', $user->id) }}"  id="purchase-form">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="alert alert-info small mb-4">
                <strong>Note:</strong><br>
                • If a feature's validity has <span class="text-danger fw-bold">expired</span>, you can <span class="text-primary fw-bold">toggle it ON</span> to renew.<br>
                • If the feature is still <span class="text-success fw-bold">active</span>, renewing will <span class="fw-bold">extend</span> the existing validity by the renewal period.<br>
                • Clicking the <i class="ti ti-circle-x text-danger"></i> button will <span class="text-danger fw-bold">remove</span> the feature from the user's existing features.
            </div>
            
            <div class="row">
                {{-- Allowed Sections --}}
                <div class="form-group col-md-12">
                    @foreach ($existingSections->groupBy('category') as $category => $groupedSections)
                        <div class="mt-3">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white p-2">
                                    <h6 class="mb-0 fw-bold text-light">{{ $category }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach ($groupedSections as $section)
                                            @php
                                                $validityDate = \Carbon\Carbon::parse(
                                                    $section->addedSections->section_validity,
                                                );
                                                $isExpired = $validityDate->isPast();
                                                $validityColor = $isExpired ? 'text-danger' : 'text-success';
                                            @endphp
                                            <div class="col-md-6 mb-3" id="existing_section_{{ $section->id }}">
                                                <div class="form-check form-switch custom-switch-v1 position-relative">
                                                    <input type="checkbox" name="features[]"
                                                        class="form-check-input input-primary pointer section-checkbox"
                                                        {{-- {{ $isExpired ? '' : 'checked' }} --}}
                                                        data-name="{{ $section->name }}"
                                                        data-price="{{ $section->price }}" value="{{ $section->id }}"
                                                        id="section_{{ $section->id }}">
                                                    <label class="form-check-label text-sm"
                                                        for="section_{{ $section->id }}">
                                                        {{ $section->name }}
                                                    </label>
                                                    <div class="small {{ $validityColor }}">
                                                        Valid till: {{ $validityDate->format('d M Y') }}
                                                    </div>
                                                    {{-- Remove Button --}}
                                                    <span role="button"
                                                        class=" position-absolute top-0 end-0 btn-sm remove-existing-btn"
                                                        data-id="{{ $section->id }}">
                                                        <i class="ti ti-circle-x fs-4 text-danger"></i>
                                                    </span>
                                                </div>
                                            </div>
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

                    <h6 class="my-4">Sub Total: ₹<span id="subtotal">0.00</span></h6>

                    <div class="my-2 d-none">
                        <label class="form-label small mb-1">Tax (%)</label>
                        <input type="number" class="form-control form-control-sm" id="tax" name="tax"
                            value="0" min="0">
                    </div>

                    <div class="my-4 d-none">
                        <label class="form-label small mb-1">Coupon Code</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="coupon_code_input">
                            <button class="btn btn-primary" type="button" id="apply_coupon">Apply</button>
                        </div>
                        <small class="text-success" id="coupon_result"></small>
                    </div>

                    {{-- Hidden Fields to submit values --}}
                    <input type="hidden" name="subtotal" id="hidden_subtotal">
                    <input type="hidden" name="discount" id="hidden_discount">
                    <input type="hidden" name="grandtotal" id="hidden_grandtotal">
                    <input type="hidden" name="coupon_code" id="hidden_coupon_code">

                    <h6 class="my-4">Grand Total: ₹<span id="grandtotal">0.00</span></h6>

                    <div class="my-4">
                        <button type="submit" class="btn btn-success btn-sm w-100">Purchase</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
