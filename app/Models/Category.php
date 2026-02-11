<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'parent_id'
    ];

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // 🔥 recursive children
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function getAllCategoryIds()
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllCategoryIds());
        }

        return $ids;
    }
}
