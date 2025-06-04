<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    //
    protected $fillable = [
        'title',
        'content',
        'user_id'

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'news_category');
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
