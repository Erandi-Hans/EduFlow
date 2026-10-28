@extends('layouts.app')
@section('title', 'New assignment')

@section('content')
<h1 class="page-title mb-5">New assignment</h1>
@include('teacher.assignments.form')
@endsection