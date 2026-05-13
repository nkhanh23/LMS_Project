    <style>
        body {
            background-color: #1E1E2E;
            background-image: linear-gradient(to right, rgba(0, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(0, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }

        /* Force all elements to stay within bounds but allow internal scrolling */
        body > *, main > section {
            max-width: 100%;
        }

        /* Exception for scrollable categories and carousels */
        .no-scrollbar, .owl-carousel, .owl-stage-outer, .owl-stage {
            max-width: none !important;
        }

        @media (max-width: 639px) {
            .header-top { display: none !important; }
        }

        /* Prevent long words from breaking layout */
        h1, h2, h3, h4, h5, h6, p, span, a {
            overflow-wrap: break-word;
            word-wrap: break-word;
        }

        /* Hide scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .pixel-border {
            box-shadow: 0 -3px 0 0 black, 0 3px 0 0 black, -3px 0 0 0 black, 3px 0 0 0 black;
        }

        .pixel-shadow {
            box-shadow: 4px 4px 0 0 rgba(0, 0, 0, 1);
        }

        .pixel-shadow-sm {
            box-shadow: 3px 3px 0 0 rgba(0, 0, 0, 1);
        }

        .pixel-button-hover {
            transition: all 0.1s ease;
        }

        .pixel-button-hover:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 0 rgba(0, 0, 0, 1);
        }

        .pixel-button-hover:active {
            transform: translate(2px, 2px);
            box-shadow: 2px 2px 0 0 rgba(0, 0, 0, 1);
        }

        .cursor-blink {
            animation: blink 1s step-end infinite;
        }

        @keyframes blink {

            from,
            to {
                border-color: transparent
            }

            50% {
                border-color: #A6E22E;
            }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-15px);
            }
        }

        img {
            image-rendering: pixelated;
        }

        /* Preloader */
        .preloader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #1E1E2E;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s;
        }

        .preloader.hide {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #2A2A3C;
            border-top-color: #A6E22E;
            animation: spin 0.8s steps(8) infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Mobile menu */
        .offcanvas {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .offcanvas.active {
            transform: translateX(0);
        }

        .offcanvas-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .offcanvas-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        /* Wishlist dropdown */
        .wishlist {
            display: none;
        }

        .wishlist-trigger:hover .wishlist {
            display: block;
        }

        /* Cart dropdown */
        .cart-dropdown {
            display: none;
        }

        .cart-trigger:hover .cart-dropdown {
            display: block;
        }

        /* Counter animation */
        @keyframes scan {
            0% {
                top: 0;
            }

            100% {
                top: 100%;
            }
        }

        /* Scroll animations */
        .fade-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Category dropdown */
        .cat-dropdown {
            display: none;
        }

        .cat-trigger:hover .cat-dropdown {
            display: block;
        }

        /* Tab active */
        .tab-btn.active {
            background-color: #A6E22E;
            color: #000;
        }

        /* Tooltip on course hover */
        .course-tooltip {
            display: none;
            position: absolute;
            z-index: 50;
        }

        .course-card-wrap:hover .course-tooltip {
            display: block;
        }

        /* Quiz Selection Styles */
        .quiz-option-input:checked + .quiz-option-content {
            border-color: #A6E22E !important;
            background-color: rgba(166, 226, 46, 0.1) !important;
        }
        
        .quiz-option-input:checked + .quiz-option-content .quiz-option-letter {
            border-color: #A6E22E !important;
            color: #A6E22E !important;
        }

        .quiz-option-input:checked + .quiz-option-content .quiz-option-text {
            color: #fff !important;
        }

        .quiz-option-input:checked + .quiz-option-content .quiz-option-circle {
            border-color: #A6E22E !important;
            background-color: #A6E22E !important;
        }

        .quiz-option-input:checked + .quiz-option-content .quiz-option-dot {
            opacity: 1 !important;
        }
    </style>
