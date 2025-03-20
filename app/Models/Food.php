<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Food extends Model
{
    use HasFactory;
    protected $table = 'foods'; // Xác định rõ tên bảng


    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean'
    ];

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Get image URL attribute
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        return asset('images/default-food.jpg');
    }

    // Scope for active foods
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    // Delete image when food is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function($food) {
            if ($food->image) {
                Storage::delete($food->image);
            }
        });
    }
}
