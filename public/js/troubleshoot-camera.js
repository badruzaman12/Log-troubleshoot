document.querySelectorAll('[data-photo-picker]').forEach((picker) => {
    const input = picker.querySelector('[data-photo-input]');
    const photoPreviews = picker.querySelector('[data-photo-previews]');
    const videoInput = picker.querySelector('[data-video-input]');
    const videoPreview = picker.querySelector('[data-video-preview]');
    const currentVideo = picker.querySelector('[data-current-video]');
    const feedback = picker.querySelector('[data-photo-feedback]');
    const dialog = picker.querySelector('[data-camera-dialog]');
    const video = picker.querySelector('[data-camera-video]');
    const cameraError = picker.querySelector('[data-camera-error]');
    const existingCount = Number(picker.dataset.existingCount || 0);
    let cameraStream;
    let selectedPhotos = [];
    let previewUrls = [];
    let videoPreviewUrl;

    const stopCamera = () => {
        cameraStream?.getTracks().forEach((track) => track.stop());
        cameraStream = undefined;
        video.srcObject = null;
    };

    const showPhotoPreviews = (files) => {
        previewUrls.forEach((url) => URL.revokeObjectURL(url));
        previewUrls = [];
        photoPreviews.replaceChildren();

        files.forEach((file, index) => {
            const url = URL.createObjectURL(file);
            previewUrls.push(url);
            const figure = document.createElement('figure');
            const image = document.createElement('img');
            const caption = document.createElement('figcaption');

            image.src = url;
            image.alt = `Pratinjau foto ${index + 1}`;
            caption.textContent = `Foto baru ${index + 1}`;
            figure.className = 'media-thumb';
            figure.append(image, caption);
            photoPreviews.append(figure);
        });

        photoPreviews.hidden = files.length === 0;
        if (existingCount + files.length > 8) {
            feedback.textContent = 'Maksimal 8 foto per catatan, termasuk foto yang sudah tersimpan.';
            feedback.hidden = false;
        } else {
            feedback.hidden = true;
        }
    };

    const syncPhotoInput = () => {
        const transfer = new DataTransfer();
        selectedPhotos.forEach((file) => transfer.items.add(file));
        input.files = transfer.files;
    };

    const appendPhotoFiles = (files) => {
        const combined = [...selectedPhotos];

        files.forEach((file) => {
            const duplicate = combined.some((selected) =>
                selected.name === file.name &&
                selected.size === file.size &&
                selected.lastModified === file.lastModified
            );

            if (!duplicate) combined.push(file);
        });

        if (existingCount + combined.length > 8) {
            feedback.textContent = 'Maksimal 8 foto per catatan, termasuk foto yang sudah tersimpan.';
            feedback.hidden = false;
            syncPhotoInput();
            return;
        }

        selectedPhotos = combined;
        syncPhotoInput();
        showPhotoPreviews(selectedPhotos);
    };

    input.addEventListener('change', () => appendPhotoFiles(Array.from(input.files)));

    videoInput.addEventListener('change', () => {
        const file = videoInput.files[0];
        if (!file) return;

        if (videoPreviewUrl) URL.revokeObjectURL(videoPreviewUrl);
        videoPreviewUrl = URL.createObjectURL(file);
        videoPreview.src = videoPreviewUrl;
        videoPreview.hidden = false;
        if (currentVideo) currentVideo.hidden = true;
    });

    picker.querySelector('[data-camera-open]').addEventListener('click', async () => {
        cameraError.hidden = true;

        if (!navigator.mediaDevices?.getUserMedia) {
            feedback.textContent = 'Akses kamera tidak tersedia. Gunakan tombol Pilih beberapa foto.';
            feedback.hidden = false;
            return;
        }

        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: { facingMode: { ideal: 'environment' } },
            });
            video.srcObject = cameraStream;
            await video.play();
            dialog.showModal();
        } catch (error) {
            stopCamera();
            cameraError.textContent = error.name === 'NotAllowedError'
                ? 'Izin kamera ditolak. Izinkan akses kamera di browser lalu coba lagi.'
                : 'Kamera tidak dapat dibuka. Pastikan kamera tersedia dan halaman memakai HTTPS atau localhost.';
            cameraError.hidden = false;
            dialog.showModal();
        }
    });

    picker.querySelectorAll('[data-camera-close]').forEach((button) => {
        button.addEventListener('click', () => dialog.close());
    });

    dialog.addEventListener('close', stopCamera);

    picker.querySelector('[data-camera-capture]').addEventListener('click', () => {
        if (!video.videoWidth || !video.videoHeight) {
            cameraError.textContent = 'Kamera belum siap. Tunggu sebentar lalu coba lagi.';
            cameraError.hidden = false;
            return;
        }

        if (existingCount + selectedPhotos.length >= 8) {
            cameraError.textContent = 'Maksimal 8 foto per catatan, termasuk foto yang sudah tersimpan.';
            cameraError.hidden = false;
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        canvas.toBlob((blob) => {
            if (!blob) {
                cameraError.textContent = 'Foto gagal diambil. Silakan coba lagi.';
                cameraError.hidden = false;
                return;
            }

            selectedPhotos.push(new File([blob], `foto-${Date.now()}.jpg`, { type: 'image/jpeg' }));
            syncPhotoInput();
            showPhotoPreviews(selectedPhotos);
            dialog.close();
        }, 'image/jpeg', 0.9);
    });

    window.addEventListener('pagehide', () => {
        stopCamera();
        previewUrls.forEach((url) => URL.revokeObjectURL(url));
        if (videoPreviewUrl) URL.revokeObjectURL(videoPreviewUrl);
    });
});