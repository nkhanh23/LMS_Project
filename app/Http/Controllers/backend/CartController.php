<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyCoupontRequest;
use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $cart = $this->cartService->viewCart($request);
        $guest_token = $request->cookie('guest_token') ?? Str::uuid();
        $cartItems = Cart::with('course')->where('guest_token', $guest_token)->get();
        // tính tổng tiền
        $subTotal = $cartItems->sum(function ($cartItems) {
            $price = $cartItems->course->discount_price ?? $cartItems->course->selling_price;
            return $cartItems->quantity * ($price ?? 0);
        });

        $html = view('frontend.pages.home.partials.cart', compact('cart', 'subTotal'))->render();
        return response()->json([
            'status' => 'success',
            'html' => $html,
            'cart_count' => $cartItems->count(),
        ]);
    }

    public function fetchCart(Request $request)
    {
        // sử dụng ajax để lấy cart
        $cart = $this->cartService->viewCart($request);
        // lấy guest token
        $guest_token = $request->cookie('guest_token') ?? Str::uuid();
        $cartItems = Cart::with('course')->where('guest_token', $guest_token)->get();

        // tính tổng tiền
        $subTotal = $cartItems->sum(function ($cartItems) {
            $price = $cartItems->course->discount_price ?? $cartItems->course->selling_price;
            return $cartItems->quantity * ($price ?? 0);
        });

        $html = view('frontend.pages.cart.partial.main', compact('cart', 'subTotal'))->render();
        return response()->json([
            'status' => 'success',
            'html' => $html,
        ]);
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
