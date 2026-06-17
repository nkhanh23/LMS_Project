<?php

/* Instructor Approved via admin */

use App\Models\User;
use App\Models\Category;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

if (!function_exists('isApprovedUser')) {
    function isApprovedUser()
    {
        $user_id = Auth::id();
        return User::where('role', 'instructor')
            ->where('status', '1')
            ->where('id', $user_id)
            ->first();
    }
}

/* Global use in category */


if (!function_exists('getCategories')) {
    function getCategories()
    {
        return \Illuminate\Support\Facades\Cache::remember('global_categories', now()->addHours(24), function () {
            return Category::select('id', 'name', 'slug', 'image')->with(['subCategory' => function ($query) {
                $query->select('id', 'category_id', 'name', 'slug');
            }])->orderBy('name', 'asc')->get();
        });
    }
}


/* bật tab sidebar*/
if (!function_exists('setSidebar')) {
    function setSidebar(array $routes): ?String
    {
        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return "mm-active";
            }
        }
        return null;
    }
}


// getCourseCategory
if (!function_exists('getCourseCategory')) {
    function getCourseCategory()
    {
        return Category::select('id', 'name', 'slug')
            ->with(['course' => function ($query) {
                $query->select('id', 'category_id', 'course_name', 'course_name_slug', 'course_image', 'selling_price', 'discount_price', 'label', 'bestseller', 'featured', 'highestrated');
            }])->orderBy('name', 'asc')->get();
    }
}

//get wishlist
if (!function_exists('getWishlist')) {
    function getWishlist()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            return Wishlist::where('user_id', $user_id)->with('course', 'course.user')->get();
        }
        return collect();
    }
}

//get cart items
if (!function_exists('getCartItems')) {
    function getCartItems()
    {
        $guestToken = request()->cookie('guest_token');
        if ($guestToken) {
            return \App\Models\Cart::where('guest_token', $guestToken)->with('course', 'course.user')->get();
        }
        return collect();
    }
}

//get cart count
if (!function_exists('getCartCount')) {
    function getCartCount()
    {
        $guestToken = request()->cookie('guest_token');
        if ($guestToken) {
            return \App\Models\Cart::where('guest_token', $guestToken)->count();
        }
        return 0;
    }
}

//global auth check
function auth_check_json()
{
    if (!Auth::check()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Bạn cần đăng nhập để thực hiện chức năng này',
        ], 401);
    }
}


function getVideoUrl($type, $url)
{
    if ($type === 'r2_video') {
        // Sinh link bảo mật có chữ ký (Presigned URL) hết hạn sau 15 phút
        return Storage::disk('r2')->temporaryUrl($url, now()->addMinutes(15));
    }

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }

    return asset($url);
}

function getYoutubeEmbedUrl($url)
{
    if (empty($url)) return null;
    // Biểu thức chính quy để tìm ID video (chuỗi 11 ký tự) từ nhiều dạng link YouTube khác nhau
    $regExp = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/';
    if (preg_match($regExp, $url, $match) && strlen($match[2]) == 11) {
        // Trả về định dạng chuẩn để nhét vào iframe: 
        return 'https://www.youtube.com/embed/' . $match[2];
    }

    return null;
}
