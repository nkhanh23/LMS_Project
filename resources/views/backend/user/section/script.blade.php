<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
{{-- Sweet Alert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.11.3/dist/echo.js"></script>
<script>
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    brand: '#A6E22E',
                    'cyber-dark': '#1E1E2E',
                    'cyber-surface': '#2A2A3C',
                    'cyber-cyan': '#66D9EF',
                    'cyber-grid': 'rgba(0, 255, 255, 0.05)',
                    'text-primary': '#F8F8F2',
                    'text-secondary': '#A0A0B0',
                },
                fontFamily: {
                    sans: ['"Space Grotesk"', 'sans-serif'],
                    pixel: ['"Pixelify Sans"', 'cursive'],
                    mono: ['"VT323"', 'monospace'],
                },
            }
        }
    }
</script>


@stack('scripts')
