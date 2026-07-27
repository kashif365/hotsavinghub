@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manage Cashback Brands</h4>
            <div class="d-flex">
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <a href="{{ route('admin.cashback-brands.create') }}" class="btn btn-primary btn-sm">+ Add New Brand</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class='alert alert-success'>{{ session('success') }}</div>
            @elseif(session('error'))
                <div class='alert alert-danger'>{{ session('error') }}</div>
            @endif

            <p class="text-muted small">Active brands appear as circular logo icons with their cash back rate in the "Exclusive Offers" row on the homepage. Drag the ☰ handle to reorder.</p>

            <form id="bulkDeleteForm" method="POST" action="{{ route('admin.cashback-brands.bulkDelete') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="ids" id="bulkDeleteIds">
            </form>

            <table id="cashbackBrandsTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Logo</th>
                        <th>Store Name</th>
                        <th>Cash Back</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="cashbackBrandsTableBody">
                    @foreach($cashbackBrands as $brand)
                    <tr data-id="{{ $brand->id }}" data-status="{{ $brand->status }}">
                        <td>
                            <input type="checkbox" name="ids[]" value="{{ $brand->id }}" class="rowCheckbox">
                        </td>
                        <td class="reorder-handle" style="cursor:move;">☰</td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($brand->logo)
                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->store_name }}" style="width: 50px; height: 50px; object-fit: contain; border-radius: 50%; border: 1px solid #eee; padding: 4px;">
                            @else
                                <span class="text-muted">No Logo</span>
                            @endif
                        </td>
                        <td>{{ $brand->store_name }}</td>
                        <td>{{ $brand->cashback_rate }}% Cash Back</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="status-toggle" data-id="{{ $brand->id }}" {{ $brand->status ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td>
                            <a href="{{ route('admin.cashback-brands.edit', $brand) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.cashback-brands.destroy', $brand) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.switch {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}
.switch input {
    opacity: 0;
    width: 0;
    height: 0;
}
.slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
}
.slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
}
input:checked + .slider {
    background-color: #2196F3;
}
input:checked + .slider:before {
    transform: translateX(26px);
}
.slider.round {
    border-radius: 24px;
}
.slider.round:before {
    border-radius: 50%;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bulk delete
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.rowCheckbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    const bulkDeleteIds = document.getElementById('bulkDeleteIds');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
        updateBulkDeleteBtn();
    });

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            selectAll.checked = Array.from(checkboxes).every(c => c.checked);
            updateBulkDeleteBtn();
        });
    });

    function updateBulkDeleteBtn() {
        const selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        bulkDeleteBtn.disabled = selected.length === 0;
        if (selected.length > 0) {
            bulkDeleteIds.value = JSON.stringify(selected);
        }
    }

    bulkDeleteBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to delete selected cashback brands?')) {
            bulkDeleteForm.submit();
        }
    });

    // Status toggle
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const id = this.dataset.id;
            const status = this.checked ? 1 : 0;

            fetch(`/admin/cashback-brands/${id}/update-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Status updated');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Reorder
    const tbody = document.getElementById('cashbackBrandsTableBody');
    if (typeof Sortable !== 'undefined' && window.Sortable) {
        try {
            new Sortable(tbody, {
                handle: '.reorder-handle',
                animation: 150,
                onEnd: function(evt) {
                    const order = Array.from(tbody.querySelectorAll('tr')).map(tr => tr.dataset.id);

                    fetch('/admin/cashback-brands/reorder', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ order: order })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
        } catch (e) {
            console.error('Sortable initialization error:', e);
        }
    }
});
</script>
@endsection
