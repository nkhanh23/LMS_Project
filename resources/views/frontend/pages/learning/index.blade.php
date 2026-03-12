@extends('frontend.master')

@section('content')
    <style>
        .hp-bar-bg {
            background: #1a2e18;
            border: 2px solid #000;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1E1E2E;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #A6E22E;
            border: 2px solid #000;
        }
    </style>

    <main class="mx-auto w-full max-w-[1600px] px-6 py-8">
        <!-- Main Title Section -->
        <div class="mb-6">
            <h1 class="text-4xl md:text-6xl font-black text-brand uppercase tracking-tighter drop-shadow-lg mb-2"
                style="text-shadow: 3px 3px 0px #000;">
                {{ $course->course_title }}
            </h1>
            <p class="text-cyber-cyan font-bold uppercase tracking-widest text-sm mb-6">{{ $course->short_description }}
            </p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Side: Video Player & Tabs -->
            @include('frontend.pages.learning.video-player')

            <!-- Right Sidebar: Quest Log -->
            @include('frontend.pages.learning.video-list')
        </div>
    </main>
@endsection
