<!-- -----------------  ADMIN LOGIN  ------------------>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        corePlugins: {
            preflight: false,
            /* Tắt Preflight để không reset Bootstrap/browser styles */
        },
        theme: {
            extend: {
                colors: {
                    "primary": "#a6e22c",
                    "background-light": "#f7f8f6",
                    "background-dark": "#1E1E2E",
                    "cyber-blue": "#00f3ff",
                },
                fontFamily: {
                    "display": ["Space Grotesk", "monospace"]
                },
                boxShadow: {
                    'block': '8px 8px 0px 0px rgba(0,0,0,1)',
                    'neon': '0 0 10px #00f3ff',
                },
                backgroundImage: {
                    'scanlines': 'linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), linear-gradient(90deg, rgba(255, 0, 0, 0.06), rgba(0, 255, 0, 0.02), rgba(0, 0, 255, 0.06))',
                }
            },
        },
    }
</script>


<!-- -----------------  ADMIN DASHBOARD  ------------------>
<script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="{{ asset('backend/assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/chartjs/js/chart.js') }}"></script>
<script src="{{ asset('backend/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/index.js') }}"></script>
<!--app JS-->
<script src="{{ asset('backend/assets/js/app.js') }}"></script>
<script>
    new PerfectScrollbar(".app-container")
</script>

<!-------------------  PHOTO PREVIEW SCRIPT  ------------------>
<script>
    $(document).ready(function() {
        $('#photo').on('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                $('#photoPreview').attr('src', URL.createObjectURL(file))
                    .css('display', 'block'); //show anhr preview
            }
        });
    });
</script>

<!-- -- -- -- -- -- -- -- -- - SWEETALERT-- -- -- -- -- -- -- -- -- -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.18/dist/sweetalert2.min.js"></script>
<script>
    @if (session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#fff',
            title: '{{ session('success') }}',
            icon: 'success',
        })
    @endif

    @if (session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#fff',
            title: '{{ session('error') }}',
            icon: 'error',
        })
    @endif
</script>

<!-- -- -- -- -- -- -- -- -- - APP.JS-- -- -- -- -- -- -- -- -- -->
<script src="{{ asset('backend/assets/js/app.js') }}"></script>

<!-- DataTable -->
<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>
<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>

<!-- -----------------  ADMIN CATEGORY SCRIPT  ------------------>
@stack('script')

<!---Flora editor--->
<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js'>
</script>

<script>
    // Initialize Froala Editor with a fixed height
    new FroalaEditor('.editor', {
        height: 200 // Set height to 200px
    });
</script>


<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>


<script>
    $(document).ready(function() {
        var table = $('#example2').DataTable({
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'print']
        });

        table.buttons().container()
            .appendTo('#example2_wrapper .col-md-6:eq(0)');
    });
</script>

<!-- -----------------  PRICE RANGE SCRIPT  ------------------>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


<!-- -----------------  LECTURE CREATE SCRIPT  ------------------>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 1. Logic ẩn/hiện các block khi thay đổi loại bài giảng (Áp dụng cho mọi modal)
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('lecture-type-select')) {
                let form = e.target.closest('form');
                let type = e.target.value;

                // Tìm các block trong nội bộ form này
                let r2Block = form.querySelector('.lecture-r2-upload');
                let youtubeBlock = form.querySelector('.video-fields');
                let docBlock = form.querySelector('.document-fields');

                // Xử lý logic ẩn hiện
                r2Block.style.display = (type === 'r2_video') ? 'block' : 'none';
                youtubeBlock.style.display = (type === 'video') ? 'block' : 'none';
                docBlock.style.display = (type === 'document') ? 'block' : 'none';
            }
        });

        // 2. Logic can thiệp khi bấm nút Submit form (Bắt sự kiện Upload R2)
        document.addEventListener('submit', async function(e) {
            // Kiểm tra xem form đang submit có đúng là form tạo lecture không
            if (e.target && (e.target.classList.contains('lecture-create-form') || e.target
                    .classList.contains('lecture-edit-form'))) {
                let form = e.target;
                let selectType = form.querySelector('.lecture-type-select').value;
                let fileInput = form.querySelector('.video-file-r2');

                // Chỉ can thiệp nếu là r2_video VÀ có file được chọn VÀ chưa upload xong
                if (selectType === 'r2_video' && fileInput.files.length > 0) {
                    e.preventDefault(); // CHẶN LẠI KHÔNG CHO TRÌNH DUYỆT SUBMIT FORM

                    let file = fileInput.files[0];
                    let extension = file.name.split('.').pop();

                    // Lưu lại blob URL để preview sau khi upload xong
                    let previewUrl = URL.createObjectURL(file);

                    let submitBtn = form.querySelector('.btn-submit-lecture');
                    let progressContainer = form.querySelector('.upload-progress-container');
                    let progressBar = form.querySelector('.upload-progress-bar');
                    let hiddenKeyInput = form.querySelector('.r2-video-key');
                    let videoPreview = form.querySelector('.r2-video-preview');
                    let successMsg = form.querySelector('.upload-success-msg');

                    submitBtn.disabled = true;
                    submitBtn.innerText = "Đang tải video lên R2...";
                    progressContainer.style.display = 'flex';

                    try {
                        // 1. Xin Presigned URL từ Laravel API
                        const response = await fetch(
                            "{{ route('instructor.lecture.get-presigned-url') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    extension: extension
                                })
                            });

                        const data = await response.json();

                        // 2. Upload file trực tiếp lên R2
                        const xhr = new XMLHttpRequest();
                        xhr.open('PUT', data.upload_url, true);
                        xhr.setRequestHeader('Content-Type', file.type);

                        // Lắng nghe % tải lên
                        xhr.upload.onprogress = function(event) {
                            if (event.lengthComputable) {
                                const percentComplete = Math.round((event.loaded / event
                                    .total) * 100);
                                progressBar.style.width = percentComplete + '%';
                                progressBar.innerText = percentComplete + '%';
                            }
                        };

                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                // 3. Xong -> Gán key vào input ẩn
                                hiddenKeyInput.value = data.file_key;
                                fileInput.value = '';

                                // 4. Hiện video preview để người dùng xem lại
                                videoPreview.src = previewUrl;
                                videoPreview.style.display = 'block';
                                successMsg.style.display = 'block';
                                progressContainer.style.display = 'none';

                                // 5. Mở khóa nút Submit để người dùng bấm lưu khi sẵn sàng
                                submitBtn.disabled = false;
                                submitBtn.innerText = "✓ Lưu bài học";
                                submitBtn.classList.remove('btn-primary');
                                submitBtn.classList.add('btn-success');
                            } else {
                                alert("Lỗi khi tải video lên Cloudflare R2.");
                                submitBtn.disabled = false;
                                submitBtn.innerText = "Lưu bài học";
                            }
                        };

                        xhr.onerror = function() {
                            alert("Lỗi mạng khi tải video.");
                            submitBtn.disabled = false;
                            submitBtn.innerText = "Lưu bài học";
                        };

                        // Thực thi upload
                        xhr.send(file);

                    } catch (error) {
                        alert("Lỗi hệ thống: " + error.message);
                        submitBtn.disabled = false;
                        submitBtn.innerText = "Lưu bài học";
                    }
                }
                // Nếu không phải r2_video, form sẽ tự động submit bình thường
            }
        });

        // 3. Logic xử lý Video YouTube Preview
        function extractYouTubeVideoID(url) {
            let regex =
                /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/;
            let match = url.match(regex);
            return match ? match[1] : null;
        }

        document.body.addEventListener("input", function(e) {
            if (e.target && e.target.classList.contains('video_url')) {
                let url = e.target.value;
                let modal = e.target.closest('.modal');
                if (modal) {
                    let preview = modal.querySelector('.videoPreview');
                    if (preview) {
                        let videoId = extractYouTubeVideoID(url);
                        if (videoId) {
                            preview.src = `https://www.youtube.com/embed/${videoId}`;
                            preview.style.display = "block";
                        } else {
                            preview.src = "";
                            preview.style.display = "none";
                        }
                    }
                }
            }
        });

        // 4. Khi mở/đóng File Modal thì format lại iframe và logic
        document.body.addEventListener('shown.bs.modal', function(e) {
            let modal = e.target;
            if (modal.id.startsWith("course-edit-")) {
                let videoInput = modal.querySelector(".video_url");
                let videoPreview = modal.querySelector(".videoPreview");
                let typeSelect = modal.querySelector(".lecture-type-select");

                // Kích hoạt ẩn hiện panel
                if (typeSelect) {
                    typeSelect.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }

                // Kích hoạt YouTube link
                if (videoInput && videoPreview && videoInput.value.trim() !== "") {
                    let videoId = extractYouTubeVideoID(videoInput.value);
                    if (videoId) {
                        videoPreview.src = `https://www.youtube.com/embed/${videoId}`;
                        videoPreview.style.display = "block";
                    }
                }
            }
        });

        document.body.addEventListener('hidden.bs.modal', function(e) {
            let modal = e.target;
            if (modal.id.startsWith("course-edit-")) {
                let videoPreview = modal.querySelector(".videoPreview");
                // Reset iframe src để tắt video nếu đang phát
                if (videoPreview) {
                    videoPreview.src = "";
                    videoPreview.style.display = "none";
                }
            }
        });
    });
</script>
