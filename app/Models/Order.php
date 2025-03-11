<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'address',
        'total_amount',
        'status',
        'note'
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

    // Mối quan hệ với Product qua OrderItem
    public function products()
    {
        return $this->belongsToMany(food::class, 'order_items')
                    ->withPivot('quantity'); // Giả sử bạn có trường quantity trong bảng order_items
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
}
