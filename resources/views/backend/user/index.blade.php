@extends('backend.user.master')
@section('content')
    <!-- ===== STATS GRID ===== -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Spent -->
        <div class="bg-cyber-surface p-6 border-2 border-black pixel-shadow flex flex-col justify-between group pixel-button-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="pixel-text text-xs text-text-secondary font-bold">Tổng chi tiêu</span>
                <i class="fas fa-coins text-yellow-400 text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-yellow-400 font-pixel">{{ number_format($totalSpent, 0, ',', '.') }}đ</p>
            <div class="mt-4 h-1 bg-black">
                <div class="h-full bg-yellow-400 w-full"></div>
            </div>
        </div>
        <!-- Courses Completed -->
        <div class="bg-cyber-surface p-6 border-2 border-black pixel-shadow flex flex-col justify-between group pixel-button-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="pixel-text text-xs text-text-secondary font-bold">Khóa học hoàn thành</span>
                <i class="fas fa-book text-pink-400 text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-pink-400 font-pixel">{{ $completedCoursesCount }}</p>
            <div class="mt-4 h-1 bg-black">
                <div class="h-full bg-pink-400" style="width: {{ $totalCourses > 0 ? ($completedCoursesCount / $totalCourses * 100) : 0 }}%;"></div>
            </div>
        </div>
        <!-- In Progress -->
        <div class="bg-cyber-surface p-6 border-2 border-black pixel-shadow flex flex-col justify-between group pixel-button-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="pixel-text text-xs text-text-secondary font-bold">Đang học</span>
                <i class="fas fa-clock text-cyber-cyan text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-cyber-cyan font-pixel">{{ $inProgressCoursesCount }}</p>
            <div class="mt-4 h-1 bg-black">
                <div class="h-full bg-cyber-cyan" style="width: {{ $totalCourses > 0 ? ($inProgressCoursesCount / $totalCourses * 100) : 0 }}%;"></div>
            </div>
        </div>
        <!-- Total Enrolled -->
        <div class="bg-cyber-surface p-6 border-2 border-black pixel-shadow flex flex-col justify-between group pixel-button-hover">
            <div class="flex justify-between items-start mb-4">
                <span class="pixel-text text-xs text-text-secondary font-bold">Tổng khóa học</span>
                <i class="fas fa-layer-group text-brand text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-brand font-pixel">{{ $totalCourses }}</p>
            <div class="mt-4 h-1 bg-black">
                <div class="h-full bg-brand w-full"></div>
            </div>
        </div>
    </div>



    <!-- ===== RECENT COURSES ===== -->
    <div class="mt-8">
        <h3 class="pixel-text font-bold text-xl text-white mb-6">Continue Learning <span
                class="text-brand">_ACTIVE_QUESTS</span></h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($recentCourses as $item)
                <a href="{{ route('course.play', $item['slug'] ?? '#') }}" class="block">
                    <div class="bg-cyber-surface border-2 border-black pixel-shadow pixel-button-hover p-4 h-full flex flex-col">
                        <div class="h-36 bg-cyber-dark border border-black mb-3 flex items-center justify-center overflow-hidden">
                            @if($item['course'] && $item['course']->course_image)
                                <img src="{{ asset($item['course']->course_image) }}" alt="course" class="w-full h-full object-cover">
                            @else
                                <i class="fab fa-laravel text-5xl text-red-500/30"></i>
                            @endif
                        </div>
                        <h4 class="font-bold text-sm mb-1 truncate" title="{{ $item['title'] }}">{{ $item['title'] }}</h4>
                        <p class="text-xs text-text-secondary mb-3 truncate">{{ $item['instructor_name'] }}</p>
                        
                        <div class="mt-auto">
                            <div class="w-full bg-black h-2 border border-black">
                                <div class="h-full bg-brand" style="width:{{ $item['progress'] }}%"></div>
                            </div>
                            <p class="text-[10px] text-text-secondary mt-1">{{ $item['progress'] }}% complete</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-8 bg-cyber-surface border-2 border-black">
                    <p class="text-text-secondary font-pixel">Bạn chưa có khóa học nào đang học.</p>
                </div>
            @endforelse
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
