<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Services\CartService;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PageController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $categoryRepository;
    protected $productRepository;
    protected $cartService;
    
    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
        $this->categoryRepository = new CategoryRepository(app());
        $this->productRepository = new ProductRepository(app());
        $this->cartService = new CartService();
    }
    
    public function productDetail(Request $request, $slug) {
        $product = $this->productRepository->where('slug', $slug)->first();
        
        $breadcrumbs = [
            [
                'title' => 'Trang chủ',
                'href' => '/'
            ],
            [
                'title' => $product->name,
                'href' => route('client.product.detail', $product->slug)
            ],
        ];
        
        $product->list_image = json_decode($product->list_image);
        
        return Inertia::render('Client/ProductDetail', [
            'product' => $product,
            'breadcrumbs' => $breadcrumbs,
            'title' => $product->name
        ]);
    }
    
    public function orderPage(Request $request) {
        $cart = $this->cartService->getCart();
        
        $listAddress = Address::with(['city', 'district', 'ward'])
            ->where('user_id', auth()->user()->id)->get();
        $arrdress = $listAddress
            ->where('is_default', 1)->first();
        
        return Inertia::render('Client/Order', [
            'title' => 'Trang thanh toán',
            'cart' => $cart,
            'listAddress' => $listAddress,
            'address' => $arrdress
        ]);
    }
    
    public function checkoutSuccess() {
        return Inertia::render('Client/CheckoutSuccess', [
            'title' => 'Thanh toán thành công'
        ]);
    }
    
    public function categoryPage(Request $request, $slug) {
        $category = $this->categoryRepository
            ->with(['products:id,name,slug,price,image_avatar,original_price'])
            ->where('slug', $slug)->first();
        
        $products = $category->products;
        return Inertia::render('Client/Category', [
            'title' => "Danh mục " . $category->name,
            'category' => $category,
            'products' => $products
        ]);
    }
    
    public function searchPage(Request $request) {
        $s = $request->input('s');
        
        if(!$s) {
            abort(404, 'Không tìm thấy sản phẩm');
        }
        
        $products = Product::select([
            'id', 'name', 'slug', 'price', 'image_avatar', 'original_price'
        ])
        ->where('name', 'like', '%' . $s . '%')
        ->get();
        
        
        return Inertia::render('Client/SearchPage', [
            'title' => 'Kết quả tìm kiếm cho từ khóa: ' . $s,
            's' => $s,
            'products' => $products
        ]);
    }
}
