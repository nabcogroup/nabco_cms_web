<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;

use App\Traits\BreadAddController;
use App\Traits\BreadBrowseController;
use App\Traits\BreadDestroyController;
use App\Traits\BreadEditController;

use Illuminate\Http\Request;

use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Facades\Voyager;
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

    public function edit(Request $request,$id) {

        $dataType = Voyager::model('DataType')->where('slug', '=', $this->slug)->with('rows')->first();


        $this->authorize('edit',$this->dataTypeContent);

        $dataTypeContent = Post::with('postMeta')->findOrFail($id);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        return Voyager::view("voyager::posts.edit-add", compact('dataType', 'dataTypeContent', 'isModelTranslatable'));

    }

    public function store(Request $request) {


        $dataType = Voyager::model('DataType')->where('slug', '=', 'posts')->first();

        $this->authorize('add',$this->dataTypeContent);


        $this->validateBread($request,$dataType->addRows);

        if (!$request->ajax()) {

            $this->insertUpdateData($request, 'posts', $dataType->addRows, $this->dataTypeContent);

            $this->dataTypeContent->savePostMeta($request->input('post_meta'));

            event(new BreadDataAdded($dataType, $this->dataTypeContent));

            return redirect()
                ->route("voyager.posts.index")
                ->with([
                    'message'    => __('voyager.generic.successfully_added_new')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }

    }

    public function update(Request $request,$id) {

        $dataType = Voyager::model('DataType')->where('slug', '=', 'posts')->first();

        $dataTypeContent = Post::findOrFail($id);

        // Check permission
        $this->authorize('edit', $dataTypeContent);


        // Validate fields with ajax
        $val = $this->validateBread($request, $dataType->editRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            
            $this->insertUpdateData($request, 'posts', $dataType->editRows, $dataTypeContent);

            $dataTypeContent->savePostMeta($request->input('post_meta'));

            event(new BreadDataUpdated($dataType, $dataTypeContent));

            return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                    'message'    => __('voyager.generic.successfully_updated')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
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
                    }
                    else {
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



        return Validator::make($request->except(['post_meta']), $rules, $messages);

    }


    
}
