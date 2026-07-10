@extends('layouts.landing')

@section('title', 'Laporan Berhasil Dikirim - WBS BBSPJIKKP')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/laporan.css') }}">
<style>
    body {
        background-color: #f8fafc;
    }
    
    .confetti-bg {
        position: absolute;
        top: 0; left: 0; right: 0; height: 350px;
        background-image: 
            radial-gradient(circle at 15% 50%, rgba(139, 92, 246, 0.4) 4px, transparent 5px),
            radial-gradient(circle at 85% 30%, rgba(244, 63, 94, 0.4) 4px, transparent 5px),
            radial-gradient(circle at 50% 20%, rgba(16, 185, 129, 0.4) 5px, transparent 6px),
            radial-gradient(circle at 75% 60%, rgba(59, 130, 246, 0.4) 4px, transparent 5px),
            radial-gradient(circle at 25% 70%, rgba(245, 158, 11, 0.4) 5px, transparent 6px);
        background-size: 100% 100%;
        opacity: 0.6;
        z-index: 0;
        pointer-events: none;
    }

    .success-icon-container {
        position: relative;
        z-index: 1;
        margin-bottom: 1.5rem;
        display: inline-block;
    }
    
    .success-icon-circle {
        width: 80px; height: 80px;
        background: #10b981;
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.5);
    }
    
    .success-icon-circle i {
        color: white; font-size: 2.5rem;
    }
    
    .code-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 2.5rem 2rem;
        height: 100%;
        display: flex; flex-direction: column; justify-content: center;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    
    .code-label {
        font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .code-value {
        font-size: 3rem; font-weight: 800; color: #0f172a; letter-spacing: 1px; line-height: 1.1;
    }
    
    .copy-btn {
        background: #eff6ff; color: #3b82f6; border: none; border-radius: 8px; width: 44px; height: 44px;
        display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
        transition: all 0.2s;
    }
    
    .copy-btn:hover { background: #dbeafe; }
    
    .encrypted-card {
        background: #0f172a;
        border-radius: 12px;
        padding: 2.5rem 2rem;
        height: 100%;
        color: white;
        display: flex; flex-direction: column; justify-content: center;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.4);
    }
    
    .encrypted-icon {
        color: #10b981; font-size: 1.5rem; margin-bottom: 1rem;
    }
    
    .action-btn-dark {
        background: #0f172a; color: white; font-weight: 600; border-radius: 8px; padding: 0.8rem 1.5rem; border: none;
        box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.2); transition: all 0.2s;
    }
    
    .action-btn-dark:hover { background: #1e293b; color: white; }
    
    .action-btn-outline {
        background: transparent; color: #3b82f6; font-weight: 600; border-radius: 8px; padding: 0.8rem 1.5rem; border: 1px solid #bfdbfe; transition: all 0.2s;
    }
    
    .action-btn-outline:hover { background: #eff6ff; }
    
    .steps-container {
        background: #f8fafc;
        border-radius: 16px;
        padding: 2.5rem;
        margin-top: 1rem;
        text-align: left;
    }
    
    .step-item {
        display: flex; gap: 1.25rem; margin-bottom: 1.5rem; align-items: flex-start;
    }
    
    .step-number {
        width: 32px; height: 32px; border-radius: 50%; background: #93c5fd; color: #1e3a8a;
        display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; font-size: 0.875rem;
    }
    
    .step-content h5 { font-weight: 700; color: #1e293b; margin-bottom: 0.25rem; font-size: 1rem; }
    .step-content p { color: #475569; font-size: 0.9rem; margin-bottom: 0; line-height: 1.5; }
</style>
@endpush

@section('content')
<div class="position-relative pt-5 pb-5 mt-4">
    <div class="confetti-bg"></div>
    
    <div class="container position-relative z-index-1">
        <div class="text-center mb-5">
            <div class="success-icon-container">
                <div class="success-icon-circle">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>
            
            <h1 class="display-5 fw-bold text-dark mb-3">Thank You</h1>
            <p class="text-muted mx-auto" style="max-width: 550px; font-size: 1rem; line-height: 1.6;">
                Your report has been successfully submitted. Your identity remains 100% anonymous through our end-to-end encrypted integrity protocols.
            </p>
        </div>
        
        <div class="row justify-content-center mb-4 g-4">
            <!-- Left Card -->
            <div class="col-lg-5 col-md-7">
                <div class="code-card">
                    <div class="text-center mb-4">
                        <div class="code-label">Your unique access code</div>
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <div class="code-value">{{ $nomor }}</div>
                            <button class="copy-btn shadow-sm" onclick="copyNomor()" id="copyBtn">
                                <i class="bi bi-files"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="text-warning text-center small fw-semibold d-flex align-items-center justify-content-center gap-2 px-3">
                        <i class="bi bi-exclamation-triangle"></i> Store this code safely. It is the ONLY way to track your report.
                    </div>
                </div>
            </div>
            
            <!-- Right Card -->
            <div class="col-lg-3 col-md-5">
                <div class="encrypted-card">
                    <div class="encrypted-icon"><i class="bi bi-shield-lock-fill"></i></div>
                    <h5 class="fw-bold mb-3">Encrypted</h5>
                    <p class="text-white-50 small mb-0 lh-lg">Metadata scrubbed. IP address masked.<br>Secure transmission verified.</p>
                </div>
            </div>
        </div>
        
        <!-- Buttons -->
        <div class="d-flex justify-content-center gap-3 mb-5 flex-wrap">
            <button class="action-btn-dark d-flex align-items-center gap-2" onclick="window.print()">
                <i class="bi bi-download"></i> Download PDF Confirmation
            </button>
            <button class="action-btn-outline d-flex align-items-center gap-2" onclick="window.print()">
                <i class="bi bi-printer"></i> Print Report Summary
            </button>
        </div>
        
        <!-- Next Steps -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <div class="steps-container shadow-sm border border-light">
                    <h4 class="fw-bold mb-4 text-dark fs-5">Next Steps in the Integrity Process</h4>
                    
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h5>Initial Triage</h5>
                            <p>An independent ombudsman will review your submission within 48 business hours to determine the appropriate investigative channel.</p>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h5>Status Updates</h5>
                            <p>Use your access code above on the "Track" page to check for progress or respond to clarifying questions from the investigator.</p>
                        </div>
                    </div>
                    
                    <div class="step-item mb-0">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h5>Resolution</h5>
                            <p>Upon conclusion, a final summary of the findings and actions taken will be posted to your secure portal.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyNomor() {
    const nomor = '{{ $nomor }}';
    navigator.clipboard.writeText(nomor).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.innerHTML = '<i class="bi bi-check-lg"></i>';
        btn.classList.add('bg-success', 'text-white');
        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-files"></i>';
            btn.classList.remove('bg-success', 'text-white');
        }, 2000);
    });
}
</script>
@endpush
