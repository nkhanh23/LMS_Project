<?php


use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\AdminCourseController;
use App\Http\Controllers\backend\AdminInstructorController;
use App\Http\Controllers\backend\AdminUserController;
use App\Http\Controllers\backend\BackendOrderController;
use App\Http\Controllers\backend\CartController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CouponController;
use App\Http\Controllers\backend\CourseController;
use App\Http\Controllers\backend\CourseSectionController;
use App\Http\Controllers\backend\InfoController;
use App\Http\Controllers\backend\InstructorController;
use App\Http\Controllers\backend\InstructorProfileController;
use App\Http\Controllers\backend\LectureController;
use App\Http\Controllers\backend\PartnerController;
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
use App\Http\Controllers\frontend\OrderController;
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
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'destroy'])->name('logout');

    /*   ADMIN PROFILE   */
    Route::get('/profile', [AdminProfileController::class, 'index'])->name('profile');
    Route::post('/profile/store', [AdminProfileController::class, 'store'])->name('profile.store');
    Route::get('/setting', [AdminProfileController::class, 'setting'])->name('setting');
    Route::post('/password/setting', [AdminProfileController::class, 'passwordSetting'])->name('passwordSetting');

    /*   ADMIN CATEGORY   */
    Route::resource('category', CategoryController::class);
    Route::resource('subcategory', SubcategoryController::class);

    /*   ADMIN SLIDER   */
    Route::resource('slider', SliderController::class);

    /*   ADMIN INFO   */
    Route::resource('info', InfoController::class);

    /* Control Instructor */
    Route::resource('instructor', AdminInstructorController::class);
    Route::post('/update-status', [AdminInstructorController::class, 'updateStatus'])->name('instructor.status');
    Route::get('/instructor-active-list', [AdminInstructorController::class, 'instructorActive'])->name('instructor.active');

    /* Control User */
    Route::resource('user', AdminUserController::class);
    Route::post('/user-status', [AdminUserController::class, 'updateStatus'])->name('user.status');
    Route::get('/user-active-list', [AdminUserController::class, 'userActive'])->name('user.active');

    /* Setting Controller */
    Route::get('/mail-setting', [SettingController::class, 'mailSetting'])->name('mail-setting');
    Route::put('/mail-setting/update', [SettingController::class, 'updateMailSettings'])->name('mail-setting.update');

    Route::get('/stripe-setting', [SettingController::class, 'stripeSetting'])->name('stripe-setting');
    Route::post('/stripe-setting/update', [SettingController::class, 'updateStripeSettings'])->name('stripe-setting.update');

    Route::get('/google-setting', [SettingController::class, 'googleSetting'])->name('google-setting');
    Route::post('/google-settings/update', [SettingController::class, 'updateGoogleSettings'])->name('google-setting.update');

    /* Control Course */
    Route::resource('course', AdminCourseController::class);
    Route::post('/course-status', [AdminCourseController::class, 'courseStatus'])->name('course.status');

    /* Order Controller */
    Route::resource('order', BackendOrderController::class);

    /* Partner Controller */
    Route::resource('partner', PartnerController::class);

    /* Manage Site Seetings */
    Route::resource('site-setting', SiteSettingController::class);
});

/*  INSTRUCTOR LOGIN  */
Route::get('/instructor/login', [InstructorController::class, 'login'])->name('instructor.login');

Route::middleware('auth', 'verified', 'role:instructor')->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [InstructorController::class, 'destroy'])->name('logout');

    /*   INSTRUCTOR PROFILE   */
    Route::get('/profile', [InstructorProfileController::class, 'index'])->name('profile');
    Route::post('/profile/store', [InstructorProfileController::class, 'store'])->name('profile.store');

    Route::get('/setting', [InstructorProfileController::class, 'setting'])->name('setting');
    Route::post('/password/setting', [InstructorProfileController::class, 'passwordSetting'])->name('passwordSetting');

    /*  INSTRUCTOR COURSE  */
    Route::resource('course', CourseController::class);
    Route::get('/get-subcategories/{categoryId}', [CategoryController::class, 'getSubcategories']);

    /*  INSTRUCTOR COURSE SECTION  */
    Route::resource('course-section', CourseSectionController::class);

    /*  INSTRUCTOR COURSE LECTURE  */
    Route::resource('lecture', LectureController::class);
    Route::post('/lecture/get-presigned-url', [LectureController::class, 'generatePresignedUrl'])
        ->name('lecture.get-presigned-url');
    Route::post('/lecture/get-presigned-document-url', [LectureController::class, 'generateDocumentPresignedUrl'])
        ->name('lecture.getPresignedDocumentUrl');

    /*  INSTRUCTOR COUPON  */
    Route::resource('coupon', CouponController::class);
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
});



require __DIR__ . '/auth.php';
