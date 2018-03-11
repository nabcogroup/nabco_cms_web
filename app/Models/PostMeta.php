<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    protected $table = "post_meta";

    protected $fillable = ["meta_key","meta_value","description","input_type"];

    public static  function getMetaKeyFieldSchema() {
        return config('app.post_meta_schema');
    }




}
