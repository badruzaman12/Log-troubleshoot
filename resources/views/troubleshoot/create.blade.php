<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Masalah Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/troubleshoot.css') }}" rel="stylesheet">
</head>
<body class="troubleshoot-page">
    <main class="form-shell">
        <section class="surface form-card">
            <header class="form-heading">
                <p class="eyebrow">KNOWLEDGE BASE</p>
                <h1>Tambah catatan troubleshoot</h1>
                <p class="page-subtitle">Catat gejala dan solusi agar mudah ditemukan kembali.</p>
            </header>

                <form action="{{ route('troubleshoot.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-grid">
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <input type="text" name="kategori" id="kategori" class="form-control" placeholder="Contoh: Jaringan, Database, XAMPP, Hardware" required value="{{ old('kategori') }}">
                    </div>

                    <div class="mb-3">
                        <label for="judul_masalah" class="form-label">Judul masalah</label>
                        <input type="text" name="judul_masalah" id="judul_masalah" class="form-control" placeholder="Contoh: Error Koneksi Database MariaDB / Port 3306 Conflict" required value="{{ old('judul_masalah') }}">
                    </div>

                    <div class="mb-3 field-full">
                        <label for="gejala" class="form-label">Gejala atau pesan error</label>
                        <textarea name="gejala" id="gejala" rows="3" class="form-control" placeholder="Tuliskan pesan error atau gejala yang muncul di layar..." required>{{ old('gejala') }}</textarea>
                    </div>

                    <div class="mb-3 field-full">
                        <label for="solusi" class="form-label">Solusi atau cara penyelesaian</label>
                        <textarea name="solusi" id="solusi" rows="5" class="form-control" placeholder="Tuliskan langkah-langkah detail cara memperbaiki masalah ini..." required>{{ old('solusi') }}</textarea>
                    </div>

                    <div class="mb-3 field-full">
                        <label for="catatan" class="form-label">Catatan tambahan <span class="optional-label">Opsional</span></label>
                        <textarea name="catatan" id="catatan" rows="2" class="form-control" placeholder="Tips tambahan atau sumber referensi link...">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="field-full">
                        @include('troubleshoot._photo-field')
                    </div>
                    </div>

                    <div class="form-footer">
                        <a href="{{ route('troubleshoot.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan catatan</button>
                    </div>
                </form>
        </section>
    </main>
    <script src="{{ asset('js/troubleshoot-camera.js') }}" defer></script>
</body>
</html>