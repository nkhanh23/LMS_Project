<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">ADMIN</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li class="{{ setSidebar(['admin.dashboard']) }}">
            <a href="javascript:;" class="">
                <div class="parent-icon"><i class='bx bx-category'></i>
                </div>
                <div class="menu-title">Tổng quan</div>
            </a>
        </li>
        <li class="{{ setSidebar(['admin.category*', 'admin.subcategory*']) }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Quản lý danh mục</div>
            </a>
            <ul>
                <li class="{{ setSidebar(['admin.category*']) }}">
                    <a href="{{ route('admin.category.index') }}"><i class='bx bx-radio-circle'></i>Danh mục</a>
                </li>
                <li class="{{ setSidebar(['admin.subcategory*']) }}">
                    <a href="{{ route('admin.subcategory.index') }}"><i class='bx bx-radio-circle'></i>Danh mục con</a>
                </li>

            </ul>
        </li>

        <li class="{{ setSidebar(['admin.instructor*']) }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Quản lý giảng viên</div>
            </a>
            <ul>
                <li class="{{ setSidebar(['admin.instructor*']) }}">
                    <a href="{{ route('admin.instructor.index') }}"><i class='bx bx-radio-circle'></i>Giảng viên</a>
                </li>
                <li class="{{ setSidebar(['admin.instructor*']) }}">
                    <a href="{{ route('admin.instructor.active') }}"><i class='bx bx-radio-circle'></i>Đang hoạt
                        động</a>
                </li>
                <li class="{{ setSidebar(['admin.instructor*']) }}">
                    <a href="{{ route('admin.instructor-requests.index') }}"><i class='bx bx-radio-circle'></i>Yêu
                        cầu</a>
                </li>
            </ul>
        </li>

        <li class="{{ setSidebar(['admin.user*']) }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Quản lý người dùng</div>
            </a>
            <ul>
                <li class="{{ setSidebar(['admin.user*']) }}">
                    <a href="{{ route('admin.user.index') }}"><i class='bx bx-radio-circle'></i>Người dùng</a>
                </li>
                <li class="{{ setSidebar(['admin.user*']) }}">
                    <a href="{{ route('admin.user.active') }}"><i class='bx bx-radio-circle'></i>Đang hoạt
                        động</a>
                </li>
                <li class="{{ setSidebar(['admin.user.account-deletion*']) }}">
                    <a href="{{ route('admin.user.account-deletion.index') }}"><i class='bx bx-radio-circle'></i>Yêu cầu xóa tài khoản</a>
                </li>
            </ul>
        </li>

        <li class="{{ setSidebar(['admin.slider*']) }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Quản lý ứng dụng</div>
            </a>
            <ul>
                <li class="{{ setSidebar(['admin.slider*']) }}">
                    <a href="{{ route('admin.slider.index') }}"><i class='bx bx-radio-circle'></i>Slider</a>
                </li>
                <li class="{{ setSidebar(['admin.info*']) }}">
                    <a href="{{ route('admin.info.index') }}"><i class='bx bx-radio-circle'></i>Thông tin</a>
                </li>
                <li class="{{ setSidebar(['admin.partner*']) }}">
                    <a href="{{ route('admin.partner.index') }}"><i class='bx bx-radio-circle'></i>Nhà tài trợ</a>
                </li>
                <li class="{{ setSidebar(['admin.site-setting*']) }}">
                    <a href="{{ route('admin.site-setting.index') }}"><i class='bx bx-radio-circle'></i>Cấu hình
                        Site</a>
                </li>
            </ul>
        </li>

        <li class="{{ setSidebar(['admin.stripeSetting*']) }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Quản lý cài đặt</div>
            </a>
            <ul>
                <li class="{{ setSidebar(['admin.mail-setting*']) }}">
                    <a href="{{ route('admin.mail-setting') }}"><i class='bx bx-radio-circle'></i>Mail</a>
                </li>

                <li class="{{ setSidebar(['admin.stripe-setting*']) }}">
                    <a href="{{ route('admin.stripe-setting') }}"><i class='bx bx-radio-circle'></i>Stripe</a>
                </li>

                <li class="{{ setSidebar(['admin.google-setting*']) }}">
                    <a href="{{ route('admin.google-setting') }}"><i class='bx bx-radio-circle'></i>Google</a>
                </li>

                <li class="{{ setSidebar(['admin.gemini-setting*']) }}">
                    <a href="{{ route('admin.gemini-setting') }}"><i class='bx bx-radio-circle'></i>Gemini</a>
                </li>

            </ul>
        </li>

        <li class="{{ setSidebar(['admin.course*']) }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Quản lý khóa học</div>
            </a>
            <ul>
                <li class="{{ setSidebar(['admin.course*']) }}">
                    <a href="{{ route('admin.course.index') }}"><i class='bx bx-radio-circle'></i>Khóa học</a>
                </li>
                <li class="{{ setSidebar(['admin.course-approvals*']) }}">
                    <a href="{{ route('admin.course-approvals.index') }}"><i class='bx bx-radio-circle'></i>Duyệt
                        khóa học</a>
                </li>
            </ul>
        </li>

        <li class="{{ setSidebar(['admin.order*']) }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Quản lý đơn hàngs</div>
            </a>
            <ul>
                <li class="{{ setSidebar(['admin.order*']) }}">
                    <a href="{{ route('admin.order.index') }}"><i class='bx bx-radio-circle'></i>Đơn hàng</a>
                </li>
                <li class="{{ setSidebar(['admin.orders.refund_requests*']) }}">
                    <a href="{{ route('admin.orders.refund_requests.index') }}"><i class='bx bx-radio-circle'></i>Yêu
                        cầu hoàn tiền</a>
                </li>

            </ul>
        </li>

        <li class="{{ setSidebar(['admin.report*']) }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">Quản lý báo cáo</div>
            </a>
            <ul>
                <li class="{{ setSidebar(['admin.moderation.reports*']) }}">
                    <a href="{{ route('admin.moderation.reports.index') }}">
                        <i class="fas fa-flag"></i>
                        <span>Báo cáo</span>
                    </a>
                </li>

                <li class="{{ setSidebar(['admin.audit-logs*']) }}">
                    <a href="{{ route('admin.audit-logs.index') }}">
                        <i class="fas fa-history"></i>
                        <span>Nhật ký hoạt động</span>
                    </a>
                </li>


            </ul>
        </li>
        <li class="{{ setSidebar(['admin.system-health*']) }}">
            <a href="{{ route('admin.system-health.index') }}">
                <i class='bx bx-radio-circle'></i>
                <div class="menu-title">Sức khỏe hệ thống</div>
            </a>
        </li>
        <li class="{{ setSidebar(['admin.payouts*']) }}">
            <a href="{{ route('admin.payouts.index') }}">
                <i class='bx bx-radio-circle'></i>
                <div class="menu-title">Rút tiền giảng viên</div>
            </a>
        </li>
        <li class="{{ setSidebar(['admin.approval-center*']) }}">
            <a href="{{ route('admin.approval-center.index') }}">
                <i class='bx bx-radio-circle'></i>
                <div class="menu-title">Trung tâm phê duyệt</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
