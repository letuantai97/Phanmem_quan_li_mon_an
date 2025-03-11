<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Food extends Model
{
    use HasFactory;

    // Cho phép mass assignment cho các trường này
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'status'  // Thêm trường status
    ];

    protected $table = 'foods'; // Đặt tên bảng chính xác
    protected $casts = [
        'price' => 'decimal:0',  // Cast price thành decimal
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    // Xử lý upload ảnh
    public function uploadImage($image)
    {
        try {
            if ($image) {
                // Xóa ảnh cũ nếu có
                if ($this->image && Storage::disk('public')->exists($this->image)) {
                    Storage::disk('public')->delete($this->image);
                }

                // Upload ảnh mới
                $imagePath = $image->store('foods', 'public');
                $this->update(['image' => $imagePath]);
            }
        } catch (\Exception $e) {
            Log::error('Error uploading image: ' . $e->getMessage());
            throw new \Exception('Could not upload image');
        }
    }

    // Xóa ảnh
    public function deleteImage()
    {
        try {
            if ($this->image && Storage::disk('public')->exists($this->image)) {
                Storage::disk('public')->delete($this->image);
                $this->update(['image' => null]);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting image: ' . $e->getMessage());
        }
    }

    // Accessor để lấy URL đầy đủ của ảnh
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-food.jpg');
    }



    // Accessor cho giá đã format
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', '.') . 'đ';
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        // Xóa ảnh khi xóa món ăn
        static::deleting(function($food) {
            $food->deleteImage();
        });
    }
}
