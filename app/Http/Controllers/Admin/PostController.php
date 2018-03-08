<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Post;
use TCG\Voyager\Facades\Voyager;

use DevNab\LaraLibs\Traits\BreadAddController;
use DevNab\LaraLibs\Traits\BreadEditController;
use DevNab\LaraLibs\Traits\BreadBrowseController;
use DevNab\LaraLibs\Traits\BreadDestroyController;

use TCG\Voyager\Http\Controllers\Controller as BaseController;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;

use Validator;

class PostController extends BaseController
{
    use BreadRelationshipParser,
        BreadBrowseController,
        BreadAddController,
        BreadEditController,
        BreadDestroyController;
    
    public $slug = "posts";
    public $view = "posts";
    public $dataTypeContent;

    public function __construct(Post $post) {
        $this->dataTypeContent = $post;
    }

    public function validateBread($request,$data) {

        $rules = [];
        $messages = [];

        foreach ($data as $row) {
            $options = json_decode($row->details);
            if (isset($options->validation)) {
                if (isset($options->validation->rule)) {
                    if (!is_array($options->validation->rule)) {
                        $rules[$row->field] = explode('|', $options->validation->rule);
                    } else {
                        $rules[$row->field] = $options->validation->rule;
                    }
                }

                if (isset($options->validation->messages)) {
                    foreach ($options->validation->messages as $key => $msg) {
                        $messages[$row->field.'.'.$key] = $msg;
                    }
                }
            }
        }

        return Validator::make($request, $rules, $messages);

    }


    
}
