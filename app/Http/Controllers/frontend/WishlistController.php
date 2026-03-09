<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\\Services\\WishlistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    protected $wishlistService;

    public function __construct(WishlistService $wishlistService)
    {
        $this->wishlistService = $wishlistService;
    }

    public function index()
    {
        $user_id = Auth::user()->id;
        $wishlist = Wishlist::where('user_id', $user_id)->with('course')->get();
        return view('backend.user.wishlist.index', compact('wishlist'));
    }

    public function getWishlist()
    {
        $user_id = Auth::user()->id;
        $wishlist = Wishlist::where('user_id', $user_id)->with('course', 'course.user')->paginate(6);
        $html = view('backend.user.section.partials.wishlist', compact('wishlist'))->render();
        return response()->json([
            'status' => 'success',
            'wishlist' => $wishlist,
            'html' => $html,
        ]);
    }

    public function addToWishlist(Request $request)
    {
        $validated_data = $request->validate([
            'course_id' => 'required|exists:courses,id', // Kiểm tra xem course_id có tồn tại trong courses table hay không
        ]);

        $course_id = $validated_data['course_id'];

        return $this->wishlistService->createWishlist($course_id);
    }

    public function allWishlist()
    {
        $authResponse = auth_check_json();
        if ($authResponse) {
            return $authResponse;
        }
        $user_id = Auth::user()->id;
        $wishlistItems = Wishlist::where('user_id', $user_id)->with('course', 'course.user')->get();
        $html = view('frontend.pages.home.partials.wishlist', compact('wishlistItems'))->render();
        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::find($id);
        if ($wishlist) {
            $wishlist->delete();
            $userId = Auth::user()->id;
            $wishlistCount = Wishlist::where('user_id', $userId)->count();
            return response()->json([
                'status' => 'success',
                'message' => 'Xóa khỏi danh sách yêu thích thành công',
                'wishlist_count' => $wishlistCount,
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy khóa học trong danh sách yêu thích',
            ]);
        }
    }
}
