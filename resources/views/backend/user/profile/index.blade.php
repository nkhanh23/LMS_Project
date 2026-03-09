@extends('backend.user.master')
@section('content')
    <!-- ===== PROFILE GRID ===== -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Left: Avatar & Quick Actions -->
        <div class="lg:col-span-4 flex flex-col gap-6">
            <!-- Avatar Card -->
            <div class="bg-cyber-surface p-2 border-4 border-black pixel-shadow">
                <div class="aspect-square bg-cyber-dark flex items-center justify-center border-2 border-black relative">
                    @if (auth()->user()->photo)
                        <img class="w-full h-full object-cover" src="{{ asset(auth()->user()->photo) }}" alt="User Avatar" />
                    @else
                        <div class="w-full h-full bg-cyber-dark flex items-center justify-center">
                            <i class="fas fa-user-astronaut text-brand/30 text-8xl"></i>
                        </div>
                    @endif
                    <div class="absolute bottom-4 left-4 right-4 bg-black/80 p-2 border border-brand/50">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-brand font-bold pixel-text">STATUS: ONLINE</span>
                            <div class="flex gap-1">
                                <i class="fas fa-star text-brand text-xs"></i>
                                <i class="fas fa-star text-brand text-xs"></i>
                                <i class="fas fa-star text-brand text-xs"></i>
                                <i class="fas fa-star text-brand text-xs"></i>
                                <i class="far fa-star text-text-secondary text-xs"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Course Button -->
            <button
                class="w-full bg-brand text-black font-black text-xl py-4 border-4 border-black pixel-shadow pixel-button-hover">
                UPLOAD COURSE
            </button>

            <!-- Skill Progress -->
            <div class="bg-cyber-surface border-4 border-black p-4 pixel-shadow">
                <h3 class="text-brand font-bold mb-3 flex items-center gap-2 pixel-text text-sm">
                    <i class="fas fa-bolt"></i>
                    SKILL PROGRESS
                </h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="pixel-text font-bold">JAVASCRIPT</span>
                            <span class="text-brand font-bold">85%</span>
                        </div>
                        <div class="h-4 bg-black border border-black p-0.5">
                            <div class="h-full bg-brand w-[85%]"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="pixel-text font-bold">PIXEL ART</span>
                            <span class="text-cyber-cyan font-bold">60%</span>
                        </div>
                        <div class="h-4 bg-black border border-black p-0.5">
                            <div class="h-full bg-cyber-cyan w-[60%]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: RPG Stat Sheet Details -->
        <div class="lg:col-span-8">
            <div class="bg-cyber-surface border-4 border-black pixel-shadow h-full flex flex-col">
                <!-- Terminal Title Bar -->
                <div class="bg-black p-3 border-b-4 border-black flex justify-between items-center">
                    <span class="text-brand font-bold pixel-text text-sm">CHARACTER_STATS.EXE</span>
                    <div class="flex gap-2">
                        <div class="w-3 h-3 bg-red-500 border border-black"></div>
                        <div class="w-3 h-3 bg-yellow-500 border border-black"></div>
                        <div class="w-3 h-3 bg-green-500 border border-black"></div>
                    </div>
                </div>
                <!-- Stats Content -->
                <div class="p-8 flex-1 space-y-6 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="group">
                                <p class="text-text-secondary text-xs uppercase font-bold mb-1 pixel-text">First Name</p>
                                <p
                                    class="text-cyber-cyan text-xl font-bold border-b-2 border-slate-700 group-hover:border-cyber-cyan pb-1 transition-colors">
                                    @auth {{ auth()->user()->name }}
                                    @else
                                    GUEST @endauth
                                </p>
                            </div>
                            <div class="group">
                                <p class="text-text-secondary text-xs uppercase font-bold mb-1 pixel-text">User Name</p>
                                <p
                                    class="text-brand text-xl font-bold border-b-2 border-slate-700 group-hover:border-brand pb-1 transition-colors">
                                    @auth {{ auth()->user()->username ?? auth()->user()->name }}
                                    @else
                                    GUEST @endauth
                                </p>
                            </div>
                            <div class="group">
                                <p class="text-text-secondary text-xs uppercase font-bold mb-1 pixel-text">Email</p>
                                <p
                                    class="text-text-primary text-xl font-bold border-b-2 border-slate-700 group-hover:border-text-primary pb-1 transition-colors">
                                    @auth {{ auth()->user()->email }}
                                    @else
                                    N/A @endauth
                                </p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="group">
                                <p class="text-text-secondary text-xs uppercase font-bold mb-1 pixel-text">Class</p>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-brain text-brand"></i>
                                    <p class="text-brand text-xl font-bold">FULL-STACK DEVELOPER</p>
                                </div>
                            </div>
                            <div class="group">
                                <p class="text-text-secondary text-xs uppercase font-bold mb-1 pixel-text">Experience</p>
                                <p class="text-text-primary text-xl font-bold">12,450 XP / 15,000 XP</p>
                            </div>
                            <div class="group">
                                <p class="text-text-secondary text-xs uppercase font-bold mb-1 pixel-text">Reputation</p>
                                <p class="text-cyber-cyan text-xl font-bold">LEGENDARY (98)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Biography -->
                    <div class="pt-6 border-t-2 border-slate-700">
                        <p class="text-text-secondary text-xs uppercase font-bold mb-3 pixel-text">Biography</p>
                        <div
                            class="bg-black/50 p-4 border-2 border-black text-text-secondary leading-relaxed font-mono text-sm">
                            A passionate coder wandering the neon alleys of the digital realm. Expert in
                            React, Node.js, and the fine art of 16-bit aesthetics. Currently building the
                            future of decentralized education.
                        </div>
                    </div>

                    <!-- Stats Counters -->
                    <div class="grid grid-cols-3 gap-4 pt-4">
                        <div class="bg-cyber-dark border-2 border-black p-3 text-center pixel-button-hover">
                            <p class="text-[10px] text-text-secondary pixel-text font-bold">COURSES</p>
                            <p class="text-2xl font-black text-brand font-pixel">12</p>
                        </div>
                        <div class="bg-cyber-dark border-2 border-black p-3 text-center pixel-button-hover">
                            <p class="text-[10px] text-text-secondary pixel-text font-bold">STUDENTS</p>
                            <p class="text-2xl font-black text-cyber-cyan font-pixel">1.2K</p>
                        </div>
                        <div class="bg-cyber-dark border-2 border-black p-3 text-center pixel-button-hover">
                            <p class="text-[10px] text-text-secondary pixel-text font-bold">ACHIEVEMENTS</p>
                            <p class="text-2xl font-black text-yellow-500 font-pixel">45</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER STATUS LINE ===== -->
    <div
        class="border-t-2 border-black/30 pt-4 flex justify-between items-center text-[10px] pixel-text text-text-secondary">
        <p>BUILD_VERSION_V2.0_STACKLEARN</p>
        <p>LAST_SYNCED: {{ now()->format('H:i:s') }}_UTC</p>
    </div>
@endsection
