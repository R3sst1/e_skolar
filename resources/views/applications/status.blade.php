@extends('layouts.app')

@section('title', 'Application Status')

@section('content')
    @include('applications.status-partial', ['application' => $application])
@endsection