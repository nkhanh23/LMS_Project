<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseReviews;
use App\Models\CourseSection;
use App\Models\InfoBox;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Wishlist;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class FrontEndDashBoardController extends Controller
{
    public function home()
    {
        $wishlistCourseIds = collect();
        if (Auth::check()) {
            $wishlistCourseIds = Wishlist::query()
                ->where('user_id', Auth::id())
                ->pluck('course_id')
                ->flip();
        }

        $all_slider = Cache::remember('frontend.home.sliders', now()->addMinutes(5), function () {
            return Slider::query()
                ->select('id', 'title', 'short_description', 'video_url', 'image')
                ->latest()
                ->get();
        });

        $all_info = Cache::remember('frontend.home.info_boxes', now()->addMinutes(5), function () {
            return InfoBox::query()
                ->select('id', 'icon', 'title', 'description')
                ->latest()
                ->get();
        });

        $all_category = Cache::remember('frontend.home.featured_categories', now()->addMinutes(5), function () {
            return Category::query()
                ->select('id', 'name', 'slug', 'image')
                ->inRandomOrder()
                ->limit(6)
                ->get();
        });

        $all_partners = Cache::remember('frontend.home.partners', now()->addMinutes(5), function () {
            return Partner::query()
                ->select('id', 'name')
                ->latest()
                ->get();
        });

        $categories = Cache::remember('frontend.home.categories', now()->addMinutes(5), function () {
            return Category::query()
                ->select('id', 'name', 'slug')
                ->orderBy('name', 'asc')
                ->get();
        });

        $course_category = Cache::remember('frontend.home.course_category', now()->addMinutes(5), function () {
            return Category::query()
                ->select('id', 'name', 'slug')
                ->with(['course' => function ($query) {
                    $query->where('approval_status', 'published')
                        ->where('status', 1)
                        ->select(
                            'id',
                            'category_id',
                            'instructor_id',
                            'course_name',
                            'course_name_slug',
                            'course_image',
                            'selling_price',
                            'discount_price',
                            'bestseller',
                            'featured',
                            'highestrated',
                            'label',
                            'description'
                        )
                        ->with(['user:id,name', 'goals'])
                        ->latest();
                }])
                ->orderBy('name', 'asc')
                ->get();
        });

        return view('frontend.index', compact('all_slider', 'all_info', 'all_category', 'categories', 'course_category', 'all_partners', 'wishlistCourseIds'));
    }

    public function view($slug)
    {
        //lấy khóa học
        $course = Course::where('course_name_slug', $slug)
            ->where('approval_status', 'published')
            ->where('status', 1)
            ->with('category', 'subcategory', 'user', 'goals')
            ->firstOrFail();

        //lấy số lượng bài giảng và nội dung bài giảng cùng lúc
        $course_content = CourseSection::where('course_id', $course->id)
            ->with(['lecture' => function($query) {
                $query->select('id', 'section_id', 'lecture_title', 'video_duration', 'type', 'url', 'is_preview');
            }])->get();
        $total_lecture = $course_content->count();

        //lấy id người dùng hiện tại
        $userId = Auth::id();

        //lấy khóa học có cùng category_id
        $similarCourse = Course::where('category_id', $course->category_id)
            ->where('id', '!=', $course->id)
            ->where('approval_status', 'published')
            ->where('status', 1)
            ->select('id', 'instructor_id', 'course_name', 'course_name_slug', 'course_image', 'selling_price', 'discount_price')
            ->with(['user' => function($q) { $q->select('id', 'name'); }])
            ->inRandomOrder()
            ->limit(6)
            ->get();

        //lấy khóa học có cùng instructor_id
        $more_course_instructor = Course::where('instructor_id', $course->instructor_id)
            ->where('id', '!=', $course->id)
            ->where('approval_status', 'published')
            ->where('status', 1)
            ->select('id', 'instructor_id', 'course_name', 'course_name_slug', 'course_image', 'selling_price', 'discount_price')
            ->with(['user' => function($q) { $q->select('id', 'name'); }])
            ->limit(6)
            ->get();

        //lấy tất cả danh mục
        $all_category = Category::select('id', 'name', 'slug')->orderBy('name', 'asc')->get();

        //lấy tổng số phút của khóa học (sử dụng cache hoặc tập hợp kết quả từ $course_content nếu cần, nhưng sum vẫn ổn)
        $total_minutes = CourseLecture::where('course_id', $course->id)->sum('video_duration');
        $hours = floor($total_minutes / 60);
        $minutes = floor($total_minutes % 60);
        $seconds = round(($total_minutes - floor($total_minutes)) * 60);
        $total_lecture_duration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        //kiểm tra xem người dùng đã mua khóa học chưa
        $hasPurchased = false;
        if (Auth::check()) {
            $hasPurchased = Order::where('user_id', Auth::id())
                ->where('course_id', $course->id)
                ->where('status', 'completed')
                ->whereNotIn('refund_status', ['approved', 'processed'])
                ->whereNull('access_revoked_at')
                ->exists();
        }

        //lấy đánh giá của khóa học
        $reviews = CourseReviews::where('course_id', $course->id)
            ->where('is_approved', true)
            ->with(['user' => function($q) { $q->select('id', 'name', 'image'); }])
            ->latest()
            ->paginate(5);

        //kiểm tra xem người dùng đã đánh giá khóa học chưa
        $userReview = null;
        if (Auth::check()) {
            $userReview = CourseReviews::where('course_id', $course->id)
                ->where('user_id', Auth::id())
                ->first();
        }

        //lấy các thông số đánh giá (chỉ chạy một lần)
        $ratingStats = CourseReviews::where('course_id', $course->id)
            ->where('is_approved', true)
            ->selectRaw('AVG(rating) as average, COUNT(*) as count')
            ->first();

        $ratingAverage = round($ratingStats->average ?? 0, 1);
        $ratingCount = $ratingStats->count;

        //lấy số lượng đánh giá theo sao
        $ratingBreakdown = CourseReviews::selectRaw('rating, COUNT(*) as total')
            ->where('course_id', $course->id)
            ->where('is_approved', true)
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        //lấy phần trăm đánh giá
        $ratingPercent = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratingBreakdown[$i] ?? 0;
            $ratingPercent[$i] = $ratingCount > 0 ? round(($count / $ratingCount) * 100) : 0;
        }

        return view('frontend.pages.course-details.index', compact(
            'course',
            'course_content',
            'total_lecture',
            'userId',
            'similarCourse',
            'more_course_instructor',
            'all_category',
            'hours',
            'minutes',
            'seconds',
            'total_lecture_duration',
            'reviews',
            'ratingAverage',
            'ratingCount',
            'ratingBreakdown',
            'hasPurchased',
            'userReview',
            'ratingPercent'
        ));
    }

    public function courses(Request $request)
    {
        $rating = $request->get('rating');
        $sort = $request->get('sort', 'relevant');
        $keyword = trim($request->get('q', ''));

        // Nhận thêm tham số lọc từ URL
        $categoryId = $request->get('category');
        $subcategoryId = $request->get('subcategory');

        // Lấy danh sách Category kèm Subcategory để render bộ lọc ở View
        $categories = Category::with('subcategory')->orderBy('name', 'asc')->get();

        $query = Course::query()
            ->where('approval_status', 'published')
            ->where('status', 1)
            ->select('id', 'instructor_id', 'category_id', 'course_name', 'course_name_slug', 'course_image', 'selling_price', 'discount_price', 'bestseller', 'description', 'created_at')
            ->with([
                'category:id,name',
                'user:id,name',
            ])
            ->withAvg(['reviews' => function ($q) {
                $q->where('is_approved', true);
            }], 'rating')
            ->withCount([
                'reviews' => function ($q) {
                    $q->where('is_approved', true);
                },
                'lectures as lectures_count',
            ])
            ->withSum('lectures as lectures_duration_sum', 'video_duration');

        // Lọc theo từ khóa tìm kiếm
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where('course_name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
        }

        // Thêm logic lọc theo danh mục
        $query->when($categoryId, function ($q, $categoryId) {
            return $q->where('category_id', $categoryId);
        });

        // Thêm logic lọc theo danh mục con
        $query->when($subcategoryId, function ($q, $subcategoryId) {
            return $q->where('subcategory_id', $subcategoryId);
        });

        if ($rating) {
            $query->having('reviews_avg_rating', '>=', $rating);
        }

        if ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'highest_rated') {
            $query->orderBy('reviews_avg_rating', 'desc');
        } else {
            $query->orderBy('created_at', 'desc'); // Default
        }

        $courses = $query->paginate(10)->withQueryString();

        // Truyền thêm biến $categories và $keyword sang View
        return view('frontend.pages.courses.index', compact('courses', 'categories', 'keyword'));
    }

    /**
     * API endpoint: gợi ý tìm kiếm nhanh (autocomplete)
     */
    public function searchSuggestions(Request $request)
    {
        $keyword = trim($request->get('q', ''));
        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'frontend.search_suggestions.' . md5($keyword);
        $courses = Cache::remember($cacheKey, now()->addSeconds(30), function () use ($keyword) {
            return Course::query()
                ->where('approval_status', 'published')
                ->where('status', 1)
                ->where('course_name', 'like', '%' . $keyword . '%')
                ->select('id', 'course_name', 'course_name_slug', 'course_image', 'discount_price', 'instructor_id', 'category_id')
                ->with(['user:id,name', 'category:id,name'])
                ->limit(6)
                ->get()
                ->map(fn ($c) => [
                    'id'    => $c->id,
                    'name'  => $c->course_name,
                    'slug'  => $c->course_name_slug,
                    'image' => asset($c->course_image),
                    'price' => number_format($c->discount_price, 0, ',', '.') . ' ₫',
                    'instructor' => $c->user->name ?? '',
                    'category'   => $c->category->name ?? '',
                    'url'   => route('chi-tiet', $c->course_name_slug),
                ]);
        });

        return response()->json($courses);
    }

    public function instructorProfile($id)
    {
        $instructor = \App\Models\User::where('id', $id)
            ->where('role', 'instructor')
            ->where('status', '1')
            ->firstOrFail();

        $courses = Course::where('instructor_id', $id)
            ->where('approval_status', 'published')
            ->where('status', 1)
            ->with(['category', 'user'])
            ->withAvg(['reviews' => function ($q) {
                $q->where('is_approved', true);
            }], 'rating')
            ->withCount(['reviews' => function ($q) {
                $q->where('is_approved', true);
            }])
            ->latest()
            ->get();

        // Calculate total students (unique users who purchased instructor's courses)
        $totalStudents = Order::where('instructor_id', $id)
            ->where('status', 'completed')
            ->distinct('user_id')
            ->count('user_id');

        // Calculate total reviews and average rating for instructor
        $totalReviews = 0;
        $totalRating = 0;
        $courseWithReviewsCount = 0;

        foreach ($courses as $course) {
            $totalReviews += $course->reviews_count;
            if ($course->reviews_avg_rating > 0) {
                $totalRating += $course->reviews_avg_rating;
                $courseWithReviewsCount++;
            }
        }

        $avgRating = $courseWithReviewsCount > 0 ? round($totalRating / $courseWithReviewsCount, 1) : 0;

        return view('frontend.pages.instructor.profile', compact('instructor', 'courses', 'totalStudents', 'totalReviews', 'avgRating'));
    }
}
