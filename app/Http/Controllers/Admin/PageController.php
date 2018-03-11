<?php

namespace App\Http\Controllers\Admin;

use App\Traits\BreadAddController;
use App\Traits\BreadBrowseController;
use App\Traits\BreadDestroyController;
use App\Traits\BreadEditController;
use TCG\Voyager\Models\Page;




use TCG\Voyager\Http\Controllers\Controller as BaseController;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;


class PageController extends BaseController
{
    use BreadRelationshipParser,
        BreadBrowseController,
        BreadEditController,
        BreadAddController,
        BreadDestroyController;

        public $slug = "pages";
        public $view = "pages";
        public $dataTypeContent;


        public function __construct(Page $page) {
            $this->dataTypeContent = $page;
        }


   
   
}
