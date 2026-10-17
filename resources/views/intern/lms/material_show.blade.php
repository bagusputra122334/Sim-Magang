@extends('layouts.app')
@section('title', $material->title)
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h5 mb-0 fw-bold text-slate-800 dark:text-slate-100">{{ $material->title }}</h1>
        <a href="{{ route('intern.lms.dashboard') }}" class="btn btn-secondary fw-semibold py-1.5 px-3 text-xs rounded-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border rounded-2 mb-3 overflow-hidden">
        <div class="card-body p-3 sm:p-4">
            <div class="mb-3">
                <h6 class="text-xs font-bold !text-slate-800 dark:!text-slate-200 uppercase tracking-wider mb-1.5">Deskripsi</h6>
                <div class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed">{!! nl2br(preg_replace('/(https?:\/\/[^\s]+)/', '<a href="$1" target="_blank" class="text-blue-600 dark:text-blue-400 underline font-semibold hover:text-blue-800 transition-colors" rel="noopener noreferrer">$1</a>', e($material->description))) !!}</div>
            </div>

            @if($videoId)
                <div class="mb-3 text-center">
                    <div id="youtube-player" class="rounded overflow-hidden shadow-sm inline-block max-w-full"></div>
                </div>
                
                <div class="text-center mt-2" id="status-container">
                    @if($progress->is_completed)
                        <span class="badge bg-success py-1 px-2.5 text-xs rounded-pill"><i class="bi bi-check-circle me-1"></i> Selesai Tonton</span>
                    @else
                        <span class="badge bg-warning text-dark py-1 px-2.5 text-xs rounded-pill" id="status-badge"><i class="bi bi-hourglass-split me-1"></i> Sedang Menonton...</span>
                    @endif
                </div>

                <script src="https://www.youtube.com/iframe_api"></script>
                <script>
                    var player;
                    function onYouTubeIframeAPIReady() {
                        player = new YT.Player('youtube-player', {
                            height: '360',
                            width: '640',
                            videoId: '{{ $videoId }}',
                            events: {
                                'onStateChange': onPlayerStateChange
                            }
                        });
                    }

                    function onPlayerStateChange(event) {
                        if (event.data == YT.PlayerState.ENDED) {
                            @if(!$progress->is_completed)
                                fetch("{{ route('intern.lms.material.complete', $material->id) }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                }).then(response => response.json())
                                  .then(data => {
                                      if(data.success) {
                                          document.getElementById('status-container').innerHTML = '<span class="badge bg-success py-1 px-2.5 text-xs rounded-pill"><i class="bi bi-check-circle me-1"></i> Selesai Tonton</span>';
                                      }
                                  });
                            @endif
                        }
                    }
                </script>
            @else
                <div class="text-center mt-3">
                    @if(!$progress->is_completed)
                        <form action="{{ route('intern.lms.material.complete', $material->id) }}" method="POST" id="manual-complete-form" onsubmit="event.preventDefault(); fetch(this.action, {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(r=>r.json()).then(d=>{if(d.success){location.reload();}})">
                            @csrf
                            <button type="submit" class="btn btn-success fw-bold py-1.5 px-3.5 text-xs rounded-2 shadow-sm"><i class="bi bi-check-circle me-1"></i> Tandai Selesai</button>
                        </form>
                    @else
                        <span class="badge bg-success py-1 px-2.5 text-xs rounded-pill"><i class="bi bi-check-circle me-1"></i> Selesai Tonton</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
