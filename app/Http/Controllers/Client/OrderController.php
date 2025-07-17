<?php

namespace App\Http\Controllers\Client;

use App\DataTables\OrderClientDataTable;
use App\DataTables\OrderDataTable;
use App\Helpers\Response;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OrderController extends Controller
{
    public $orderService;
    
    public function __construct()
    {
        $this->orderService = new OrderService();
    }
    
    public function handleCheckout(Request $request) {
        try {
            DB::beginTransaction();
            
            $data['user_id'] = auth()->user()->id;
            $order = $this->orderService->checkout($data);
            
            DB::commit();
            
            return Response::success("Thanh toán thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("OrderController::handleCheckout" ,['exception' => $th]);
            return Response::fail("Thanh toán thất bại", 500);
        }
        
        
    }
    
    public function clientDataTable() {
        return (new OrderClientDataTable())->build();
    }
    
     public function dataTable() {
        return (new OrderDataTable())->build();
    }
    
    public function orderDetail($id) {
        $order = Order::with([
            'orderItem', 'address', 'address.city', 'address.district', 'address.ward',
            'orderItem.product:id,name,slug,price,original_price,image_avatar'
        ])->where('id', $id)->first();
        
        if($order) {
            $order->final_price_formatted = $order->final_price_formatted;
            $order->full_address = $order->address->full_address ?? null;
            $order->full_address = $order->address->full_address ?? null;
            $order->status_label = $order->status_label ?? null;
        }
        
        return Response::success('get dữ liệu thành công', $order);
    }
    
    
    public function viewOrderAdmin(Request $request) {
        $listStatus = Order::getStatusOptions();
        
        return Inertia::render('Admin/Order/OrderAdmin', [
            'listStatusOrder' => $listStatus,
            'title' => 'Danh sách đơn hàng'
        ]);
    }
    
    public function updateStatus(Request $request) {
        try {
            $input = $request->all();
            
            $request->validate([
                'id' => 'required',
                'status' => 'required'
            ]);
            
            DB::beginTransaction();
            
            $order = Order::find($input['id']);
            
            $order->status = $input['status'];
            $order->save();
            
            DB::commit();
            
            return Response::success("Cập nhật thành công");
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error("OrderController::updateStatus" ,['exception' => $th]);
            return Response::fail("Cập nhật thất bại", 500);
        }
    }
}
