<?php

namespace App\Http\Controllers;

use App\Helpers\Response;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\District;
use App\Models\Product;
use App\Models\Ward;
use App\Services\CartService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class CartController extends Controller
{
    protected $cartService;
    
    public function __construct()
    {
        $this->cartService = new CartService();
    }
    
    public function cartPage(Request $request) {
        if(!$request->user()) {
            return redirect()->route('client.login');
        }
        
        $cart = $this->cartService->getCart();
        
        return Inertia::render('Client/Cart', [
            'title' => "Giỏ hàng của bạn",
            'cart' => $cart,
        ]);
    }
    
    public function addToCart(Request $request) {
        $product_id = $request->product_id;
        $user_id = $request->user_id;
        $quantity = $request->quantity ?? 1;
        
        if(empty($product_id) && empty($user_id)) {
            return Response::fail("Vui lòng nhập product_id và user_id", 400);
        }
        
        try {
            DB::beginTransaction();
            $this->cartService->getCart();
            $input = [
                'quantity' => $quantity,
            ];
            $product = Product::find($product_id);
            
            $success = $this->cartService->updateOrCreateCartDetail($input, $product);
            
            DB::commit();
            
            return Response::success("Thêm sản phẩm vào giỏ hàng thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("CartController::addToCart" ,['exception' => $th]);
            return Response::fail("Thêm sản phẩm vào giỏ hàng thất bại", 500);
        }
    }
    
    public function updateCart(Request $request) {
        
    }
}
