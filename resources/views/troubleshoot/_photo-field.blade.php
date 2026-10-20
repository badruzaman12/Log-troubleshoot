<div class="photo-field mb-4" data-photo-picker>
    <label for="foto" class="form-label">Foto pendukung <span class="optional-label">Opsional</span></label>
    <div class="photo-actions">
        <label for="foto" class="btn btn-outline-secondary photo-file-button">
            <span aria-hidden="true">＋</span> Pilih foto
            <input type="file" name="foto" id="foto" class="visually-hidden" accept="image/*" capture="environment" data-photo-input>
        </label>
        <button type="button" class="btn btn-outline-secondary" data-camera-open>
            <span aria-hidden="true">◉</span> Buka kamera
        </button>
    </div>
    <p class="form-hint">Format gambar, maksimal 5 MB.</p>

    <div class="photo-feedback" data-photo-feedback role="status" aria-live="polite" hidden></div>
    <div class="photo-preview" data-photo-preview-wrap hidden>
        <img src="" alt="Pratinjau foto yang dipilih" data-photo-preview>
        <span>Foto baru dipilih</span>
    </div>

    @if(!empty($currentPhoto))
        <div class="photo-preview current-photo" data-current-photo>
            <img src="{{ asset('storage/'.$currentPhoto) }}" alt="Foto saat ini">
            <span>Foto saat ini</span>
        </div>
    @endif

    @error('foto')
        <div class="field-error">{{ $message }}</div>
    @enderror

    <dialog class="camera-dialog" data-camera-dialog aria-labelledby="camera-title">
        <div class="camera-header">
            <div>
                <p class="eyebrow">FOTO PENDUKUNG</p>
                <h2 id="camera-title">Ambil foto</h2>
            </div>
            <button type="button" class="camera-close" data-camera-close aria-label="Tutup kamera">×</button>
        </div>
        <video class="camera-video" data-camera-video autoplay playsinline></video>
        <p class="camera-error" data-camera-error role="alert" hidden></p>
        <div class="camera-actions">
            <button type="button" class="btn btn-outline-secondary" data-camera-close>Batal</button>
            <button type="button" class="btn btn-primary" data-camera-capture>Ambil foto</button>
        </div>
    </dialog>
</div>