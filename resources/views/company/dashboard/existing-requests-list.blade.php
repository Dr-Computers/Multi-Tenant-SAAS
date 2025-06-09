@if ($existingRequests->count())
    <div class="table-responsive mt-4">
        <table class="table table-bordered table-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Section</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Requested At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($existingRequests as $index => $request)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-capitalize">
                            @php
                                $sections = \App\Models\Section::whereIn('id', $request->section_ids)
                                    ->pluck('name')
                                    ->toArray();
                            @endphp
                            {{ implode(', ', $sections) }}
                        </td>
                        <td>
                           {{ adminPrice() }} {{ $request->grand_total }}
                        </td>

                        <td>
                            @if ($request->status === 'approved')
                                <span class="badge bg-success text-white fw-bold">Approved</span>
                            @elseif($request->status === 'pending')
                                <span class="badge bg-warning text-dark text-white fw-bold">Pending</span>
                            @elseif($request->status === 'rejected')
                                <span class="badge bg-danger text-white fw-bold">Rejected</span>
                            @else
                                <span class="badge bg-secondary text-white fw-bold">Unknown</span>
                            @endif
                        </td>
                        <td>{{ $request->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted mt-3">No existing section requests found.</p>
@endif
