<!-- -----------------  ADMIN LOGIN  ------------------>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
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
