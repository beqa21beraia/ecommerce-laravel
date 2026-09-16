@extends('adminlte::page')

@section('title', 'Admin — ' . config('app.name'))

@section('content_header')
    @yield('content_header')
@endsection

@section('content')
    @yield('content')
@endsection
