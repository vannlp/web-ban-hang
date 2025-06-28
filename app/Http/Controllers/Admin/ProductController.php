<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProductDataTable;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Bard;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
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
    
    public function datatable() {
        return (new ProductDataTable())->build();
    }
    
    public function edit(Request $request, $id) {
        if(empty($id)) {
            return abort(400, 'Invalid Product ID');
        }
        
        $listBard = Bard::all();
        
        $input['getCategory'] = true;
        
        $product = $this->productService->getProductEdit($id, $input);
        
        if(empty($product)) {
            return abort(400, 'Invalid Product ID');
        }
        
        return inertia('Admin/Products/EditProduct', ['product' => $product, 'listBard' => $listBard]);
    }
    
    public function update(Request $request, $id) {
        if(empty($id)) {
            return abort(400, 'Invalid Product ID');
        }
        
        $product = $this->productService->getProductEdit($id);
        
        $request->validate([
            'name' => ['required', Rule::unique('products', 'name')->ignore(optional($product)->id)],
            'short_description' => 'required',
            'description' => 'string|nullable',
            'price' => 'required|numeric',
            'original_price' => 'numeric|nullable',
            'image_avatar' => 'string|nullable',
            'list_image' => 'array|nullable',
            'bard_id' => 'nullable|integer',
            'status' => 'required|integer',
            'slug' => ['required', 'string', Rule::unique('products', 'slug')->ignore(optional($product)->id)],
            'listCategory' => 'array|nullable'
        ], [
            'required' => 'Trường :attribute là bắt buộc',
            'unique' => 'Trường :attribute đã tồn tại',
            'numeric' => 'Trường :attribute phải là số',
            'string' => 'Trường :attribute phải là chuỗi'
        ], [
            'name' => 'Tên sản phẩm',
            'short_description' => 'Mô tả ngắn',
            'description' => 'Mô tả',
            'price' => 'Giá',
            'original_price' => 'Giá gốc',
            'image_avatar' => 'Ảnh đại diện',
            'list_image' => 'Danh sách ảnh',
            'listCategory' => 'Danh mục',
        ]);
        
        $input = $request->all();
        try {
            DB::beginTransaction();
            
            $this->productService->update($id, $input);
            
            DB::commit();
            
            return Response::success("Cập nhật sản phẩm thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("ProductController::store" ,['exception' => $th]);
            return Response::fail("Cập nhật thất bại", 500);
        }
    }
    
    public function add() {
        $listBard = Bard::all();
        
        return inertia('Admin/Products/AddProduct', ['listBard' => $listBard]);
    }
    
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|unique:products,name',
            'short_description' => 'required',
            'description' => 'string|nullable',
            'price' => 'required|numeric',
            'original_price' => 'numeric|nullable',
            'image_avatar' => 'string|nullable',
            'list_image' => 'array|nullable',
            'bard_id' => 'nullable|integer',
            'status' => 'required|integer',
            'slug' => ['required', 'string', 'unique:products,slug'],
            'listCategory' => 'array|nullable'
        ], [], []);
        
        $input = $request->all();
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
    
    public function delete(Request $request, $id) {
        $input = $request->all();
        try {
            DB::beginTransaction();
            
            $this->productService->delete($id);
            
            DB::commit();
            
            return Response::success("Xóa sản phẩm thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("ProductController::delete" ,['exception' => $th]);
            return Response::fail("Xóa thất bại", 500);
        }
    }
}
