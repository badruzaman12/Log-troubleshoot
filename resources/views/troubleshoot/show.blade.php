<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Masalah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/troubleshoot.css') }}" rel="stylesheet">
</head>
<body class="troubleshoot-page">
    <main class="detail-shell">
        <article class="surface detail-card">
            <header class="detail-heading">
                <div>
                    <p class="eyebrow">DETAIL TROUBLESHOOT</p>
                    <h1>{{ $troubleshoot->judul_masalah }}</h1>
                    <div class="d-flex align-items-center flex-wrap gap-2 mt-3">
                        <span class="badge category-tag">{{ $troubleshoot->kategori }}</span>
                        <span class="detail-meta">Dibuat {{ $troubleshoot->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <a href="{{ route('troubleshoot.edit', $troubleshoot) }}" class="btn btn-outline-secondary">Edit catatan</a>
            </header>

                @php
                    $photos = collect([$troubleshoot->foto])->merge($troubleshoot->fotos ?? [])->filter();
                @endphp

                @if($photos->isNotEmpty() || $troubleshoot->video)
                    <section class="detail-media" aria-label="Media pendukung">
                        @if($photos->isNotEmpty())
                            <div class="detail-photo-grid">
                                @foreach($photos as $photo)
                                    <a href="{{ asset('storage/'.$photo) }}" target="_blank" rel="noopener" class="detail-photo-link">
                                        <img src="{{ asset('storage/'.$photo) }}" alt="Foto {{ $troubleshoot->judul_masalah }}" class="detail-photo">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                        @if($troubleshoot->video)
                            <div class="detail-video-wrap">
                                <h2>Video pendukung</h2>
                                <video class="detail-video" src="{{ asset('storage/'.$troubleshoot->video) }}" controls preload="metadata"></video>
                            </div>
                        @endif
                    </section>
                @endif

                @if($troubleshoot->gejala)
                    <section class="detail-section">
                        <h2>Gejala atau pesan error</h2>
                        <p class="detail-copy font-monospace">{{ $troubleshoot->gejala }}</p>
                    </section>
                @endif

                @if($troubleshoot->solusi)
                    <section class="detail-section">
                        <h2>Solusi</h2>
                        <p class="detail-copy">{{ $troubleshoot->solusi }}</p>
                    </section>
                @endif

                @if($troubleshoot->catatan)
                    <section class="detail-section">
                        <h2>Catatan tambahan</h2>
                        <p class="detail-copy">{{ $troubleshoot->catatan }}</p>
                    </section>
                @endif

                <footer class="detail-footer">
                    <a href="{{ route('troubleshoot.index') }}" class="btn btn-outline-secondary">Kembali ke daftar</a>
                </footer>
        </article>
    </main>
</body>
</html>