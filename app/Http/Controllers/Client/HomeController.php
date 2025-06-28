<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class HomeController extends Controller
{
    protected $productService;
    protected $categoryService;
    protected $categoryRepository;
    
    public function __construct()
    {
        $this->productService = new ProductService();
        $this->categoryService = new CategoryService();
        $this->categoryRepository = new CategoryRepository(app());
    }
    
    public function home(Request $request) {
        // lấy ra các danh mục cha
        $categories = $this->categoryRepository->getParentByClient()
            ->with(['products:id,name,slug,price,image_avatar,short_description,original_price'])
            ->whereHas('products', function ($query) {
                $query->where('status', 1);
            })
            ->get();
        
        return Inertia::render('Client/Home', [
            'categories' => $categories,
            'title' => 'Trang chủ'
        ]);
    }
}
