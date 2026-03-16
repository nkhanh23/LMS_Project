<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">INSTRUCTOR</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="javascript:;" class="">
                <div class="parent-icon"><i class='bx bx-category'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        @if (isApprovedUser())
            <li class="{{ setSidebar(['instructor.course*', 'instructor.course-section*']) }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Quản lý khóa học</div>
                </a>
                <ul>
                    <li class="{{ setSidebar(['instructor.course*', 'instructor.course-section']) }}">
                        <a href="{{ route('instructor.course.index') }}"><i class='bx bx-radio-circle'></i>Tất cả khóa
                            học</a>
                    </li>

                </ul>
            </li>

            <li class="{{ setSidebar(['instructor.coupon*']) }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Quản lý mã giảm giá</div>
                </a>
                <ul>
                    <li class="{{ setSidebar(['instructor.coupon*']) }}">
                        <a href="{{ route('instructor.coupon.index') }}"><i class='bx bx-radio-circle'></i>Tất cả mã
                            giảm giá</a>
                    </li>

                </ul>
            </li>

            <li class="{{ setSidebar(['instructor.lecture-discussions*']) }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Quản lý thảo luận</div>
                </a>
                <ul>
                    <li class="{{ setSidebar(['instructor.lecture-discussions*']) }}">
                        <a href="{{ route('instructor.lecture-discussions.index') }}"><i
                                class='bx bx-radio-circle'></i>Tất cả thảo
                            luận</a>
                    </li>

                </ul>
            </li>

            <li class="{{ setSidebar(['instructor.order*']) }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Quản lý đơn hàng</div>
                </a>
                <ul>
                    <li class="{{ setSidebar(['instructor.order*']) }}">
                        <a href="{{ route('instructor.orders.index') }}"><i class='bx bx-radio-circle'></i>Tất cả đơn
                            hàng</a>
                    </li>

                </ul>
            </li>

            <li class="{{ setSidebar(['instructor.revenue*']) }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class="bx bx-category"></i>
                    </div>
                    <div class="menu-title">Quản lý doanh thu</div>
                </a>
                <ul>
                    <li class="{{ setSidebar(['instructor.revenue*']) }}">
                        <a href="{{ route('instructor.revenue.dashboard') }}"><i class='bx bx-radio-circle'></i>Tổng
                            quan</a>
                    </li>

                </ul>
            </li>
        @endif


    </ul>
    <!--end navigation-->
</div>
