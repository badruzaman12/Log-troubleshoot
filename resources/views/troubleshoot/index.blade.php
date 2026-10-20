<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Masalah & Solusi (Troubleshoot Log)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/troubleshoot.css') }}" rel="stylesheet">
</head>
<body class="troubleshoot-page">
    <main class="page-shell index-shell">
        <header class="page-header index-header">
            <div class="index-title-wrap">
                <div class="index-kicker">
                    <span class="index-kicker-mark" aria-hidden="true">KB</span>
                    <p class="eyebrow">KNOWLEDGE BASE <span>/</span> TROUBLESHOOT</p>
                </div>
                <h1>Log troubleshoot</h1>
                <p class="index-description">Pusat catatan kendala, gejala, dan solusi teknis.</p>
                <div class="index-meta">
                    <span class="status-dot" aria-hidden="true"></span>
                    <span>{{ $troubleshoots->total() }} catatan tersimpan</span>
                </div>
            </div>
            <a href="{{ route('troubleshoot.create') }}" class="btn btn-primary index-add-button">
                <span class="add-mark" aria-hidden="true">+</span>
                <span>Tambah catatan</span>
            </a>
        </header>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <section class="index-controls" aria-label="Pencarian catatan">
            <div class="index-section-heading">
                <div>
                    <h2>Semua catatan</h2>
                    <p>Telusuri masalah dan solusi yang pernah dicatat.</p>
                </div>
                @if($troubleshoots->total())
                    <span class="result-count">{{ $troubleshoots->firstItem() }}–{{ $troubleshoots->lastItem() }} <span>dari</span> {{ $troubleshoots->total() }}</span>
                @endif
            </div>
            <form action="{{ route('troubleshoot.index') }}" method="GET" class="search-form index-search">
                <label class="search-box">
                    <span class="search-glyph" aria-hidden="true">⌕</span>
                    <input type="search" name="search" class="form-control" placeholder="Cari judul, kategori, gejala..." value="{{ $search ?? '' }}" aria-label="Cari catatan troubleshoot">
                </label>
                <button class="btn btn-primary search-submit" type="submit">Cari catatan</button>
                @if($search)
                    <a href="{{ route('troubleshoot.index') }}" class="btn btn-outline-secondary search-reset">Reset</a>
                @endif
                </form>
        </section>

        <section class="surface table-surface index-table-surface {{ $troubleshoots->isEmpty() ? 'index-table-empty' : '' }}" aria-label="Daftar catatan troubleshoot">
                <div class="table-responsive">
                    <table class="table table-hover align-middle troubleshoot-table">
                        <thead>
                            <tr>
                                <th class="row-number-heading">No.</th>
                                <th>Masalah</th>
                                <th>Kategori</th>
                                <th>Foto</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($troubleshoots as $key => $item)
                                <tr class="index-data-row">
                                    <td class="index-row-number">{{ str_pad($troubleshoots->firstItem() + $key, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="issue-cell">
                                        <a href="{{ route('troubleshoot.show', $item) }}" class="issue-link">
                                            <span class="issue-title">{{ $item->judul_masalah }}</span>
                                            <span class="issue-symptom">{{ Str::limit($item->gejala, 72) }}</span>
                                        </a>
                                    </td>
                                    <td><span class="badge category-tag">{{ $item->kategori }}</span></td>
                                    <td>
                                        @if($item->foto)
                                            <img src="{{ asset('storage/'.$item->foto) }}" alt="Foto {{ $item->judul_masalah }}" class="table-photo">
                                        @else
                                            <span class="no-photo" aria-label="Tidak ada foto">—</span>
                                        @endif
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-group">
                                        <a href="{{ route('troubleshoot.show', $item) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                        <a href="{{ route('troubleshoot.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form action="{{ route('troubleshoot.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="index-empty-row">
                                    <td colspan="5" class="empty-table-cell">
                                        <div class="empty-state">
                                            <span class="empty-mark" aria-hidden="true">+</span>
                                            <h3>{{ $search ? 'Tidak ada hasil yang cocok' : 'Belum ada catatan' }}</h3>
                                            <p>{{ $search ? 'Coba kata kunci lain atau reset pencarian.' : 'Catat masalah dan solusinya agar mudah ditemukan lagi.' }}</p>
                                            @if($search)
                                                <a href="{{ route('troubleshoot.index') }}" class="btn btn-outline-secondary">Reset pencarian</a>
                                            @else
                                                <a href="{{ route('troubleshoot.create') }}" class="btn btn-primary">Buat catatan pertama</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
        </section>

        <footer class="index-pagination">
            <p>{{ $troubleshoots->total() }} catatan</p>
            {{ $troubleshoots->withQueryString()->links() }}
        </footer>

        <footer class="index-footer">
            <div class="index-footer-brand">
                <span class="footer-brand-mark" aria-hidden="true">KB</span>
                <div>
                    <strong>Knowledge Base</strong>
                    <span>Catatan masalah & solusi teknis</span>
                </div>
            </div>
            <p class="index-footer-credit"><span>by</span> <strong>Ahmad Badruzaman</strong></p>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>