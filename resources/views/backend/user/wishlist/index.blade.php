@extends('backend.user.master')
@section('content')
    <div class="mb-8">
        <h3 class="pixel-text font-bold text-xl text-white uppercase tracking-tighter">
            Danh sách yêu thích <span class="text-pink-500">_WISH_LIST</span>
        </h3>
        <p class="text-[10px] sm:text-xs text-text-secondary mt-1 font-mono uppercase">
            Những khóa học bạn đã lưu để học sau
        </p>
    </div>

    <div class="" id="wishlist-container">
        <!-- Wishlist items will be loaded here via Ajax -->
    </div>
    @if ($wishlist)
        <div class="text-center py-3">
            <nav aria-label="Page navigation example" class="pagination-box">
                <ul class="pagination justify-content-center" id="pagination-box">

                </ul>

            </nav>

        </div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('customjs/user/wishlist.js') }}"></script>
    <script src="{{ asset('customjs/user/index.js') }}"></script>
@endpush
