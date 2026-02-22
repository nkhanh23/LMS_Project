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



    </ul>
    <!--end navigation-->
</div>
