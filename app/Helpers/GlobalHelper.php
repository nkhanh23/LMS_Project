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
        return Category::with('subCategory')->orderBy('name', 'asc')->get();
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
        return Category::with('course, course.user, course.course_goals')->orderBy('name', 'asc')->get();
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
        return Storage::disk('r2')->url($url);
    }

    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }

    return asset($url);
}
