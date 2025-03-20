<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'address',
        'total_amount',
        'status',
        'note',
        'order_type',
        'payment_status',
        'table_number'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    // Mối quan hệ với OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Mối quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope để lọc theo trạng thái
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope để lọc theo trạng thái thanh toán
    public function scopePaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    // Tính tổng tiền đơn hàng
    public function calculateTotal()
    {
        return $this->items->sum(function($item) {
            return $item->quantity * $item->price;
        });
    }

    // Cập nhật tổng tiền
    public function updateTotal()
    {
        $this->total_amount = $this->calculateTotal();
        $this->save();
    }

    // Mối quan hệ với Product qua OrderItem
    public function products()
    {
        return $this->belongsToMany(Food::class, 'order_items', 'order_id', 'food_id')
                    ->withPivot('quantity');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'chờ xử lý' => '<span class="badge bg-warning">Chờ xử lý</span>',
            'đang xử lý' => '<span class="badge bg-info">Đang xử lý</span>',
            'hoàn thành' => '<span class="badge bg-success">Hoàn thành</span>',
            'đã hủy' => '<span class="badge bg-danger">Đã hủy</span>',
            default => '<span class="badge bg-secondary">Không xác định</span>'
        };
    }

    public function getPaymentStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'pending' => '<span class="badge bg-warning">Chưa thanh toán</span>',
            'completed' => '<span class="badge bg-success">Đã thanh toán</span>',
            default => '<span class="badge bg-secondary">Không xác định</span>'
        };
    }

    public function getOrderTypeBadgeAttribute()
    {
        return match($this->order_type) {
            'dine-in' => '<span class="badge bg-info">Tại quán</span>',
            'online' => '<span class="badge bg-primary">Trực tuyến</span>',
            default => '<span class="badge bg-secondary">Không xác định</span>'
        };
    }
}
