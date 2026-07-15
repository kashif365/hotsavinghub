@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Manage Networks</h4>
            <div>
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createNetworkModal">
                    + Add Network
                </button>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table id="networksTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Network Name</th>
                        <th>Affiliate ID</th>
                        <th>Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($networks as $network)
                        <tr data-id="{{ $network->id }}">
                            <td><input type="checkbox" value="{{ $network->id }}" class="rowCheckbox"></td>
                            <td class="reorder-handle" style="cursor:move;">☰</td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $network->name }}</td>
                            <td>{{ $network->affiliate_id ?? '-' }}</td>
                            <td>
                                @if($network->status)
                                    <span class="badge bg-success">Enabled</span>
                                @else
                                    <span class="badge bg-danger">Disabled</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning"
                                        data-bs-toggle="modal" data-bs-target="#editNetworkModal{{ $network->id }}">
                                    Edit
                                </button>

                                <form action="{{ route('admin.networks.destroy', $network->id) }}"
                                      method="POST"
                                      class="d-inline single-delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Edit modal --}}
                        <div class="modal fade" id="editNetworkModal{{ $network->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.networks.update', $network->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        @include('admin.networks.form', ['network' => $network])
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

            {{-- Hidden form for bulk delete --}}
            <form id="bulkDeleteForm" action="{{ route('admin.networks.bulkDelete') }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>

{{-- Create modal --}}
<div class="modal fade" id="createNetworkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.networks.store') }}" method="POST">
                @csrf
                @include('admin.networks.form', ['network' => null])
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    initDataTableWithFeatures({
        tableSelector: '#networksTable',
        bulkDeleteBtnSelector: '#bulk-delete-btn',
        selectAllSelector: '#selectAll',
        rowHandleSelector: 'td.reorder-handle',
        reorderUrl: "{{ route('admin.networks.reorder') }}",
        csrfToken: "{{ csrf_token() }}",
        bulkDeleteUrl: "{{ route('admin.networks.bulkDelete') }}" // JS bulk delete ke liye zaroori
    });
});
</script>
@endsection
