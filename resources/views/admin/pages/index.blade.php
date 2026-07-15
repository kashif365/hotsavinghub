@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Manage Pages</h4>
            <div class="d-flex">
                <button id="bulk-delete-btn" class="btn btn-danger btn-sm mx-3" disabled>Delete Selected</button>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-success btn-sm">➕ Add Page</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class='alert alert-success'>{{ session('success') }}</div>
            @elseif(session('error'))
                <div class='alert alert-danger'>{{ session('error') }}</div>
            @endif

            {{-- No outer bulk form needed --}}
            <table id="pagesTable" class="table table-bordered table-striped align-middle w-100">
                <thead>
                    <tr>
                        <th width="30"><input type="checkbox" id="selectAll"></th>
                        <th width="30">☰</th>
                        <th>SL</th>
                        <th>Title</th>
                        <th>SEO URL</th>
                        <th>Status</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                    <tr data-id="{{ $page->id }}">
                        <td>
                            <input type="checkbox" name="ids[]" value="{{ $page->id }}" class="rowCheckbox">
                        </td>
                        <td class="reorder-handle" style="cursor:move;">☰</td>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $page->page_title }}</td>
                        <td>{{ $page->seo_url }}</td>
                        <td>
                            <span class="badge {{ $page->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $page->status ? 'Enabled' : 'Disabled' }}
                            </span>
                        </td>
                        <td>
                            @if($page->media)
                                <img src="{{ asset($page->media) }}" width="50" alt="{{ $page->page_title }}">
                            @elseif($page->banner_image)
                                <img src="{{ asset($page->banner_image) }}" width="50" alt="{{ $page->page_title }}">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-warning btn-sm">✏ Edit</a>

                            {{-- Single delete form --}}
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" 
                                  method="POST" 
                                  id="page-{{$page->id}}"
                                  class="d-inline single-delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm delete-btn">🗑 Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>

{{-- JS init --}}
<script>
    $(document).ready(function () {
        initDataTableWithFeatures({
            tableSelector: '#pagesTable',
            bulkDeleteBtnSelector: '#bulk-delete-btn',
            selectAllSelector: '#selectAll',
            rowHandleSelector: 'td.reorder-handle',
            reorderUrl: '{{ route("admin.pages.reorder") }}',
            bulkDeleteUrl: '{{ route("admin.pages.bulk-delete") }}',
            csrfToken: '{{ csrf_token() }}'
        });
        
    });

</script>
@endsection
