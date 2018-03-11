<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Post as VoyagerPost;
use TCG\Voyager\Traits\HasRelationships;
use TCG\Voyager\Traits\Resizable;
use TCG\Voyager\Traits\Translatable;

class Post extends Model
{

    use Translatable,
        Resizable,
        HasRelationships;

    protected $translatable = ['title', 'seo_title', 'excerpt', 'body', 'slug', 'meta_description', 'meta_keywords'];

    const PUBLISHED = 'PUBLISHED';

    protected $guarded = [];

    protected $appends = ["meta_media_object"];

    public function save(array $options = [])
    {
        // If no author has been assigned, assign the current user's id as the author of the post
        if (!$this->author_id && Auth::user()) {
            $this->author_id = Auth::user()->id;
        }






        parent::save();
    }


    /**
     * Scope a query to only published scopes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished(Builder $query)
    {
        return $query->where('status', '=', static::PUBLISHED);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category()
    {
        return $this->belongsTo(Voyager::modelClass('Category'));
    }

    /***
     * Mutators and Accessor
     */
    public function getMetaMediaObjectAttribute($value)
    {

        if (is_null($value)) return "";

        $retValue = json_decode($value);

        return $retValue;

    }

    public function getFeaturedAttribute($value) {

        return is_null($value) ? 0 : $value;
    }


    public function postMeta()
    {
        return $this->hasMany("App\Models\PostMeta","post_id","id");
    }

    public function authorId()
    {
        return $this->belongsTo(Voyager::modelClass('User'), 'author_id', 'id');
    }


    public function savePostMeta($post_meta = []) {

        $meta_keys = PostMeta::getMetaKeyFieldSchema();

        foreach ($post_meta as $key => $value) {

            $meta_key = isset($meta_keys[$key]) ? $meta_keys[$key] : [];

            if(count($meta_key) <= 0) continue;

            $postMeta = $this->postMeta()->where("meta_key",$key)->first();
            if(is_null($postMeta)) {
                $this->postMeta()->create([
                    "meta_key"     =>  $key,
                    "meta_value"    =>  $value,
                    "description"   =>  $meta_key["description"],
                    "input_type"    =>  $meta_key["input_type"]
                ]);
            }
            else {
                $postMeta->meta_value = $value;
                $postMeta->save();
            }
        }

    }

}
