<?php
namespace App\Services;

use App\Models\Address;
use App\Models\CartDetail;
use App\Models\CategoryProduct;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;

class CartService {
    protected $productRepository;
    protected $cartRepository;
    
    protected $product = null;
    protected $cart = null;
    protected $address = null;
    
    public function __construct()
    {
        $this->productRepository = new ProductRepository(app());
        $this->cartRepository = new CartRepository(app());
    }
    
    public function getCart($session_id = null) {
        $user_id = auth()->user()->id ?? null;
        $query = $this->cartRepository->query()->with([
            'cartDetail',  
            'cartDetail.product' => function ($q) {
                $q->select('id', 'name', 'image_avatar', 'slug', 'price', 'original_price');
            }
        ]);
        
        if(!empty($session_id)) {
            $query->where('session_id', $session_id);
        }
        
        if(!empty($user_id)) {
            $query->where('user_id', $user_id);
        }
        
        $this->cart = $query->first();
        // dd($this->cart);
        
        if(!$this->cart) {
            $address = $this->getAddressDefault(true);
            $input = [
                'session_id' => $session_id,
                'address_id' => $address->id ?? null
            ];
            $this->createCart($input);
        }
        $this->syncCart();
        return $this->cart;
    }
    
    public function createCart($input = []) {
        $dataCreate = [
            'user_id' => auth()->user()->id,
            'session_id' => $input['session_id'] ?? null,
            'total_price' => $input['total_price'] ?? 0,
            'final_price' => $input['final_price'] ?? 0,
            'address_id' => $input['address_id'] ?? null,
        ];
        
        $this->cart = $this->cartRepository->create($dataCreate);
    }
    
    public function createCartDetail($input = [], $product) {
        $dataCreate = [
            'cart_id' => auth()->user()->id,
            'product_id' => $input['session_id'] ?? null,
            'quantity' => $input['quantity'] ?? 0,
            'price' => $product->original_price ?? $product->price,
            'final_price' => $product->price,
        ];
        
        $this->cart = $this->cartRepository->create($dataCreate);
    }
    
    public function updateOrCreateCartDetail($input = [], $product) {
        if(!$this->cart){
            $this->getCart();
        }
        
        $session_id = $this->cart->session_id ?? null;
        $user_id = $this->cart->user_id ?? null;
        $cart_id = $this->cart->id ?? null;
        $product_id = $product->id;
        
        $cartDetail = CartDetail::where('cart_id', $cart_id)->where('product_id', $product_id)->first();
        
        if(!$cartDetail) {
            $cartDetail = new CartDetail();
            $cartDetail->cart_id = $cart_id;
            $cartDetail->product_id = $product_id;
            $cartDetail->quantity = 0;
        }
        
        $cartDetail->quantity = $cartDetail->quantity + $input['quantity'];
        $cartDetail->price = $product->price;
        $cartDetail->final_price = $cartDetail->quantity * $product->price;
        
        $cartDetail->save();
        
        $this->syncCart();
        $this->getCart();
        
        return true;
    }
    
    public function getAddressDefault($isSync = false) {
        $user_id = auth()->user()->id ?? null;
        
        if($this->address && !$isSync) {
            return $this->address;
        }
        
        $this->address = Address::where('user_id', $user_id)->where('is_default', 1)->first();
        
        return $this->address;
    }
    
    public function syncCart() {
        $user_id = auth()->user()->id ?? null;
        
        if(!$this->cart) {
            $this->getCart();
        }
        
        [$total_price, $final_price] = $this->updateTotalPrice();
        
        // cập nhật bảng cart
        $this->cart->total_price = $total_price;
        $this->cart->final_price = $final_price;
        // $this->cart->address_id = $address_id;
        $this->cart->save();
        
    }
    
    public function updateTotalPrice() {
        $total_price = 0;
        $final_price = 0;
        $cartDetails = $this->cart->cartDetail;
        
        foreach($cartDetails as $detail) {
            $totalPriceByProduct = $detail->final_price;
            $total_price += $totalPriceByProduct;
            $final_price += $totalPriceByProduct;
        }        
        
        return [
            $total_price,
            $final_price,
        ];
    }
    
    public function updateCartDetail($input = []) {
        if(!$this->cart) {
            $this->getCart();
        }
        
        $detail_id = $input['detail_id'] ?? null;
        $quantity = $input['quantity'] ?? 1;
        
        $cartDetail = $this->cart->cartDetail->where('id', $detail_id)->first();
        
        $cartDetail->quantity = $input['quantity'];
        $cartDetail->final_price = $cartDetail->quantity * $cartDetail->price;
        $cartDetail->save();
        
        $this->syncCart();
        $this->getCart();
        
        return true;
    }
    
    public function deleteCartDetail($id) {
        if(!$this->cart) {
            $this->getCart();
        }
        
        $cartDetail = $this->cart->cartDetail->where('id', $id)->first();
        $cartDetail->delete();
        
        return true;
    }
    
    
}