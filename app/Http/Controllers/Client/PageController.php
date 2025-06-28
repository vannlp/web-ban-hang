<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
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
    
    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
        $this->categoryRepository = new CategoryRepository(app());
        $this->productRepository = new ProductRepository(app());
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
}
