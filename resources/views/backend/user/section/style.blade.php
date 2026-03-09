<style>
    body {
        background-color: #1E1E2E;
        background-image: linear-gradient(to right, rgba(0, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(0, 255, 255, 0.05) 1px, transparent 1px);
        background-size: 40px 40px;
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

    .pixel-text {
        text-transform: uppercase;
        letter-spacing: 0.05em;
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

    /* Sidebar active tab */
    .sidebar-active {
        background-color: #A6E22E;
        color: #000000 !important;
        box-shadow: 4px 4px 0px 0px #000000;
    }

    .sidebar-active i {
        color: #000000 !important;
    }

    /* Scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #1E1E2E;
    }

    ::-webkit-scrollbar-thumb {
        background: #2A2A3C;
        border: 2px solid #000;
    }
</style>
