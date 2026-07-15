@extends('admin.layouts.app')
@section('title', 'Edit Event')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Edit Event</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.events.form')
            </form>
        </div>
    </div>
</div>

@include('admin.partials.media-library-modal')
@endsection
