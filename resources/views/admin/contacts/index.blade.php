@extends('admin.layouts.app')

@section('title', 'Contact Submissions')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Contact Submissions</h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-primary">{{ $contacts->total() }} Total</span>
                        <span class="badge bg-warning">{{ $contacts->where('status', 'new')->count() }} New</span>
                    </div>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($contacts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($contacts as $contact)
                                        <tr class="{{ $contact->status === 'new' ? 'table-warning' : '' }}">
                                            <td>
                                                <input type="checkbox" name="contact_ids[]" value="{{ $contact->id }}" class="form-check-input contact-checkbox">
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <span class="avatar-initial rounded-circle bg-primary">
                                                            {{ strtoupper(substr($contact->first_name, 0, 1)) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $contact->full_name }}</h6>
                                                        @if($contact->phone)
                                                            <small class="text-muted">{{ $contact->phone }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                                    {{ $contact->email }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($contact->subject) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'new' => 'danger',
                                                        'read' => 'warning', 
                                                        'replied' => 'success',
                                                        'closed' => 'secondary'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$contact->status] }}">
                                                    {{ ucfirst($contact->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $contact->created_at->format('M d, Y') }}<br>
                                                    {{ $contact->created_at->format('h:i A') }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        Actions
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('admin.contacts.show', $contact) }}">
                                                                <i class="ri-eye-line me-2"></i>View Details
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="read">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="ri-eye-line me-2"></i>Mark as Read
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="replied">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="ri-reply-line me-2"></i>Mark as Replied
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('admin.contacts.update-status', $contact) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <input type="hidden" name="status" value="closed">
                                                                <button type="submit" class="dropdown-item">
                                                                    <i class="ri-check-line me-2"></i>Mark as Closed
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this contact submission?')">
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
                            {{ $contacts->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-mail-line display-1 text-muted"></i>
                            <h4 class="mt-3">No Contact Submissions</h4>
                            <p class="text-muted">No contact form submissions have been received yet.</p>
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
                <h5 class="modal-title">Delete Selected Contacts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the selected contact submissions? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.contacts.bulk-delete') }}" method="POST" id="bulkDeleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Selected</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const contactCheckboxes = document.querySelectorAll('.contact-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        contactCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkDeleteButton();
    });

    // Individual checkbox change
    contactCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkDeleteButton();
            updateSelectAllState();
        });
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
        bulkDeleteBtn.disabled = checkedBoxes.length === 0;
    }

    function updateSelectAllState() {
        const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
        const totalBoxes = contactCheckboxes.length;
        
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
        const checkedBoxes = document.querySelectorAll('.contact-checkbox:checked');
        const contactIds = Array.from(checkedBoxes).map(cb => cb.value);
        
        // Add hidden inputs for selected contact IDs
        bulkDeleteForm.innerHTML = '';
        contactIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'contact_ids[]';
            input.value = id;
            bulkDeleteForm.appendChild(input);
        });
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        bulkDeleteForm.appendChild(csrfInput);
        
        // Add method spoofing for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        bulkDeleteForm.appendChild(methodInput);
        
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
