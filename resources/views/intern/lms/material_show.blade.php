@extends('layouts.app')
@section('title', $material->title)
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $material->title }}</h1>
        <a href="{{ route('intern.lms.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="mb-4">
                <h5>Deskripsi</h5>
                <p>{{ $material->description }}</p>
            </div>

            @if($videoId)
                <div class="mb-4 text-center">
                    <div id="youtube-player"></div>
                </div>
                
                <div class="text-center mt-3" id="status-container">
                    @if($progress->is_completed)
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Selesai Tonton</span>
                    @else
                        <span class="badge bg-warning text-dark fs-6" id="status-badge"><i class="bi bi-hourglass-split"></i> Sedang Menonton...</span>
                    @endif
                </div>

                <script src="https://www.youtube.com/iframe_api"></script>
                <script>
                    var player;
                    function onYouTubeIframeAPIReady() {
                        player = new YT.Player('youtube-player', {
                            height: '390',
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
                                          document.getElementById('status-container').innerHTML = '<span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Selesai Tonton</span>';
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
                            <button type="submit" class="btn btn-success"><i class="bi bi-check-circle"></i> Tandai Selesai</button>
                        </form>
                    @else
                        <span class="badge bg-success fs-6"><i class="bi bi-check-circle"></i> Selesai Tonton</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
