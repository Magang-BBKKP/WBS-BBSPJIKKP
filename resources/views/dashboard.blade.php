@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                Welcome back, {{ auth()->user()->name }}!
                <br><br>
                <p class="text-muted small">Your last login was at: {{ auth()->user()->last_login_at }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
