@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Admin Dashboard</h2>
        <p>Welcome, {{ Auth::user()->name }}!</p>
    </div>
@endsection
