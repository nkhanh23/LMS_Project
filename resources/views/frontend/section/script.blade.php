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
    });
</script>
