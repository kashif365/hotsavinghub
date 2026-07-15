@extends('admin.layouts.app')
@section('title', 'Create New Event')
@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Create New Event</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.events.form')
            </form>
        </div>
    </div>
</div>

@include('admin.partials.media-library-modal')
@endsection
