@extends('layouts.app')
@section('title', 'Add course')

@section('content')
<h1 class="page-title mb-5">Add course</h1>
@include('admin.Course.form')
@endsection