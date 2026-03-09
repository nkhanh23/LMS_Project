@extends('frontend.master')

@push('style')
    <style>
        .retro-border {
            border: 4px solid #000000;
        }

        .retro-input::placeholder {
            color: #A6ACCD;
            opacity: 0.7;
        }

        .shadow-retro {
            box-shadow: 4px 4px 0px 0px rgba(0, 0, 0, 1);
        }

        .shadow-retro-hover:hover {
            box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 1);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-1 justify-center py-8 px-8 sm:px-12 lg:px-20 overflow-x-hidden">
        <div class="layout-content-container flex flex-col w-full max-w-[1200px] flex-1">

            <!-- Breadcrumb Area -->
            @include('frontend.section.breadcrumb', ['title' => 'Giỏ hàng'])

            <!-- Main Content Area -->
            <div id="cart-main-content">
                <!-- được tải lên bằng ajax -->

            </div>

        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('customjs/cart/index.js') }}"></script>
@endpush
