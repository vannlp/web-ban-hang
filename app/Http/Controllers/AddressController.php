<?php

namespace App\Http\Controllers;

use App\Helpers\Response as HelpersResponse;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\District;
use App\Models\Ward;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AddressController extends Controller
{
    public function getCities(Request $request) {
        $search = $request->input('search');
        $limit = $request->input('limit', 10);
        $input = $request->all();
        
        $query = City::query();
        
        if($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if(!empty($input['id'])) {
            $query->where('id', $input['id']);
        }
        
        $query->limit($limit);
        
        $cities = $query->get();
        
        return HelpersResponse::success("Get dữ liệu thành công", $cities);
    }
    
    public function getDistricts(Request $request) {
        $search = $request->input('search');
        $limit = $request->input('limit', 10);
        $city_id = $request->input('city_id');
        $input = $request->all();
        
        $query = District::query();
        
        if(!empty($search)) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if(!empty($input['id'])) {
            $query->where('id', $input['id']);
        }
        
        if(!empty($city_id)) {
            $query->where('city_id', $city_id);
        }
        
        $query->limit($limit);
        
        $data = $query->get();
        
        return HelpersResponse::success("Get dữ liệu thành công", $data);
    }
    
    public function getWard(Request $request) {
        $search = $request->input('search');
        $limit = $request->input('limit', 10);
        $district_id = $request->input('district_id');
        $input = $request->all();
        
        $query = Ward::query();
        
        if($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        
        if(!empty($input['id'])) {
            $query->where('id', $input['id']);
        }
        
        if($district_id) {
            $query->where('district_id', $district_id);
        }
        
        $query->limit($limit);
        
        $data = $query->get();
        
        return HelpersResponse::success("Get dữ liệu thành công", $data);
    }
    
    public function store(Request $request) {
        $request->validate([
            'phone' => ['required'],
            'street' => ['required'],
            'city_id' => ['required'],
            'district_id' => ['required'],
            'ward_id' => ['required'],
            'user_id' => ['required'],
            'is_defaut' => ['required', 'in:0,1'],
        ], [
            'required' => 'Trường :attribute là bắt buộc',
            'in' => 'Trường :attribute không hợp lệ'
        ], [
            'phone' => 'Số điện thoại',
            'street' => 'Địa chỉ',
            'city_id' => 'Thành phố',
            'district_id' => 'Quận/Huyện',
            'ward_id' => 'Phường/Xã',
            'user_id' => 'Người dùng',
        ]);
        
        try {
            DB::beginTransaction();
            
            $input = $request->all();
            
            $address = Address::create($input);
            
            DB::commit();
            
            return HelpersResponse::success("Submit thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("AddressController::store" ,['exception' => $th]);
            return HelpersResponse::fail("Submit thất bại", 500);
        }
    }
    
    public function update(Request $request, $id) {
        $request->validate([
            'phone' => ['required'],
            'street' => ['required'],
            'city_id' => ['required'],
            'district_id' => ['required'],
            'ward_id' => ['required'],
            'user_id' => ['required'],
            'is_defaut' => ['required', 'in:0,1'],
        ], [
            'required' => 'Trường :attribute là bắt buộc',
            'in' => 'Trường :attribute không hợp lệ'
        ], [
            'phone' => 'Số điện thoại',
            'street' => 'Địa chỉ',
            'city_id' => 'Thành phố',
            'district_id' => 'Quận/Huyện',
            'ward_id' => 'Phường/Xã',
            'user_id' => 'Người dùng',
        ]);
        
        try {
            DB::beginTransaction();
            
            $input = $request->all();
            
            $address = Address::find($id);
            
            $address->update($input);
            
            DB::commit();
            
            return HelpersResponse::success("Submit thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("AddressController::store" ,['exception' => $th]);
            return HelpersResponse::fail("Submit thất bại", 500);
        }
    }
    
    public function getAddress(Request $request, $id) {
        $query = Address::query();
        
        $query->where('id', $id);
        
        $data = $query->first();
        
        return HelpersResponse::success("Get dữ liệu thành công", $data);
    }
    
    public function getListAddress(Request $request) {
        $input = $request->all();
        
        if(empty($input['user_id'])) {
            return HelpersResponse::fail("Vui lòng nhập user_id");
        }
        
        $query = Address::query()->with(['city', 'district', 'ward']);
        
        $query->where('user_id', $input['user_id']);
        
        $data = $query->get();
        
        return HelpersResponse::success("Get dữ liệu thành công", $data);
    }
    
    public function updateDefault(Request $request ,$id) {
        $input = $request->all();      
        if(empty($input['user_id'])) {
            return HelpersResponse::fail("Vui lòng nhập user_id");
        }
        try {
            DB::beginTransaction();
            Address::where('user_id', $input['user_id'])->update([
                'is_defaut' => 0
            ]);
            
            $address = Address::find($id);
            
            $address->update([
                'is_defaut' => 1
            ]);   
            
            
            DB::commit();
            return HelpersResponse::success("Cập nhật trạng thái thành công");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("AddressController::updateDefault" ,['exception' => $th]);
            return HelpersResponse::fail("Cập nhật trạng thái thất bại", 500);
        }
        
        
    }
    
    public function delete(Request $request, $id) {
        try {
            DB::beginTransaction();
            $address = Address::find($id);
            
            $address->delete();
            DB::commit();
            return HelpersResponse::success("Xóa thành công");
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("AddressController::delete" ,['exception' => $th]);
            return HelpersResponse::fail("Xóa thất bại", 500);
        }
        
    }
}
