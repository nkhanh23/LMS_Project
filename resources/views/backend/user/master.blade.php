<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <title>StackLearn | User Dashboard</title>
    @include('backend.user.section.link')

    <!-- Tailwind CSS CDN -->
    @include('backend.user.section.script')
    @include('backend.user.section.style')
</head>

<body
    class="text-text-primary font-sans selection:bg-brand selection:text-black min-h-screen antialiased overflow-x-hidden">
    <div class="flex h-screen w-full overflow-hidden">
        <!-- ===== SIDEBAR ===== -->
        @include('backend.user.section.sidebar')

        <!-- ===== MAIN CONTENT ===== -->
        <main class="flex-1 flex flex-col overflow-y-auto relative">
            <!-- Header Bar -->
            @include('backend.user.section.header')

            <!-- Page Content -->
            <div class="p-8 space-y-8">
                <!-- ===== WELCOME SECTION ===== -->
                @include('backend.user.section.breadcrumb')
                @yield('content')
            </div>
        </main>
    </div>

    {{-- SweetAlert2 Flash Messages --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'THÀNH CÔNG!',
                text: '{{ session('success') }}',
                background: '#2A2A3C',
                color: '#F8F8F2',
                confirmButtonColor: '#A6E22E',
                confirmButtonText: 'OK',
                iconColor: '#A6E22E',
                customClass: {
                    popup: 'border-2 border-black',
                    title: 'font-bold uppercase',
                }
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'LỖI!',
                text: '{{ session('error') }}',
                background: '#2A2A3C',
                color: '#F8F8F2',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK',
                iconColor: '#ef4444',
                customClass: {
                    popup: 'border-2 border-black',
                    title: 'font-bold uppercase',
                }
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'CẢNH BÁO!',
                text: '{{ session('warning') }}',
                background: '#2A2A3C',
                color: '#F8F8F2',
                confirmButtonColor: '#eab308',
                confirmButtonText: 'OK',
                iconColor: '#eab308',
                customClass: {
                    popup: 'border-2 border-black',
                    title: 'font-bold uppercase',
                }
            });
        </script>
    @endif

    {{-- Validation Errors Tab Memory --}}
    @if ($errors->any() && session('error_tab'))
        <script>
            // Automatically open the tab that has errors based on session data
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof switchTab === 'function') {
                    switchTab('{{ session('error_tab') }}');
                }
            });
        </script>
    @endif
</body>

</html>
