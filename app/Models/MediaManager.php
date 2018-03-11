<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaManager extends Model
{
    protected $table = "media_manager";

    public function scopeFilters($query,$search) {
        if ($search->value && $search->key && $search->filter) {
            $search_filter = ($search->filter == 'equals') ? '=' : 'LIKE';
            $search_value = ($search->filter == 'equals') ? $search->value : '%'.$search->value.'%';
            $query->where($search->key, $search_filter, $search_value);
        }
    }

    public function scopeSlug($query,$value) {
        return $query->where('slug',$value);
    }

    public function scopeSlugIn($query,$values = []) {
        return $query->whereIn('slug',$values);
    }
}
