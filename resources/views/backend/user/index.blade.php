@extends('backend.user.master')
@section('content')
    <!-- ===== STATS GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Earnings -->
        <div
            class="bg-cyber-surface p-6 border-2 border-black pixel-shadow flex flex-col justify-between group pixel-button-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="pixel-text text-xs text-text-secondary font-bold">Tổng thu nhập</span>
                <i class="fas fa-coins text-yellow-400 text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-yellow-400 font-pixel">$4,096</p>
            <div class="mt-4 h-1 bg-black">
                <div class="h-full bg-yellow-400 w-3/4"></div>
            </div>
        </div>
        <!-- Courses Completed -->
        <div
            class="bg-cyber-surface p-6 border-2 border-black pixel-shadow flex flex-col justify-between group pixel-button-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="pixel-text text-xs text-text-secondary font-bold">Khóa học đã hoàn thành</span>
                <i class="fas fa-book text-pink-400 text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-pink-400 font-pixel">12</p>
            <div class="mt-4 h-1 bg-black">
                <div class="h-full bg-pink-400 w-1/2"></div>
            </div>
        </div>
        <!-- Total Hours -->
        <div
            class="bg-cyber-surface p-6 border-2 border-black pixel-shadow flex flex-col justify-between group pixel-button-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="pixel-text text-xs text-text-secondary font-bold">Total Hours</span>
                <i class="fas fa-clock text-cyber-cyan text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-cyber-cyan font-pixel">256</p>
            <div class="mt-4 h-1 bg-black">
                <div class="h-full bg-cyber-cyan w-[85%]"></div>
            </div>
        </div>
        <!-- Rank -->
        <div
            class="bg-cyber-surface p-6 border-2 border-black pixel-shadow flex flex-col justify-between group pixel-button-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="pixel-text text-xs text-text-secondary font-bold">Rank</span>
                <i class="fas fa-medal text-brand text-xl"></i>
            </div>
            <p class="text-2xl font-bold text-brand font-pixel">LEGENDARY</p>
            <div class="mt-4 h-1 bg-black">
                <div class="h-full bg-brand w-full"></div>
            </div>
        </div>
    </div>

    <!-- ===== LEARNING ACTIVITY CHART ===== -->
    <div class="bg-cyber-surface border-2 border-black pixel-shadow p-8">
        <div class="flex items-center justify-between mb-8">
            <h3 class="pixel-text font-bold text-xl text-white">Learning Activity <span
                    class="text-cyber-cyan">_SESSION_LOGS</span></h3>
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-pink-400 border-2 border-black"></div>
                    <span class="pixel-text text-[10px] text-text-secondary font-bold">Lectures</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-cyber-cyan border-2 border-black"></div>
                    <span class="pixel-text text-[10px] text-text-secondary font-bold">Coding</span>
                </div>
            </div>
        </div>
        <!-- Pixel-Art Bar Chart -->
        <div
            class="flex items-end justify-between h-64 w-full gap-4 pt-4 border-b-4 border-l-4 border-black px-4 bg-black/20">
            <!-- Monday -->
            <div class="flex-1 flex flex-col gap-1 items-center">
                <div class="w-full bg-pink-400 border-2 border-black h-24"></div>
                <div class="w-full bg-cyber-cyan border-2 border-black h-12"></div>
                <span class="pixel-text text-[10px] mt-2 font-bold text-text-secondary font-pixel">MON</span>
            </div>
            <!-- Tuesday -->
            <div class="flex-1 flex flex-col gap-1 items-center">
                <div class="w-full bg-pink-400 border-2 border-black h-16"></div>
                <div class="w-full bg-cyber-cyan border-2 border-black h-32"></div>
                <span class="pixel-text text-[10px] mt-2 font-bold text-text-secondary font-pixel">TUE</span>
            </div>
            <!-- Wednesday -->
            <div class="flex-1 flex flex-col gap-1 items-center">
                <div class="w-full bg-pink-400 border-2 border-black h-32"></div>
                <div class="w-full bg-cyber-cyan border-2 border-black h-8"></div>
                <span class="pixel-text text-[10px] mt-2 font-bold text-text-secondary font-pixel">WED</span>
            </div>
            <!-- Thursday -->
            <div class="flex-1 flex flex-col gap-1 items-center">
                <div class="w-full bg-pink-400 border-2 border-black h-12"></div>
                <div class="w-full bg-cyber-cyan border-2 border-black h-40"></div>
                <span class="pixel-text text-[10px] mt-2 font-bold text-text-secondary font-pixel">THU</span>
            </div>
            <!-- Friday -->
            <div class="flex-1 flex flex-col gap-1 items-center">
                <div class="w-full bg-pink-400 border-2 border-black h-20"></div>
                <div class="w-full bg-cyber-cyan border-2 border-black h-20"></div>
                <span class="pixel-text text-[10px] mt-2 font-bold text-text-secondary font-pixel">FRI</span>
            </div>
            <!-- Saturday -->
            <div class="flex-1 flex flex-col gap-1 items-center">
                <div class="w-full bg-pink-400 border-2 border-black h-40"></div>
                <div class="w-full bg-cyber-cyan border-2 border-black h-12"></div>
                <span class="pixel-text text-[10px] mt-2 font-bold text-text-secondary font-pixel">SAT</span>
            </div>
            <!-- Sunday -->
            <div class="flex-1 flex flex-col gap-1 items-center">
                <div class="w-full bg-pink-400 border-2 border-black h-12"></div>
                <div class="w-full bg-cyber-cyan border-2 border-black h-8"></div>
                <span class="pixel-text text-[10px] mt-2 font-bold text-text-secondary font-pixel">SUN</span>
            </div>
        </div>
    </div>

    <!-- ===== RECENT COURSES ===== -->
    <div>
        <h3 class="pixel-text font-bold text-xl text-white mb-6">Continue Learning <span
                class="text-brand">_ACTIVE_QUESTS</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-cyber-surface border-2 border-black pixel-shadow pixel-button-hover p-4">
                <div class="h-36 bg-cyber-dark border border-black mb-3 flex items-center justify-center">
                    <i class="fab fa-laravel text-5xl text-red-500/30"></i>
                </div>
                <h4 class="font-bold text-sm mb-1">Laravel Masterclass</h4>
                <p class="text-xs text-text-secondary mb-3">Tim Buchalka · 24h total</p>
                <div class="w-full bg-black h-2 border border-black">
                    <div class="h-full bg-brand" style="width:65%"></div>
                </div>
                <p class="text-[10px] text-text-secondary mt-1">65% complete</p>
            </div>
            <div class="bg-cyber-surface border-2 border-black pixel-shadow pixel-button-hover p-4">
                <div class="h-36 bg-cyber-dark border border-black mb-3 flex items-center justify-center">
                    <i class="fab fa-react text-5xl text-cyber-cyan/30"></i>
                </div>
                <h4 class="font-bold text-sm mb-1">React & Next.js Pro</h4>
                <p class="text-xs text-text-secondary mb-3">DevTeam · 18h total</p>
                <div class="w-full bg-black h-2 border border-black">
                    <div class="h-full bg-cyber-cyan" style="width:40%"></div>
                </div>
                <p class="text-[10px] text-text-secondary mt-1">40% complete</p>
            </div>
            <div class="bg-cyber-surface border-2 border-black pixel-shadow pixel-button-hover p-4">
                <div class="h-36 bg-cyber-dark border border-black mb-3 flex items-center justify-center">
                    <i class="fab fa-python text-5xl text-yellow-400/30"></i>
                </div>
                <h4 class="font-bold text-sm mb-1">Python for AI</h4>
                <p class="text-xs text-text-secondary mb-3">AI Team · 32h total</p>
                <div class="w-full bg-black h-2 border border-black">
                    <div class="h-full bg-yellow-400" style="width:20%"></div>
                </div>
                <p class="text-[10px] text-text-secondary mt-1">20% complete</p>
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

@push('scripts')
    <script src="{{ asset('customjs/user/wishlist.js') }}"></script>
    <script src="{{ asset('customjs/user/index.js') }}"></script>
@endpush
