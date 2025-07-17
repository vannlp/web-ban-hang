<?php
namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class OrderService {
    public function checkout ($data = []) {
        $user_id = $data['user_id'];
        
        $cart = Cart::where('user_id', $user_id)->first();
        $listCartDetail = $cart->cartDetail;
        
        $order = Order::create([
            'code' => Order::generateOrderCode(),
            'user_id' => $cart->user_id,
            'total_price' => $cart->total_price,
            'final_price' => $cart->final_price,
            'status' => Order::STATUS_PENDING,
            'cart_id' => $cart->id,
            'address_id' => $cart->address_id,
        ]);
        
        // tạo order item
        foreach($listCartDetail as $detail) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $detail->product_id,
                'quantity' => $detail->quantity,
                'price' => $detail->price,
                'final_price' => $detail->final_price,
            ]);
        }
        
        // xóa đi giỏ hàng
        $cart->delete();
        
        return $order;
    }
}