<div class="modal-body">
    <div class="container-fluid">
        <h2 class="text-lg font-bold mb-4">Permissions for Role: {{ $role->name }}</h2>

        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-200">
                    <th  colspan="2" class="px-4 py-2 border">Permission Name</th>
                    <th class="px-4 py-2 border">Has Permission?</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allPermissions->groupBy('section') as $section => $permission)
                <tr>
                    <td colspan="3" class="text-center">
                       <strong>{{ $section }}</strong> 
                    </td>
                </tr>
                    @foreach ($permission ?? [] as $permission_section)
                        <tr>
                            <td colspan="2" class="px-4 py-2 border text-capitalize">{{ $permission_section->name }}</td>
                            <td class="px-4 py-2 border text-center">
                                @if ($role->hasPermissionTo($permission_section->name))
                                    ✅
                                @else
                                    ❌
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

</div>
