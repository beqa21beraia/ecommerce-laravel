@extends('adminlte::page')

@section('title', 'Admin Login')

@section('content')
    <div class="container mt-5" style="max-width: 400px;">
        <h3>Admin Login</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf

            <div class="mb-3">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
@endsection
