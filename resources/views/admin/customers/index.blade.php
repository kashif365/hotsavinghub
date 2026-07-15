@extends('admin.layouts.app')

@section('title', 'Customer Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Customer Management</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                            <i class="ri-add-line me-1"></i>Add New Customer
                        </a>
                        <span class="badge bg-primary">{{ $customers->total() }} Total</span>
                        <span class="badge bg-success">{{ $customers->where('status', true)->count() }} Active</span>
                        <span class="badge bg-info">{{ $customers->where('is_subscribed', true)->count() }} Subscribed</span>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($customers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Newsletter</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                        <tr class="{{ !$customer->status ? 'table-secondary' : '' }}">
                                            <td>
                                                <input type="checkbox" name="customer_ids[]" value="{{ $customer->id }}" class="form-check-input customer-checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-initial rounded-circle bg-primary">
                                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $customer->name }}</h6>
                                                        <small class="text-muted">ID: {{ $customer->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                                    {{ $customer->email }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($customer->status)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-warning">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($customer->is_subscribed)
                                                    <span class="badge bg-info">Subscribed</span>
                                                @else
                                                    <span class="badge bg-secondary">Not Subscribed</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $customer->created_at->format('M d, Y') }}<br>
                                                    {{ $customer->created_at->format('h:i A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.customers.show', $customer) }}">
                                                                <i class="ri-eye-line me-2"></i>View Details
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.customers.edit', $customer) }}">
                                                                <i class="ri-edit-line me-2"></i>Edit Customer
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.customers.update-status', $customer) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="{{ $customer->status ? 0 : 1 }}">
                                                                <button type="submit" class="dropdown-item">
                                                                    @if($customer->status)
                                                                        <i class="ri-pause-line me-2"></i>Deactivate
                                                                    @else
                                                                        <i class="ri-check-line me-2"></i>Activate
                                                                    @endif
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <i class="ri-delete-bin-line me-2"></i>Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Bulk Actions -->
                        <div class="mt-3">
                            <button type="button" class="btn btn-outline-danger btn-sm" id="bulkDeleteBtn" disabled>
                                <i class="ri-delete-bin-line me-1"></i>Delete Selected
                            </button>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $customers->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-user-line display-1 text-muted"></i>
                            <h4 class="mt-3">No Customers Found</h4>
                            <p class="text-muted">No customers have been registered yet.</p>
                            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i>Create First Customer
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Selected Customers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the selected customers? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.customers.bulk-delete') }}" method="POST" id="bulkDeleteForm">
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete Selected</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const customerCheckboxes = document.querySelectorAll('.customer-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        customerCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkDeleteButton();
    });

    // Individual checkbox change
    customerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkDeleteButton();
            updateSelectAllState();
        });
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.customer-checkbox:checked');
        bulkDeleteBtn.disabled = checkedBoxes.length === 0;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.customer-checkbox:checked');
        const totalBoxes = customerCheckboxes.length;
        
        if (checkedBoxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedBoxes.length === totalBoxes) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }

    // Bulk delete functionality
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.customer-checkbox:checked');
        const customerIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        // Add hidden inputs for selected customer IDs
        bulkDeleteForm.innerHTML = '';
        customerIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'customer_ids[]';
            input.value = id;
            bulkDeleteForm.appendChild(input);
        });
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        bulkDeleteForm.appendChild(csrfInput);
        
        // Add submit button
        const submitBtn = document.createElement('button');
        submitBtn.type = 'submit';
        submitBtn.className = 'btn btn-danger';
        submitBtn.textContent = 'Delete Selected';
        bulkDeleteForm.appendChild(submitBtn);
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
        modal.show();
    });
});
</script>
@endsection
