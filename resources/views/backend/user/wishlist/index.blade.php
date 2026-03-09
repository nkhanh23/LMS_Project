@extends('backend.user.master')
@section('content')
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
