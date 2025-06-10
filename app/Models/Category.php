<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = ['name', 'description', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function news()
    {
        return $this->belongsToMany(News::class, 'news_category');
    }
    public static function getCategoryHierarchy()
    {
        return self::with('children.children')->whereNull('parent_id')->get();
    }
}
