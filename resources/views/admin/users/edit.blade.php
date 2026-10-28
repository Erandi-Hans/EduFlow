@extends('layouts.app')
@section('title', 'Edit user')

@section('content')
<h1 class="page-title mb-5">Edit {{ strtolower($role) }}</h1>
@include('admin.users.form')
@endsection