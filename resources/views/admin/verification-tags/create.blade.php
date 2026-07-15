@extends('admin.layouts.app')

@section('title', 'Add Meta / Script Tag')

@section('content')
    @include('admin.verification-tags.form', [
        'tag' => $tag,
        'action' => route('admin.verification-tags.store'),
        'method' => 'POST',
        'heading' => 'Add Meta / Script Tag',
    ])
@endsection

