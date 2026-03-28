$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).on('change', '.document-file-r2', async function () {
    const input = this;
    const form = $(input).closest('form');
    const file = input.files[0];  // Lấy file người dùng vừa chọn

    if (!file) return;

    const progressContainer = form.find('.document-upload-progress-container');
    const progressBar = form.find('.document-upload-progress-bar');
    const successMsg = form.find('.document-upload-success-msg');

    successMsg.hide();
    progressContainer.show();
    progressBar.css('width', '0%').text('0%');

    const fileName = file.name;
    const mimeType = file.type || 'application/octet-stream';
    const extension = fileName.split('.').pop().toLowerCase();// Lấy đuôi file (.pdf, .docx...)

    try {
        // Gọi AJAX xin "vé thông hành" (Presigned URL) từ Server cho tài liệu
        const presignedRes = await $.ajax({
            url: '/instructor/lecture/get-presigned-document-url',
            method: 'POST',
            data: {
                extension: extension,
                mime_type: mimeType,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Khởi tạo một XMLHttpRequest (XHR) - Công cụ giúp upload file chạy ngầm
        const xhr = new XMLHttpRequest();
        xhr.open('PUT', presignedRes.upload_url, true);
        xhr.setRequestHeader('Content-Type', mimeType);

        //Theo dõi tiến trình upload để làm Progress Bar
        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.css('width', percent + '%').text(percent + '%');
            }
        });

        //Khi upload xong (thành công hay lỗi)
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // LƯU THÔNG TIN: Khi upload xong, ghi lại các "thông số" vào input ẩn
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

        // Xử lý lỗi mạng
        xhr.onerror = function () {
            alert('Có lỗi mạng khi upload tài liệu!');
            progressContainer.hide();
        };

        // Gửi file lên R2
        xhr.send(file);
    } catch (error) {
        console.error(error);
        alert('Không lấy được presigned URL cho tài liệu!');
        progressContainer.hide();
    }
});

// Handle Lecture Type Change
$(document).on('change', '.lecture-type-select', function () {
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
$(document).on('change', '.video-file-r2', async function () {
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
        //Gọi AJAX xin "vé thông hành" (Presigned URL) từ Server cho video
        const presignedRes = await $.ajax({
            url: '/instructor/lecture/get-presigned-url',
            method: 'POST',
            data: {
                extension: extension,
                mime_type: mimeType,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Khởi tạo một XMLHttpRequest (XHR) - Công cụ giúp upload file chạy ngầm
        const xhr = new XMLHttpRequest();
        xhr.open('PUT', presignedRes.upload_url, true); // Dùng cái link vừa xin 
        xhr.setRequestHeader('Content-Type', mimeType);

        //Theo dõi tiến trình upload để làm Progress Bar
        xhr.upload.addEventListener('progress', function (e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.css('width', percent + '%').text(percent + '%');
            }
        });

        //Khi upload xong (thành công hay lỗi)
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Tìm ô input ẩn '.r2-video-key' và lưu Key (presignedRes.file_key) vào
                form.find('.r2-video-key').val(presignedRes.file_key);
                // Tạo URL tạm thời từ file vừa upload để hiển thị preview
                videoPreview.attr('src', URL.createObjectURL(file)).show();
                successMsg.show();
                progressBar.css('width', '100%').text('100%');
            } else {
                // Hiển thị thông báo upload lên R2 thất bại
                alert('Upload video lên R2 thất bại!');
                progressContainer.hide();
            }
        };

        //Gửi file lên R2
        xhr.send(file);
    } catch (error) {
        console.error(error);
        alert('Không lấy được presigned URL cho video!');
        progressContainer.hide();
    }
});
