<?php

/* Instructor Approved via admin */

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

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
