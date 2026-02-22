    <style>
        /* Retro Grid Animation */
        .retro-grid {
            background-image:
                linear-gradient(to right, rgba(0, 243, 255, 0.1) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0, 243, 255, 0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            transform: perspective(500px) rotateX(60deg);
            transform-origin: center top;
            animation: grid-move 20s linear infinite;
        }

        @keyframes grid-move {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 0 1000px;
            }
        }

        /* Floating Binary Effect */
        .binary-rain {
            background: linear-gradient(180deg,
                    rgba(0, 243, 255, 0) 0%,
                    rgba(0, 243, 255, 0.1) 50%,
                    rgba(0, 243, 255, 0) 100%);
            background-size: 100% 200%;
            animation: rain 3s linear infinite;
        }

        @keyframes rain {
            0% {
                background-position: 0% 0%;
            }

            100% {
                background-position: 0% 200%;
            }
        }

        /* Scanline Overlay */
        .scanlines {
            background-size: 100% 2px, 3px 100%;
            pointer-events: none;
        }

        /* Custom Input Scrollbar hide */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>