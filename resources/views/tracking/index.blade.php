@extends('layouts.landing')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 70vh;">
    
    <div class="text-center mb-5">
        <h1 class="fw-bold text-dark mb-3">Track Your Report</h1>
        <p class="text-muted" style="max-width: 500px; margin: 0 auto;">
            Enter your access code provided at the time of submission to check your status and communicate with investigators.
        </p>
    </div>

    <div class="card border-0 shadow-sm" style="width: 100%; max-width: 550px; border-radius: 12px;">
        <div class="card-body p-4 p-md-5">
            
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form action="{{ route('track.search') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="access_code" class="form-label text-muted small fw-medium">Access Code</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="access_code" name="access_code" placeholder="Enter Access Code" required autocomplete="off" style="font-family: monospace; letter-spacing: 1px;">
                    </div>
                </div>

                <button type="submit" class="btn w-100 py-3 text-white fw-medium" style="background-color: #0b1a30; border-radius: 8px;">
                    View Report Status <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
