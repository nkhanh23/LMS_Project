<?php


use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\AdminInstructorController;
use App\Http\Controllers\backend\CategoryController;
use App\Http\Controllers\backend\CourseController;
use App\Http\Controllers\backend\CourseSectionController;
use App\Http\Controllers\backend\InfoController;
use App\Http\Controllers\backend\InstructorController;
use App\Http\Controllers\backend\InstructorProfileController;
use App\Http\Controllers\backend\LectureController;
use App\Http\Controllers\backend\SliderController;
use App\Http\Controllers\backend\Subcategory;
use App\Http\Controllers\backend\SubcategoryController;
use App\Http\Controllers\frontend\FrontEndDashBoardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*  FRONTEND ROUTES  */
Route::get('/', [FrontEndDashBoardController::class, 'home'])->name('frontend.home');
Route::get('/course-details/{slug}', [FrontEndDashBoardController::class, 'view'])->name('course-details');

require __DIR__ . '/auth.php';
