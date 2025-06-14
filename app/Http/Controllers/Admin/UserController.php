<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userRepository;
    
    public function __construct()
    {
        $this->userRepository = new UserRepository(app());
    }
    
    
    public function index()
    {
        
        return Inertia::render('Admin/Users/UserIndex');
    }
    
    public function datatable() {
        return (new UserDataTable())->build();
    }
    
    public function store(Request $request) {
        $request->validate([
            'username' => ['required', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed'],
            'password_confirmation' => ['required']
        ],[
            'required' => 'Trường :attribute là bắt buộc',
            'unique' => 'Trường :attribute đã tồn tại',
            'confirmed' => 'Xác nhận mật khẩu không đúng'
        ],[
            'username' => 'Tên đăng nhập',
            'email' => 'Email',
            'password' => 'Mật khẩu',
            'password_confirmation' => 'Xác nhận mật khẩu'
        ]);
        
        try {
            DB::beginTransaction();
            $createData = [
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'name' => $request->input('name', ''),
            ];
            
            $this->userRepository->create($createData);
            
            DB::commit();
            
            return Response::success('Thêm người dùng thành công');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("UerController::store" ,$th);
            return Response::fail("Thêm người dùng thất bại");
        }
    }
    
    public function delete(Request $request, $id) {
        try {
            DB::beginTransaction();
            
            $this->userRepository->delete($id);
            
            DB::commit();
            
            return Response::success('Xóa người dùng thành công');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("UerController::delete" ,$th);
            return Response::fail("Xóa người dùng thất bại", 500);
        }
    }
    
    public function getUser(Request $request, $id) {
        try {
            $user = $this->userRepository->findOrFail($id);

            return Response::success('Lấy thông tin người dùng thành công' ,$user);
        } catch (\Throwable $th) {
            Log::error("UerController::getUser" ,$th);
            return Response::fail("Lấy thông tin người dùng thất bại", 500);
        }
    }
    
    public function update(Request $request, $id) {
        try {
            $dataUpdate = $request->except(['username', 'email']);
            DB::beginTransaction();
            
            $user = $this->userRepository->findOrFail($id);
            
            $user->update([
                'name' => !empty($dataUpdate['name']) ? $dataUpdate['name'] : $user->name,
                'password' => !empty($dataUpdate['password']) ? Hash::make($dataUpdate['password']) : $user->password,
            ]);
            
            DB::commit();
            
            return Response::success('Update người dùng thành công');
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("UerController::store" ,$th);
            return Response::fail("Update người dùng thất bại");
        }
    }
    
}
