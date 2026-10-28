@extends('layouts.app')
@section('title', 'Edit course')

@section('content')
<h1 class="page-title mb-5">Edit course</h1>
@include('admin.Course.form')
@endsection