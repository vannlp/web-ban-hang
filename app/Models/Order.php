<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;
    
    protected $table = "orders";
    
    protected $fillable = [
        'id',
        'user_id',
        'code',
        'total_price',
        'final_price',
        'status',
        'cart_id',
        'address_id',
        'created_at',
        'updated_at',
    ];
    
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_PENDING    => 'Chờ xử lý',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_COMPLETED  => 'Hoàn thành',
            self::STATUS_CANCELLED  => 'Đã huỷ',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? 'Không rõ';
    }
    
    
    public function orderItem() {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
    
    public function address() {
        return $this->belongsTo(Address::class, 'address_id');
    }
    
    public static function generateOrderCode(int $length = 6): string
    {
        $prefix = 'ODER_'; // bạn có thể thay bằng gì tuỳ thích
        $code = $prefix . strtoupper(Str::random($length));

        // đảm bảo không bị trùng (cực hiếm khi xảy ra nhưng vẫn nên chắc chắn)
        while (self::where('code', $code)->exists()) {
            $code = $prefix . strtoupper(Str::random($length));
        }

        return $code;
    }
    
    public function getFinalPriceFormattedAttribute(): string
    {
        return number_format($this->final_price, 0, ',', '.') . ' đ';
    }
}
