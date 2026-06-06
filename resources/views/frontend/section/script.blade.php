    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
                        pixel: ['"Press Start 2P"', 'cursive'],
                        mono: ['"VT323"', 'monospace'],
                    },
                }
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        $(document).ready(function() {
            // Preloader
            setTimeout(function() {
                document.getElementById('preloader').classList.add('hide');
            }, 800);

            // Mobile menu
            $('#mobileMenuBtn').on('click', function() {
                $('#mobileMenu, #menuOverlay').addClass('active');
            });
            $('#closeMenu, #menuOverlay').on('click', function() {
                $('#mobileMenu, #menuOverlay').removeClass('active');
            });

            // Hero slider
            $('.hero-slider').owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                autoplayTimeout: 5000,
                nav: false,
                dots: true,
                animateOut: 'fadeOut',
                smartSpeed: 800
            });

            // Fancybox
            Fancybox.bind("[data-fancybox]", {
                Youtube: {
                    autoplay: 0
                },
                Vimeo: {
                    autoplay: 0
                },
                Html5Video: {
                    autoplay: false
                }
            });

            // Scroll reveal
            const observer = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) e.target.classList.add('visible');
                });
            }, {
                threshold: 0.1
            });
            document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

            // Theme toggle
            function toggleTheme() {
                document.documentElement.classList.toggle('dark');
                const icon = document.documentElement.classList.contains('dark') ? 'fa-moon' : 'fa-sun';
                document.querySelectorAll('#themeToggle i, #themeToggleMobile i').forEach(i => i.className =
                    'fas ' + icon);
            }
            document.getElementById('themeToggle')?.addEventListener('click', toggleTheme);
            document.getElementById('themeToggleMobile')?.addEventListener('click', toggleTheme);

            // Scroll top
            $(window).on('scroll', function() {
                $('#scrollTop').toggleClass('opacity-0 pointer-events-none', $(this).scrollTop() < 400);
            });

            // Counter animation
            function animateCounters() {
                $('.counter').each(function() {
                    var $this = $(this),
                        countTo = $this.attr('data-count');
                    $({
                        countNum: 0
                    }).animate({
                        countNum: countTo
                    }, {
                        duration: 2000,
                        easing: 'swing',
                        step: function() {
                            $this.text(Math.floor(this.countNum));
                        },
                        complete: function() {
                            $this.text(this.countNum);
                        }
                    });
                });
            }
            var counterDone = false;
            $(window).on('scroll', function() {
                var counterSection = $('#funfact');
                if (counterSection.length && !counterDone) {
                    var top = counterSection.offset().top - $(window).height() + 100;
                    if ($(window).scrollTop() > top) {
                        animateCounters();
                        counterDone = true;
                    }
                }
            });

            // Tab filtering
            $('.tab-btn').on('click', function() {
                $('.tab-btn').removeClass('active');
                $(this).addClass('active');
                var filter = $(this).data('filter');
                if (filter === 'all') {
                    $('.course-item').show();
                } else {
                    $('.course-item').hide().filter('[data-cat="' + filter + '"]').show();
                }
            });

            // Course carousel
            $('.course-carousel').owlCarousel({
                items: 3,
                loop: true,
                autoplay: true,
                autoplayTimeout: 4000,
                nav: true,
                dots: false,
                margin: 24,
                navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    1024: {
                        items: 3
                    }
                }
            });

            // Testimonial carousel
            $('.testimonial-carousel').owlCarousel({
                items: 2,
                loop: true,
                autoplay: true,
                autoplayTimeout: 5000,
                nav: false,
                dots: true,
                margin: 24,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    }
                }
            });

            // Client logo carousel
            $('.client-carousel').owlCarousel({
                items: 5,
                loop: true,
                autoplay: true,
                autoplayTimeout: 3000,
                nav: false,
                dots: false,
                margin: 40,
                responsive: {
                    0: {
                        items: 2
                    },
                    480: {
                        items: 3
                    },
                    768: {
                        items: 4
                    },
                    1024: {
                        items: 5
                    }
                }
            });

            // Force recalculate responsive widths
            setTimeout(function() {
                window.dispatchEvent(new Event('resize'));
            }, 500);
        });
    </script>

    <script src="{{ asset('customjs/wishlist/index.js') }}"></script>
    <script src="{{ asset('customjs/cart/index.js') }}"></script>

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
    <script>
        @if (session('success'))
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
        @endif
    </script>

    <script>
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'LỖI!',
                text: '{{ session('error') }}',
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
        @endif
    </script>

    @stack('scripts')

    <script>
    (function () {
        // ---- Shared autocomplete logic ----
        var SUGGEST_URL = '{{ route('search.suggestions') }}';
        var COURSES_URL = '{{ route('frontend.courses.index') }}';

        function buildItem(course) {
            return '<a href="' + course.url + '" class="flex items-center gap-3 px-4 py-3 hover:bg-brand hover:text-black group border-b border-black/20 last:border-0 transition-colors">' +
                '<img src="' + course.image + '" alt="" class="w-14 h-10 object-cover border border-black/40 flex-shrink-0">' +
                '<div class="min-w-0 flex-1">' +
                    '<p class="text-sm font-bold leading-tight line-clamp-1 group-hover:text-black">' + course.name + '</p>' +
                    '<p class="text-[11px] text-text-secondary group-hover:text-black/70 mt-0.5">' + course.instructor + ' &middot; ' + course.category + '</p>' +
                '</div>' +
                '<span class="text-brand font-bold text-sm shrink-0 group-hover:text-black">' + course.price + '</span>' +
            '</a>';
        }

        function buildViewAll(keyword) {
            return '<a href="' + COURSES_URL + '?q=' + encodeURIComponent(keyword) + '" ' +
                'class="block text-center px-4 py-3 bg-black/30 text-brand font-bold text-sm hover:bg-brand hover:text-black transition-colors uppercase tracking-widest">' +
                '<i class="fas fa-search mr-2"></i>Xem tất cả kết quả cho &ldquo;' + keyword + '&rdquo;' +
                '</a>';
        }

        function initSearch(inputId, dropdownId) {
            var input = document.getElementById(inputId);
            var dropdown = document.getElementById(dropdownId);
            if (!input || !dropdown) return;

            var timer = null;
            var lastQuery = '';

            function hide() { dropdown.classList.add('hidden'); dropdown.innerHTML = ''; }
            function show() { dropdown.classList.remove('hidden'); }

            input.addEventListener('input', function () {
                var q = this.value.trim();
                clearTimeout(timer);
                if (q.length < 2) { hide(); lastQuery = ''; return; }
                if (q === lastQuery) return;
                lastQuery = q;
                timer = setTimeout(function () {
                    fetch(SUGGEST_URL + '?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
                        .then(function (r) { return r.json(); })
                        .then(function (data) {
                            if (!data.length) { hide(); return; }
                            var html = data.map(buildItem).join('');
                            html += buildViewAll(q);
                            dropdown.innerHTML = html;
                            show();
                        })
                        .catch(function () { hide(); });
                }, 280);
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') hide();
            });

            // Enter → full search
            input.closest('form')?.addEventListener('submit', function () { hide(); });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initSearch('header-search-input', 'header-search-dropdown');
            initSearch('mobile-search-input', 'mobile-search-dropdown');

            // Close on outside click
            document.addEventListener('click', function (e) {
                ['header-search-wrapper', 'mobile-search-wrapper'].forEach(function (id) {
                    var wrapper = document.getElementById(id);
                    if (wrapper && !wrapper.contains(e.target)) {
                        var dd = wrapper.querySelector('[id$="-search-dropdown"]');
                        if (dd) { dd.classList.add('hidden'); dd.innerHTML = ''; }
                    }
                });
            });
        });
    })();
    </script>
