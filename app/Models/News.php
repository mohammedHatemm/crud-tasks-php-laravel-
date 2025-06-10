<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'content', 'user_id'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'news_category');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        }
        return $query;
    }

    public function scopeFilterByDate($query, $date)
    {
        if ($date) {
            switch ($date) {
                case 'today':
                    return $query->whereDate('created_at', now()->format('Y-m-d'));
                case 'week':
                    return $query->where('created_at', '>=', now()->subWeek());
                case 'month':
                    return $query->where('created_at', '>=', now()->subMonth());
            }
        }
        return $query;
    }
}
