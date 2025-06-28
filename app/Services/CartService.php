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
                $q->select('id', 'name', 'image_avatar', 'slug');
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
        }
        
        $cartDetail->quantity = $input['quantity'] ?? $cartDetail->quantity;
        $cartDetail->price = $product->original_price ?? $product->price;
        $cartDetail->final_price = $product->price;
        
        $cartDetail->save();
        
        $this->getCart();
        
        return true;
    }
    
    public function getAddressDefault($isSync = false) {
        $user_id = auth()->user()->id ?? null;
        
        if($this->address && !$isSync) {
            return $this->address;
        }
        
        $this->address = Address::where('user_id', $user_id)->where('is_defaut', 1)->first();
        
        return $this->address;
    }
    
}