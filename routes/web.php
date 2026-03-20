<?php


use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\backend\AdminApprovalCenterController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\AdminCourseController;
use App\Http\Controllers\backend\AdminInstructorController;
use App\Http\Controllers\backend\AdminInstructorRequestController;
use App\Http\Controllers\backend\AdminUserController;
use App\Http\Controllers\backend\BackendOrderController;
use App\Http\Controllers\backend\CartController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\CourseController;
use App\Http\Controllers\backend\CourseSectionController;
use App\Http\Controllers\backend\InfoController;
use App\Http\Controllers\backend\InstructorController;
use App\Http\Controllers\backend\InstructorLectureDiscussionController;
use App\Http\Controllers\backend\InstructorOrderController;
use App\Http\Controllers\backend\InstructorProfileController;
use App\Http\Controllers\backend\InstructorRequestController;
use App\Http\Controllers\backend\InstructorRevenueController;
use App\Http\Controllers\backend\LectureController;
use App\Http\Controllers\backend\PartnerController;
use App\Http\Controllers\backend\QuizController;
use App\Http\Controllers\backend\SettingController;
use App\Http\Controllers\backend\SiteSettingController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\backend\SocialController;
use App\Http\Controllers\backend\Subcategory;
use App\Http\Controllers\backend\SubcategoryController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\UserProfileController;
use App\Http\Controllers\frontend\CheckoutController;
use App\Http\Controllers\frontend\CourseReviewController;
use App\Http\Controllers\frontend\FrontEndDashBoardController;
use App\Http\Controllers\frontend\LearningController;
use App\Http\Controllers\frontend\LectureDiscussionController;
use App\Http\Controllers\frontend\LectureNoteController;
use App\Http\Controllers\frontend\OrderController;
use App\Http\Controllers\frontend\QuizAttempController;
use App\Http\Controllers\frontend\WishlistController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

/*  GOOGLE ROUTE  */

Route::get('/auth/google', [SocialController::class, 'googleLogin'])->name('auth.google');
Route::get('/auth/google/callback', [SocialController::class, 'googleAuthentication'])->name('auth.google-callback');



/*  ADMIN LOGIN  */

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');

Route::middleware('auth', 'verified', 'role:admin')->prefix('admin')->name('admin.')->group(function () {
    //Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    //Logout
    Route::post('/logout', [AdminController::class, 'destroy'])->name('logout');

    /*   ADMIN PROFILE   */
    //Profile
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    //Lưu profile
    Route::post('/profile/store', [AdminProfileController::class, 'store'])->name('profile.store');
    //Setting
    Route::get('/setting', [AdminProfileController::class, 'setting'])->name('setting');
    //Lưu setting
    Route::post('/password/setting', [AdminProfileController::class, 'passwordSetting'])->name('passwordSetting');

    /*   ADMIN CATEGORY   */
    //Danh sách category
    Route::resource('category', CategoryController::class);
    //Danh sách subcategory
    Route::resource('subcategory', SubcategoryController::class);

    /*   ADMIN SLIDER   */
    //Danh sách slider
    Route::resource('slider', SliderController::class);

    /*   ADMIN INFO   */
    Route::resource('info', InfoController::class);

    /* Control Instructor */
    //Danh sách instructor
    Route::resource('instructor', AdminInstructorController::class);
    //Cập nhật trạng thái
    Route::post('/update-status', [AdminInstructorController::class, 'updateStatus'])->name('instructor.status');
    //Danh sách instructor active
    Route::get('/instructor-active-list', [AdminInstructorController::class, 'instructorActive'])->name('instructor.active');

    /* Control User */
    //Danh sách user
    Route::resource('user', AdminUserController::class);
    //Cập nhật trạng thái
    Route::post('/user-status', [AdminUserController::class, 'updateStatus'])->name('user.status');
    //Danh sách user active
    Route::get('/user-active-list', [AdminUserController::class, 'userActive'])->name('user.active');

    /* Setting Controller */
    //Mail setting
    Route::get('/mail-setting', [SettingController::class, 'mailSetting'])->name('mail-setting');
    //Lưu mail setting
    Route::put('/mail-setting/update', [SettingController::class, 'updateMailSettings'])->name('mail-setting.update');

    //Stripe setting
    Route::get('/stripe-setting', [SettingController::class, 'stripeSetting'])->name('stripe-setting');
    //Lưu stripe setting
    Route::post('/stripe-setting/update', [SettingController::class, 'updateStripeSettings'])->name('stripe-setting.update');

    //Google setting
    Route::get('/google-setting', [SettingController::class, 'googleSetting'])->name('google-setting');
    //Lưu google setting
    Route::post('/google-settings/update', [SettingController::class, 'updateGoogleSettings'])->name('google-setting.update');

    /* Control Course */
    //Danh sách khóa học
    Route::resource('course', AdminCourseController::class);


    /* Order Controller */
    //Danh sách đơn hàng
    Route::resource('order', BackendOrderController::class);

    /* Partner Controller */
    //Danh sách partner
    Route::resource('partner', PartnerController::class);

    /* Manage Site Seetings */
    //Danh sách site setting
    Route::resource('site-setting', SiteSettingController::class);

    /* Manage Instructor Requests */
    //Danh sách yêu cầu trở thành instructor
    Route::get('/instructor-requests', [AdminInstructorRequestController::class, 'index'])
        ->name('instructor-requests.index');
    //Duyệt yêu cầu trở thành instructor
    Route::post('/instructor-requests/{id}/approve', [AdminInstructorRequestController::class, 'approve'])
        ->name('instructor-requests.approve');
    //Từ chối yêu cầu trở thành instructor
    Route::post('/instructor-requests/{id}/reject', [AdminInstructorRequestController::class, 'reject'])
        ->name('instructor-requests.reject');

    /* Manage Approval Center */
    //Danh sách approval center
    Route::get('/approval-center', [AdminApprovalCenterController::class, 'index'])
        ->name('approval-center.index');
    //Duyệt yêu cầu trở thành instructor
    Route::post('/approval-center/instructors/{id}/approve', [AdminApprovalCenterController::class, 'approveInstructor'])
        ->name('approval-center.instructors.approve');
    //Từ chối yêu cầu trở thành instructor
    Route::post('/approval-center/instructors/{id}/suspend', [AdminApprovalCenterController::class, 'suspendInstructor'])
        ->name('approval-center.instructors.suspend');
    //Duyệt khóa học
    Route::post('/approval-center/courses/{id}/publish', [AdminApprovalCenterController::class, 'publishCourse'])
        ->name('approval-center.courses.publish');
    //Từ chối khóa học
    Route::post('/approval-center/courses/{id}/reject', [AdminApprovalCenterController::class, 'rejectCourse'])
        ->name('approval-center.courses.reject');
    //Ẩn khóa học
    Route::post('/approval-center/courses/{id}/hide', [AdminApprovalCenterController::class, 'hideCourse'])
        ->name('approval-center.courses.hide');
});

/*  INSTRUCTOR LOGIN  */
Route::get('/instructor/login', [InstructorController::class, 'login'])->name('instructor.login');

Route::middleware('auth', 'verified', 'role:instructor', 'instructor.approved')->prefix('instructor')->name('instructor.')->group(function () {
    //Dashboard
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    //Logout
    Route::post('/logout', [InstructorController::class, 'destroy'])->name('logout');

    /*   INSTRUCTOR PROFILE   */
    //Profile
    Route::get('/profile', [InstructorProfileController::class, 'index'])->name('profile');
    //Lưu profile
    Route::post('/profile/store', [InstructorProfileController::class, 'store'])->name('profile.store');

    //Setting
    Route::get('/setting', [InstructorProfileController::class, 'setting'])->name('setting');
    //Lưu setting
    Route::post('/password/setting', [InstructorProfileController::class, 'passwordSetting'])->name('passwordSetting');

    /*  INSTRUCTOR COURSE  */
    //Danh sách khóa học
    Route::resource('course', CourseController::class);
    //Lấy danh sách subcategory
    Route::get('/get-subcategories/{categoryId}', [CategoryController::class, 'getSubcategories']);
    //Gửi khóa học để admin review
    Route::post('/course/{id}/submit-review', [CourseController::class, 'submitForReview'])
        ->name('course.submit-review');

    /*  INSTRUCTOR COURSE SECTION  */
    //Danh sách section
    Route::resource('course-section', CourseSectionController::class);

    /*  INSTRUCTOR COURSE LECTURE  */
    //Danh sách lecture
    Route::resource('lecture', LectureController::class);
    //Get presigned url
    Route::post('/lecture/get-presigned-url', [LectureController::class, 'generatePresignedUrl'])
        ->name('lecture.get-presigned-url');
    //Get presigned document url
    Route::post('/lecture/get-presigned-document-url', [LectureController::class, 'generateDocumentPresignedUrl'])
        ->name('lecture.getPresignedDocumentUrl');

    /*  INSTRUCTOR COUPON  */
    //Danh sách coupon
    Route::resource('coupon', CouponController::class);

    /*  INSTRUCTOR REVENUE  */
    //Dashboard doanh thu
    Route::get('/revenue-dashboard', [InstructorRevenueController::class, 'dashboard'])
        ->name('revenue.dashboard');

    /*  INSTRUCTOR ORDER  */
    //Danh sách đơn hàng
    Route::get('/orders', [InstructorOrderController::class, 'index'])
        ->name('orders.index');
    //Export đơn hàng
    Route::get('/orders/export/csv', [InstructorOrderController::class, 'exportCsv'])
        ->name('orders.export.csv');
    //Export đơn hàng excel
    Route::get('/orders/export/excel', [InstructorOrderController::class, 'exportExcel'])
        ->name('orders.export.excel');
    //Chi tiết đơn hàng
    Route::get('/orders/{id}', [InstructorOrderController::class, 'show'])
        ->name('orders.show');

    /*  INSTRUCTOR LECTURE DISCUSSION  */
    //Lấy danh sách bài học theo khóa học
    Route::get('/lecture-discussions/lectures-by-course', [InstructorLectureDiscussionController::class, 'getLecturesByCourse'])
        ->name('lecture-discussions.lectures-by-course');
    //Danh sách thảo luận
    Route::get('/lecture-discussions', [InstructorLectureDiscussionController::class, 'index'])
        ->name('lecture-discussions.index');
    //Chi tiết thảo luận
    Route::get('/lecture-discussions/{id}', [InstructorLectureDiscussionController::class, 'show'])
        ->name('lecture-discussions.show');
    //Duyệt thảo luận
    Route::patch('/lecture-discussions/{id}/approve', [InstructorLectureDiscussionController::class, 'approve'])
        ->name('lecture-discussions.approve');
    //Bỏ duyệt thảo luận
    Route::patch('/lecture-discussions/{id}/unapprove', [InstructorLectureDiscussionController::class, 'unapprove'])
        ->name('lecture-discussions.unapprove');
    //Xóa thảo luận
    Route::delete('/lecture-discussions/{id}', [InstructorLectureDiscussionController::class, 'destroy'])
        ->name('lecture-discussions.destroy');
    //Trả lời thảo luận
    Route::post('/lecture-discussions/{id}/reply', [InstructorLectureDiscussionController::class, 'reply'])
        ->name('lecture-discussions.reply');

    /*  INSTRUCTOR QUIZ  */
    //Danh sách quiz
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    //Edit quiz
    Route::get('/quiz/{lecture}/edit', [QuizController::class, 'edit'])->name('quiz.edit');
    //Lưu quiz
    Route::post('/quiz/{lecture}', [QuizController::class, 'storeOrUpdate'])->name('quiz.store_or_update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*  USER ROUTE LIST  */
Route::middleware('auth', 'verified', 'role:user')->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [UserController::class, 'destroy'])->name('logout');

    /*   USER PROFILE   */
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/store', [UserProfileController::class, 'store'])->name('profile.store');
    Route::post('/password/setting', [UserProfileController::class, 'passwordSetting'])->name('passwordSetting');
    Route::post('/email/setting', [UserProfileController::class, 'emailSetting'])->name('emailSetting');

    /*  USER WISHLIST  */
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/wishlist-data', [WishlistController::class, 'getWishlist']);
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');

    /*  USER BECOME INSTRUCTOR  */
    Route::get('/become-instructor', [InstructorRequestController::class, 'create'])
        ->name('become-instructor.create');

    Route::post('/become-instructor', [InstructorRequestController::class, 'store'])
        ->name('become-instructor.store');
});

/*  FRONTEND ROUTES  */
Route::get('/', [FrontEndDashBoardController::class, 'home'])->name('frontend.home');
Route::get('/chi-tiet/{slug}', [FrontEndDashBoardController::class, 'view'])->name('chi-tiet');

/*  WISHLIST ROUTES  */
Route::get('/wishlist/all', [WishlistController::class, 'allWishlist'])->name('wishlist');
Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist']);

/*  CART ROUTES  */
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/all', [CartController::class, 'cartAll'])->name('cart');
Route::get('/fetch/cart', [CartController::class, 'fetchCart']);
Route::post('/remove/cart', [CartController::class, 'removeCart']);

/*  CHECKOUT ROUTES  */
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

/*  CHECKOUT ROUTES  */
Route::post('/apply-coupon', [CouponController::class, 'applyCoupon']);

/*  CHECKOUT COUPON  */
Route::post('/apply-checkout-coupon', [CouponController::class, 'applyCheckoutCoupon'])->name('checkoutCoupon');
Route::post('/remove-coupon', [CouponController::class, 'removeCoupon'])->name('removeCoupon');

/*  COURSE PLAY  */
// Route mở bài học đầu tiên hoặc bài học cuối cùng đang học dở
Route::get('/khoa-hoc/{slug}/hoc', [LearningController::class, 'playCourse'])->name('course.play');
// Route xem một bài giảng cụ thể
Route::get('/khoa-hoc/{slug}/bai-hoc/{lecture_id}', [LearningController::class, 'watchLecture'])->name('course.lecture.watch');


/*  AUTH PROTECTED ROUTES  */
Route::middleware('auth')->group(function () {
    Route::post('/order', [OrderController::class, 'order'])->name('order');
    Route::get('/payment-success', [OrderController::class, 'success'])->name('success');
    Route::get('/payment-cancel', [OrderController::class, 'cancel'])->name('cancel');

    /*  COURSE REVIEW ROUTES  */
    Route::post('/chi-tiet/{slug}/review', [CourseReviewController::class, 'store'])->name('course-review.store');

    /*  COURSE DOWNLOAD DOCUMENT ROUTES  */
    Route::get('/lecture/{lecture}/download-document', [LectureController::class, 'downloadDocument'])
        ->name('lecture.downloadDocument');

    /*  LECTURE DISCUSSION ROUTES  */
    Route::post('/lecture/discussion', [LectureDiscussionController::class, 'store'])
        ->name('lecture.discussion.store');
    // lấy tất cả bình luận của bài giảng
    Route::get('/lecture/{lecture}/discussions', [LectureDiscussionController::class, 'index'])
        ->name('lecture.discussion.index');
    // xóa bình luận
    Route::delete('/lecture/discussion/{discussion}', [LectureDiscussionController::class, 'destroy'])
        ->name('lecture.discussion.destroy');
    Route::get('/learning/lecture/{lecture}/data', [LearningController::class, 'getLectureData'])
        ->name('learning.lecture.data');

    /*  LECTURE NOTE ROUTES  */
    Route::get('/learning/lecture/{lecture}/notes', [LectureNoteController::class, 'index'])->name('lecture.notes.index');
    Route::post('/learning/notes', [LectureNoteController::class, 'store'])->name('lecture.notes.store');
    Route::patch('/learning/notes/{id}', [LectureNoteController::class, 'update'])->name('lecture.notes.update');
    Route::delete('/learning/notes/{id}', [LectureNoteController::class, 'destroy'])->name('lecture.notes.destroy');
    Route::post('/quiz/{quiz}/submit', [QuizAttempController::class, 'submit'])->name('quiz.submit');
});



require __DIR__ . '/auth.php';
