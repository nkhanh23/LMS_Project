<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCoupontRequest;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return view('frontend.pages.cart.index');
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id', // kiểm tra course id có tồn tại trong courses
            'quantity' => 'required|integer|min:1',
        ]);
        $course_id = $validated['course_id'];
        return $this->cartService->createCart($course_id, $request);
    }

    public function cartAll(Request $request)
    {
        $cartItems = $this->cartService->viewCart($request);
        $subTotal = $this->calculateSubtotal($cartItems);

        $html = view('frontend.pages.home.partials.cart', [
            'cart' => $cartItems,
            'subTotal' => $subTotal,
        ])->render();
        return response()->json([
            'status' => 'success',
            'html' => $html,
            'cart_count' => $cartItems->count(),
        ]);
    }

    public function fetchCart(Request $request)
    {
        $cartItems = $this->cartService->viewCart($request);
        $subTotal = $this->calculateSubtotal($cartItems);

        $html = view('frontend.pages.cart.partial.main', [
            'cart' => $cartItems,
            'subTotal' => $subTotal,
        ])->render();
        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
    }

    private function calculateSubtotal($cartItems): int|float
    {
        return $cartItems->sum(function ($item) {
            $price = $item->course->discount_price ?? $item->course->selling_price;
            return $item->quantity * ($price ?? 0);
        });
    }

    public function removeCart(Request $request)
    {
        $cartItems = Cart::find($request->id);
        if (!$cartItems) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không tìm thấy sản phẩm trong giỏ hàng',
            ]);
        }
        $cartItems->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Xóa sản phẩm khỏi giỏ hàng thành công',
        ]);
    }
}
