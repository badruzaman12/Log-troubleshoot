document.querySelectorAll('[data-photo-picker]').forEach((picker) => {
    const input = picker.querySelector('[data-photo-input]');
    const previewWrap = picker.querySelector('[data-photo-preview-wrap]');
    const preview = picker.querySelector('[data-photo-preview]');
    const currentPhoto = picker.querySelector('[data-current-photo]');
    const feedback = picker.querySelector('[data-photo-feedback]');
    const dialog = picker.querySelector('[data-camera-dialog]');
    const video = picker.querySelector('[data-camera-video]');
    const cameraError = picker.querySelector('[data-camera-error]');
    let cameraStream;
    let previewUrl;

    const stopCamera = () => {
        cameraStream?.getTracks().forEach((track) => track.stop());
        cameraStream = undefined;
        video.srcObject = null;
    };

    const showPreview = (file) => {
        if (!file) return;

        if (previewUrl) URL.revokeObjectURL(previewUrl);
        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        previewWrap.hidden = false;
        if (currentPhoto) currentPhoto.hidden = true;
        feedback.hidden = true;
    };

    input.addEventListener('change', () => showPreview(input.files[0]));

    picker.querySelector('[data-camera-open]').addEventListener('click', async () => {
        cameraError.hidden = true;

        if (!navigator.mediaDevices?.getUserMedia) {
            feedback.textContent = 'Akses kamera tidak tersedia. Gunakan tombol Pilih foto.';
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

            const file = new File([blob], `foto-${Date.now()}.jpg`, { type: 'image/jpeg' });
            const transfer = new DataTransfer();
            transfer.items.add(file);
            input.files = transfer.files;
            showPreview(file);
            dialog.close();
        }, 'image/jpeg', 0.9);
    });

    window.addEventListener('pagehide', () => {
        stopCamera();
        if (previewUrl) URL.revokeObjectURL(previewUrl);
    });
});