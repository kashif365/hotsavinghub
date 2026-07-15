@extends('admin.layouts.app')

@section('title', 'Edit Meta / Script Tag')

@section('content')
    @include('admin.verification-tags.form', [
        'tag' => $tag,
        'action' => route('admin.verification-tags.update', $tag),
        'method' => 'PUT',
        'heading' => 'Edit Meta / Script Tag',
    ])
@endsection

