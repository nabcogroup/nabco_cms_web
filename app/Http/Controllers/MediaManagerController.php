<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Models\MediaManager;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Http\Controllers\Controller as BaseController;

class MediaManagerController extends BaseController
{


    public function __construct() {

    }
    public function index(Request $request) {

        $dataType = Voyager::model('DataType')->where('slug','media-manager')->first();

        // Check permission
        $this->authorize('browse', app($dataType->model_name));

        $getter = $dataType->server_side ? 'paginate' : 'get';
        $search = (object) ['value' => $request->get('s'), 'key' => $request->get('key'), 'filter' => $request->get('filter')];
        $searchable = $dataType->server_side ? array_keys(SchemaManager::describeTable(app($dataType->model_name)->getTable())->toArray()) : '';
        $orderBy = $request->get('order_by');
        $sortOrder = $request->get('sort_order', null);

        $query = MediaManager::select("*")->filters($search);

        if($orderBy && in_array($orderBy,$dataType->fields())) {
            $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'DESC';
            $dataTypeContent = call_user_func([$query->orderBy($orderBy, $querySortOrder),$getter]);
        }
        else{
            $dataTypeContent = call_user_func([$query->latest(MediaManager::CREATED_AT),$getter]);
        }

        // Check if server side pagination is enabled
        $isServerSide = isset($dataType->server_side) && $dataType->server_side;


        if($request->ajax()) {
            $view = "voyager::media-manager.ajax-browse";
        }
        else {
            $view = "voyager::media-manager.browse";
        }



        return Voyager::view($view,
            compact('dataType',
            'dataTypeContent',
            'search',
            'orderBy',
            'sortOrder',
            'searchable',
            'isServerSide'));
    }

    public function create(Request $request) {

        $dataType = Voyager::model('DataType')->where('slug','media-manager')->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = new MediaManager();
        foreach ($dataType->addRows as $key => $row) {
            $details = json_decode($row->details);
            $dataType->addRows[$key]['col_width'] = isset($details->width) ? $details->width : 100;
        }

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        return Voyager::view("voyager::media-manager.edit-add", compact('dataType', 'dataTypeContent', 'isModelTranslatable'));

    }

    public function store(Request $request) {

        $dataType = Voyager::model('DataType')->where('slug', '=', 'media-manager')->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        if (!$request->ajax()) {
            $dataTypeContent = new MediaManager();
            $data = $this->insertUpdate($request,$dataTypeContent,$dataType,true);

            event(new BreadDataAdded($dataType, $data));

            return redirect()
                ->route("voyager.media-manager.index")
                ->with([
                    "message" => __('voyager.generic.successfully_added_new')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);

        }
    }

    public function edit(Request $request, $id) {

        $dataType = Voyager::model('DataType')->where('slug', '=', "media-manager")->first();

          
        $dataTypeContent = MediaManager::findOrFail($id);

        foreach ($dataType->addRows as $key => $row) {
            $details = json_decode($row->details);
            $dataType->addRows[$key]['col_width'] = isset($details->width) ? $details->width : 100;
        }

        // Check permission
        $this->authorize('edit', $dataTypeContent);
        
        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        return Voyager::view("voyager::media-manager.edit-add", compact('dataType', 'dataTypeContent', 'isModelTranslatable'));


    }

    public function update(Request $request,$id) {
        
        $dataType = Voyager::model('DataType')->where('slug', '=', "media-manager")->first();

        $dataTypeContent = MediaManager::findOrFail($id);

        // Check permission
        $this->authorize('edit', $dataTypeContent);

        $this->insertUpdate($request,$dataTypeContent,$dataType,false);
        
        event(new BreadDataUpdated($dataType, $dataTypeContent));

        return redirect()
            ->route("voyager.{$dataType->slug}.index")
            ->with([
                'message'    => __('voyager.generic.successfully_updated')." {$dataType->display_name_singular}",
                'alert-type' => 'success',
            ]);

    }




    private function insertUpdate(Request $request,&$dataTypeContent, &$dataType,$isCreate = true) {
        

        $navigation = $isCreate ? "addRows" : "editRows";
        
        $rowFields = $dataType->{$navigation};
        

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->{$navigation});
        
        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        $rowFields = $dataType->{$navigation};

        foreach($rowFields as $rowField) {
            $dataTypeContent->{$rowField->field} = $request->input($rowField->field);
            if($request->has("img_".$rowField->field)) {    
                $content = json_decode($this->getContentBasedOnType($request,$rowField->field,(object)["type" => "file","field" => "img_".$rowField->field]));
                $dataTypeContent->{$rowField->field} = $content[0]->download_link;
            }
        }
          
        $dataTypeContent->save();

    }


}
