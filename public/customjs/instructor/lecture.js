$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('change', '.document-file-r2', async function () {
    const input = this;
    const form = $(input).closest('form');
    const file = input.files[0];

    if (!file) return;

    const progressContainer = form.find('.document-upload-progress-container');
    const progressBar = form.find('.document-upload-progress-bar');
    const successMsg = form.find('.document-upload-success-msg');

    successMsg.hide();
    progressContainer.show();
    progressBar.css('width', '0%').text('0%');

    const fileName = file.name;
    const mimeType = file.type || 'application/octet-stream';
    const extension = fileName.split('.').pop().toLowerCase();

    try {
        const presignedRes = await $.ajax({
            url: '/instructor/lecture/get-presigned-document-url',
            method: 'POST',
            data: {
                extension: extension,
                mime_type: mimeType,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });

        const xhr = new XMLHttpRequest();
        xhr.open('PUT', presignedRes.upload_url, true);
        xhr.setRequestHeader('Content-Type', mimeType);

        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.css('width', percent + '%').text(percent + '%');
            }
        });

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                form.find('.r2-document-key').val(presignedRes.file_key);
                form.find('.document-original-name').val(fileName);
                form.find('.document-mime-type').val(mimeType);
                form.find('.document-file-size').val(file.size);

                successMsg.show();
                progressBar.css('width', '100%').text('100%');
            } else {
                alert('Upload tài liệu lên R2 thất bại!');
                progressContainer.hide();
            }
        };

        xhr.onerror = function () {
            alert('Có lỗi mạng khi upload tài liệu!');
            progressContainer.hide();
        };

        xhr.send(file);
    } catch (error) {
        console.error(error);
        alert('Không lấy được presigned URL cho tài liệu!');
        progressContainer.hide();
    }
});

// Handle Lecture Type Change
$(document).on('change', '.lecture-type-select', function() {
    const type = $(this).val();
    const form = $(this).closest('form');

    form.find('.video-fields, .lecture-r2-upload, .text-fields, .document-fields').hide();

    if (type === 'video') {
        form.find('.video-fields').show();
        form.find('.content-label').text('Mô tả bài học (Tùy chọn)');
    } else if (type === 'r2_video') {
        form.find('.lecture-r2-upload').show();
        form.find('.content-label').text('Mô tả bài học (Tùy chọn)');
    } else if (type === 'text') {
        form.find('.text-fields').show();
        form.find('.content-label').text('Nội dung bài học');
    } else if (type === 'document') {
        form.find('.document-fields').show();
        form.find('.content-label').text('Mô tả bài học (Tùy chọn)');
    }
});

// Handle R2 Video Upload
$(document).on('change', '.video-file-r2', async function() {
    const input = this;
    const form = $(input).closest('form');
    const file = input.files[0];

    if (!file) return;

    const progressContainer = form.find('.upload-progress-container');
    const progressBar = form.find('.upload-progress-bar');
    const successMsg = form.find('.upload-success-msg');
    const videoPreview = form.find('.r2-video-preview');

    successMsg.hide();
    progressContainer.show();
    progressBar.css('width', '0%').text('0%');

    const fileName = file.name;
    const extension = fileName.split('.').pop().toLowerCase();
    const mimeType = file.type || 'video/mp4';

    try {
        const presignedRes = await $.ajax({
            url: '/instructor/lecture/get-presigned-url',
            method: 'POST',
            data: {
                extension: extension,
                mime_type: mimeType,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });

        const xhr = new XMLHttpRequest();
        xhr.open('PUT', presignedRes.upload_url, true);
        xhr.setRequestHeader('Content-Type', mimeType);

        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.css('width', percent + '%').text(percent + '%');
            }
        });

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                form.find('.r2-video-key').val(presignedRes.file_key);
                videoPreview.attr('src', URL.createObjectURL(file)).show();
                successMsg.show();
                progressBar.css('width', '100%').text('100%');
            } else {
                alert('Upload video lên R2 thất bại!');
                progressContainer.hide();
            }
        };

        xhr.send(file);
    } catch (error) {
        console.error(error);
        alert('Không lấy được presigned URL cho video!');
        progressContainer.hide();
    }
});