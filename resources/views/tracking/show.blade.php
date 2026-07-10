@extends('layouts.landing')

@push('styles')
<style>
    body { background-color: #f8f9fa; }
    
    .tracking-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        background: #fff;
        margin-bottom: 24px;
        overflow: hidden;
    }
    
    /* Stepper Styling */
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        position: relative;
    }
    .stepper-item {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        z-index: 1;
    }
    .stepper-item::before {
        position: absolute;
        content: "";
        border-bottom: 3px solid #e9ecef;
        width: 100%;
        top: 15px;
        left: -50%;
        z-index: -1;
    }
    .stepper-item:first-child::before { content: none; }
    
    .step-counter {
        position: relative;
        z-index: 5;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid #e9ecef;
        color: #adb5bd;
        margin-bottom: 8px;
    }
    
    .stepper-item.completed .step-counter {
        background-color: #20c997;
        border-color: #20c997;
        color: white;
    }
    .stepper-item.completed::before {
        border-color: #20c997;
    }
    .stepper-item.active .step-counter {
        border-color: #20c997;
        color: #20c997;
    }
    .stepper-item.active .step-counter::after {
        content: "";
        width: 12px;
        height: 12px;
        background-color: #20c997;
        border-radius: 50%;
        position: absolute;
    }
    .step-name {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6c757d;
    }
    .stepper-item.completed .step-name, .stepper-item.active .step-name {
        color: #212529;
    }

    /* Timeline list styling */
    .timeline {
        border-left: 2px solid #e9ecef;
        padding-left: 20px;
        margin-left: 15px;
        margin-top: 30px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
    }
    .timeline-item::before {
        content: "";
        position: absolute;
        left: -27px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e9ecef;
        border: 2px solid #fff;
    }
    .timeline-item:first-child::before {
        background: #20c997;
    }
    .timeline-date {
        font-size: 0.75rem;
        color: #adb5bd;
        position: absolute;
        left: -75px;
        top: -2px;
        width: 50px;
        text-align: right;
    }

    /* Secure Channel */
    .chat-container {
        height: 350px;
        overflow-y: auto;
        padding-right: 10px;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .chat-message {
        max-width: 80%;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 0.9rem;
        position: relative;
    }
    .chat-investigator {
        background: #f1f3f5;
        color: #212529;
        align-self: flex-start;
        border-bottom-left-radius: 2px;
    }
    .chat-pelapor {
        background: #0b1a30;
        color: #fff;
        align-self: flex-end;
        border-bottom-right-radius: 2px;
    }
    .chat-time {
        font-size: 0.65rem;
        opacity: 0.7;
        margin-top: 5px;
        text-align: right;
    }
    
    /* Input Area */
    .chat-input-wrapper {
        position: relative;
        margin-top: 15px;
    }
    .chat-input {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 45px 12px 15px;
        width: 100%;
        resize: none;
        font-size: 0.9rem;
    }
    .chat-send-btn {
        position: absolute;
        right: 8px;
        bottom: 8px;
        background: #20c997;
        color: white;
        border: none;
        border-radius: 6px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    /* Evidence List */
    .evidence-item {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .evidence-icon {
        width: 40px;
        height: 40px;
        background: #f1f3f5;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #dc3545;
        font-size: 1.2rem;
    }

    /* Security Policy */
    .security-policy {
        background: #0b1a30;
        color: white;
        border-radius: 12px;
        padding: 24px;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header Card -->
    <div class="tracking-card p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div class="text-muted small fw-medium text-uppercase mb-1">Report ID</div>
            <h2 class="h4 mb-0 fw-bold">#{{ $laporan->nomor_registrasi }}</h2>
        </div>
        <div class="d-flex align-items-center gap-4">
            <div class="text-end">
                <span class="badge bg-{{ $laporan->status_color }}-subtle text-{{ $laporan->status_color }} px-3 py-2 rounded-pill d-flex align-items-center">
                    <i class="bi bi-circle-fill me-2" style="font-size: 0.5rem;"></i> {{ $laporan->status_label }}
                </span>
            </div>
            <div class="text-end border-start ps-4 d-none d-md-block">
                <div class="text-muted small fw-medium">Last Updated</div>
                <div class="fw-semibold">{{ $laporan->updated_at->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-7">
            
            <!-- Process Status -->
            <div class="tracking-card p-4">
                <h5 class="fw-bold mb-4">Process Status</h5>
                
                @php
                    $steps = ['menunggu', 'verifikasi', 'investigasi', 'selesai'];
                    $currentIdx = array_search($laporan->status, $steps);
                    if($currentIdx === false) $currentIdx = 0; // fallback if ditolak
                @endphp

                <div class="stepper-wrapper">
                    <div class="stepper-item {{ $currentIdx >= 0 ? 'completed' : '' }}">
                        <div class="step-counter"><i class="bi bi-check-lg"></i></div>
                        <div class="step-name">Received</div>
                    </div>
                    <div class="stepper-item {{ $currentIdx >= 1 ? 'completed' : '' }} {{ $currentIdx == 0 ? 'active' : '' }}">
                        <div class="step-counter">{!! $currentIdx >= 1 ? '<i class="bi bi-check-lg"></i>' : '' !!}</div>
                        <div class="step-name">Under Review</div>
                    </div>
                    <div class="stepper-item {{ $currentIdx >= 2 ? 'completed' : '' }} {{ $currentIdx == 1 ? 'active' : '' }}">
                        <div class="step-counter">{!! $currentIdx >= 2 ? '<i class="bi bi-check-lg"></i>' : '' !!}</div>
                        <div class="step-name">Investigation</div>
                    </div>
                    <div class="stepper-item {{ $currentIdx >= 3 ? 'completed' : '' }} {{ $currentIdx == 2 ? 'active' : '' }}">
                        <div class="step-counter"><i class="bi bi-clock"></i></div>
                        <div class="step-name">Resolved</div>
                    </div>
                </div>

                @if($laporan->timelines->count() > 0)
                    <div class="timeline ps-md-5 ms-md-4">
                        @foreach($laporan->timelines->sortByDesc('created_at') as $tl)
                            <div class="timeline-item">
                                <div class="timeline-date d-none d-md-block">{{ $tl->created_at->format('M d') }}</div>
                                <h6 class="fw-bold mb-1">{{ $tl->title }}</h6>
                                <p class="text-muted small mb-0">{{ $tl->description }}</p>
                                <div class="text-muted small d-md-none mt-1"><i class="bi bi-calendar3 me-1"></i>{{ $tl->created_at->format('M d, Y') }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <p class="mb-0">Belum ada riwayat proses.</p>
                    </div>
                @endif
            </div>

            <!-- Secure Channel -->
            <div class="tracking-card p-0 d-flex flex-column" style="height: 500px;">
                <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-light">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">Secure Channel</h6>
                            <small class="text-success fw-medium"><i class="bi bi-shield-check me-1"></i>Anonymity Guard Active</small>
                        </div>
                    </div>
                    <i class="bi bi-info-circle text-muted" role="button" title="Pesan Anda dienkripsi dan anonim"></i>
                </div>
                
                <div class="p-4 flex-grow-1 chat-container">
                    @forelse($laporan->messages as $msg)
                        @if($msg->sender_type == 'investigator')
                            <div class="chat-message chat-investigator">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <span class="fw-bold small">Investigator</span>
                                </div>
                                <div>{{ $msg->content }}</div>
                                <div class="chat-time">{{ $msg->created_at->format('h:i A') }}</div>
                            </div>
                        @else
                            <div class="chat-message chat-pelapor">
                                <div>{{ $msg->content }}</div>
                                <div class="chat-time">{{ $msg->created_at->format('h:i A') }}</div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-muted my-auto">
                            <i class="bi bi-chat-dots fs-1 d-block mb-2 opacity-50"></i>
                            <p class="small">Belum ada pesan. Anda dapat mengirim pesan atau keterangan tambahan di sini secara anonim.</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="p-3 border-top bg-white">
                    <form action="{{ route('track.message.store', $laporan->tracking_token) }}" method="POST" id="chatForm">
                        @csrf
                        <div class="chat-input-wrapper">
                            <input type="text" name="message" id="messageInput" class="chat-input" placeholder="Type a secure message..." required autocomplete="off">
                            <button type="submit" class="chat-send-btn">
                                <i class="bi bi-send-fill"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Right Column -->
        <div class="col-lg-5">
            
            <!-- Evidence -->
            <div class="tracking-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Evidence</h5>
                    <button class="btn btn-sm btn-link text-decoration-none p-0 fw-medium" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        + Add New
                    </button>
                </div>

                @if($laporan->buktis->count() > 0)
                    @foreach($laporan->buktis as $bukti)
                        <div class="evidence-item">
                            <div class="evidence-icon">
                                @php
                                    $ext = strtolower(pathinfo($bukti->nama_asli, PATHINFO_EXTENSION));
                                    $icon = 'file-earmark-text';
                                    $color = '#6c757d';
                                    if(in_array($ext, ['pdf'])) { $icon = 'file-earmark-pdf'; $color = '#dc3545'; }
                                    elseif(in_array($ext, ['jpg','jpeg','png'])) { $icon = 'file-earmark-image'; $color = '#0d6efd'; }
                                    elseif(in_array($ext, ['doc','docx'])) { $icon = 'file-earmark-word'; $color = '#0dcaf0'; }
                                @endphp
                                <i class="bi bi-{{ $icon }}" style="color: {{ $color }}"></i>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-medium text-truncate" title="{{ $bukti->nama_asli }}">{{ $bukti->nama_asli }}</div>
                                <small class="text-muted">Diunggah: {{ $bukti->created_at->format('M d, Y H:i') }}</small>
                            </div>
                            <a href="{{ Storage::url($bukti->path_file) }}" target="_blank" class="btn btn-sm text-muted">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted small">Tidak ada bukti tambahan yang dilampirkan.</div>
                @endif
            </div>

            <!-- Security Policy -->
            <div class="security-policy mb-4 shadow-sm">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-shield-check fs-4 text-info"></i>
                    <h5 class="mb-0 fw-bold">Security Policy</h5>
                </div>
                <p class="small" style="line-height: 1.6; color: #e9ecef;">
                    Your identity remains encrypted and unknown even to the investigators unless you choose to disclose it. Our zero-knowledge protocols ensure maximum whistleblower protection.
                </p>
                <a href="#" class="text-info text-decoration-none small d-inline-flex align-items-center mt-2">
                    Learn more about encryption <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.75rem;"></i>
                </a>
            </div>

            <!-- Need Help -->
            <div class="tracking-card p-4">
                <h5 class="fw-bold mb-3">Need Help?</h5>
                <div class="list-group list-group-flush">
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3 text-muted">
                        How long does an investigation take?
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-3 text-muted">
                        Who will see my information?
                        <i class="bi bi-chevron-right small"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title fw-bold">Add Evidence</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('track.evidence.store', $laporan->tracking_token) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
                <label class="form-label text-muted small fw-medium">Upload File (Max 10MB)</label>
                <input type="file" class="form-control" name="file" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                <div class="form-text">Format: PDF, JPG, PNG, DOCX</div>
            </div>
          </div>
          <div class="modal-footer border-top-0">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Upload Evidence</button>
          </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatContainer = document.querySelector('.chat-container');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const fetchUrl = '{{ route('track.messages.fetch', $laporan->tracking_token) }}';
    
    // Auto-scroll to bottom
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    scrollToBottom();

    // Fetch messages periodically
    setInterval(async () => {
        try {
            const res = await fetch(fetchUrl);
            const data = await res.json();
            
            if (data.messages && data.messages.length > 0) {
                renderMessages(data.messages);
            }
        } catch (e) {
            console.error('Error fetching messages', e);
        }
    }, 5000); // 5 seconds polling

    function renderMessages(messages) {
        // Clear container except the "no messages" placeholder if needed
        chatContainer.innerHTML = '';
        
        messages.forEach(msg => {
            let html = '';
            if (msg.sender_type === 'investigator') {
                html = `
                <div class="chat-message chat-investigator">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 24px; height: 24px; font-size: 0.7rem;">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <span class="fw-bold small">Investigator</span>
                    </div>
                    <div>${msg.content}</div>
                    <div class="chat-time">${msg.time}</div>
                </div>`;
            } else {
                html = `
                <div class="chat-message chat-pelapor">
                    <div>${msg.content}</div>
                    <div class="chat-time">${msg.time}</div>
                </div>`;
            }
            chatContainer.innerHTML += html;
        });
        scrollToBottom();
    }

    // Handle AJAX Form Submission
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const content = messageInput.value.trim();
        if(!content) return;

        // Add dummy message temporarily for immediate feedback
        const tempHtml = `
            <div class="chat-message chat-pelapor opacity-50">
                <div>${content}</div>
                <div class="chat-time">Sending...</div>
            </div>`;
        chatContainer.innerHTML += tempHtml;
        scrollToBottom();
        
        const btn = chatForm.querySelector('button');
        btn.disabled = true;

        try {
            const formData = new FormData(chatForm);
            const res = await fetch(chatForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            if (data.success) {
                messageInput.value = '';
                // Fetch full messages immediately
                const msgRes = await fetch(fetchUrl);
                const msgData = await msgRes.json();
                renderMessages(msgData.messages);
            }
        } catch (e) {
            console.error('Error sending message', e);
        } finally {
            btn.disabled = false;
            messageInput.focus();
        }
    });
});
</script>
@endpush
@endsection
