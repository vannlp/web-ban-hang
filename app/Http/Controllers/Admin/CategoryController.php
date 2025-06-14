<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\CategoryDataTable;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController extends Controller
{
    protected $categoryRepository;
    protected $categoryService;
    
    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository(app());
        $this->categoryService = new CategoryService();
    }
    
    public function getCategories(Request $request) {
        $input = $request->all();
        
        if(!empty($input['search'])) {
            $input['name'] = $input['search'];
        }
        
        $listCategory = $this->categoryRepository->getCategory($input)->limit(10)->get();
        
        return Response::success("Get dữ liệu thành công", $listCategory);
    }
    
    public function getOne(Request $request, $id) {
        if(empty($id)) {
            return Response::fail("Get dữ liệu thất bại");
        }
        
        $input['id'] = $id;
        
        $listCategory = $this->categoryRepository->getAllCol($input)->first();
        
        return Response::success("Get dữ liệu thành công", $listCategory);
    }
    
    public function index() {
        return Inertia::render('Admin/Products/ManagerCategory');
    }
    
    public function datatable() {
        return (new CategoryDataTable())->build();
    }
    
    public function updateStatus(Request $request, $id) {
        $status = $request->input('status');
        try {
            DB::beginTransaction();
            
            $this->categoryService->updateStatus($id, $status);
            
            DB::commit();
            
            return Response::success("Cập nhật thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("CategoryController::updateStatus" ,['exception' => $th]);
            return Response::fail("Cập nhật thành công thất bại", 500);
        }
    }
    
    public function store(Request $request) {
        $request->validate([
            'name' => ['required', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'image_avatar' => ['nullable', 'string'],
            'slug' => ['required', 'string', 'unique:categories,slug'],
            'status' => ['required', 'numeric'],
            'parent_id' => ['nullable'],
        ],[
            'required' => 'Trường :attribute là bắt buộc',
            'unique' => 'Trường :attribute đã tồn tại',
            'numeric' => 'Trường :attribute phải là số',
            'string' => 'Trường :attribute phải là chuỗi'
        ],[
            'name' => 'Tên danh mục',
            'description' => 'Mô tả',
            'image_avatar' => 'Ảnh đại diện',
            'slug' => 'Slug',
            'status' => 'Trạng thái',
            'parent_id' => 'Danh mục cha',
        ]);
        
        $input = $request->all();
        
        try {
            DB::beginTransaction();
            
            $this->categoryService->create($input);
            
            DB::commit();
            
            return Response::success("Thêm mới thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("CategoryController::store" ,['exception' => $th]);
            return Response::fail("Thêm mới  thất bại", 500);
        }
        
        // $this->categoryService
    }
    
    public function update(Request $request, $id) {
        $category = $this->categoryRepository->findOrFail($id);
        
        $request->validate([
            'name' => ['required', Rule::unique('categories', 'name')->ignore(optional($category)->id)],
            'description' => ['nullable', 'string'],
            'image_avatar' => ['nullable', 'string'],
            'slug' => ['required', 'string', Rule::unique('categories', 'slug')->ignore(optional($category)->id)],
            'status' => ['required', 'numeric'],
            'parent_id' => ['nullable'],
        ],[
            'required' => 'Trường :attribute là bắt buộc',
            'unique' => 'Trường :attribute đã tồn tại',
            'numeric' => 'Trường :attribute phải là số',
            'string' => 'Trường :attribute phải là chuỗi'
        ],[
            'name' => 'Tên danh mục',
            'description' => 'Mô tả',
            'image_avatar' => 'Ảnh đại diện',
            'slug' => 'Slug',
            'status' => 'Trạng thái',
            'parent_id' => 'Danh mục cha',
        ]);
        
        $input = $request->all();
        
        try {
            DB::beginTransaction();
            
            $this->categoryService->update($id, $input);
            
            DB::commit();
            
            return Response::success("Cập nhật thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("CategoryController::update" ,['exception' => $th]);
            return Response::fail("Cập nhật thất bại", 500);
        }
    }
    
    
    public function delete(Request $request, $id) {
        try {
            DB::beginTransaction();
            
            $this->categoryService->delete($id);
            
            DB::commit();
            
            return Response::success("Xóa thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("CategoryController::delete" ,['exception' => $th]);
            return Response::fail("Xóa thất bại", 500);
        }
    }
    
}
