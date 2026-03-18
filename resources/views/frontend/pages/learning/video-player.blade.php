<div class="flex-1 flex flex-col">
    <div id="learningPlayerWrapper">
        <!-- Video Player -->
        @include('frontend.pages.learning.partials.player-content')
    </div>



    <!-- Learning Tabs & Info with padding -->
    <div id="tabSectionContainer" class="{{ $currentLecture->quiz ? 'hidden' : 'p-4 md:p-8' }}">
        <div class="flex items-end border-b-2 border-black">
            <button onclick="switchTab('overview', this)"
                class="tab-btn active px-8 py-3 bg-cyber-surface text-white font-bold uppercase border-2 border-black border-b-0 relative z-10 -mb-[2px] transition-all">
                Tổng quan
            </button>
            <button onclick="switchTab('qa', this)"
                class="tab-btn px-8 py-3 bg-cyber-dark text-slate-400 font-bold uppercase border-2 border-black border-b-0 border-l-0 hover:bg-cyber-surface hover:text-white transition-all">
                Hỏi & Đáp
            </button>
            <button onclick="switchTab('notes', this)"
                class="tab-btn px-8 py-3 bg-cyber-dark text-slate-400 font-bold uppercase border-2 border-black border-b-0 border-l-0 hover:bg-cyber-surface hover:text-white transition-all">
                Ghi chú
            </button>
            <button onclick="switchTab('reviews', this)"
                class="tab-btn px-8 py-3 bg-cyber-dark text-slate-400 font-bold uppercase border-2 border-black border-b-0 border-l-0 hover:bg-cyber-surface hover:text-white transition-all">
                Đánh giá
            </button>
        </div>

        <div class="min-h-[400px]">
            <!-- Overview Tab -->
            @include('frontend.pages.learning.partials.overview')

            <!-- Q&A Tab -->
            @include('frontend.pages.learning.partials.qna')

            <!-- Notes Tab -->
            @include('frontend.pages.learning.partials.note')

            <!-- Reviews Tab -->
            @include('frontend.pages.learning.partials.reviews')
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function switchTab(tabId, element) {
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-cyber-surface', 'text-white', 'z-10', '-mb-[2px]');
                btn.classList.add('bg-cyber-dark', 'text-slate-400');
            });
            element.classList.add('active', 'bg-cyber-surface', 'text-white', 'z-10', '-mb-[2px]');
            element.classList.remove('bg-cyber-dark', 'text-slate-400');
        }
    </script>
@endpush
