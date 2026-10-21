<div class="photo-field mb-4" data-photo-picker data-existing-count="{{ count($currentPhotos ?? []) }}">
    <label for="fotos" class="form-label">Foto pendukung <span class="optional-label">Opsional</span></label>
    <div class="photo-actions">
        <label for="fotos" class="btn btn-outline-secondary photo-file-button">
            <span aria-hidden="true">＋</span> Pilih beberapa foto
            <input type="file" name="fotos[]" id="fotos" class="visually-hidden" accept="image/*" data-photo-input multiple>
        </label>
        <button type="button" class="btn btn-outline-secondary" data-camera-open>
            <span aria-hidden="true">◉</span> Buka kamera
        </button>
    </div>
    <p class="form-hint">Pilih 1–8 foto sekaligus dari galeri. Maksimal 8 foto, masing-masing 4 MB.</p>

    <div class="photo-feedback" data-photo-feedback role="status" aria-live="polite" hidden></div>
    <div class="photo-preview-grid" data-photo-previews hidden></div>

    @if(!empty($currentPhotos))
        <div class="current-media-grid" data-current-photos>
            @foreach($currentPhotos as $currentPhoto)
                <figure class="media-thumb">
                    <img src="{{ asset('storage/'.$currentPhoto) }}" alt="Foto pendukung saat ini">
                    <figcaption>Foto tersimpan</figcaption>
                </figure>
            @endforeach
        </div>
    @endif

    @error('fotos')
        <div class="field-error">{{ $message }}</div>
    @enderror
    @error('fotos.0')
        <div class="field-error">{{ $message }}</div>
    @enderror

    <div class="video-upload">
        <label for="video" class="form-label">Video pendukung <span class="optional-label">Opsional</span></label>
        <input type="file" name="video" id="video" class="form-control" accept="video/mp4,video/webm,video/quicktime" data-video-input>
        <p class="form-hint">MP4, WebM, atau MOV. Maksimal 15 MB.</p>
        @error('video')
            <div class="field-error">{{ $message }}</div>
        @enderror
        @if(!empty($currentVideo))
            <video class="video-preview" src="{{ asset('storage/'.$currentVideo) }}" controls preload="metadata" data-current-video></video>
        @endif
        <video class="video-preview" controls preload="metadata" data-video-preview hidden></video>
    </div>

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