<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Bard;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductController extends Controller
{
    protected $productService;
    
    public function __construct()
    {
        $this->productService = new ProductService();
    }
    
    public function index() {
        return inertia('Admin/Products/ProductIndex');
    }
    
    public function add() {
        $listBard = Bard::all();
        
        return inertia('Admin/Products/AddProduct', ['listBard' => $listBard]);
    }
    
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'short_description' => 'required',
            'description' => 'string|nullable',
            'price' => 'required|numeric',
            'original_price' => 'numeric|nullable',
            'image_avatar' => 'string|nullable',
            'list_image' => 'array|nullable',
            'bard_id' => 'nullable|integer',
            'status' => 'required|integer'
        ], [], []);
        
        $input = $request->all();
        dd($input['parent_id']);
        try {
            DB::beginTransaction();
            
            $this->productService->create($input);
            
            DB::commit();
            
            return Response::success("Thêm sản phẩm thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("ProductController::store" ,['exception' => $th]);
            return Response::fail("Thêm người dùng thất bại", 500);
        }
    }
}
