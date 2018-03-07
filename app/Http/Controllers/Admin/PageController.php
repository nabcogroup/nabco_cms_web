<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Page;
use TCG\Voyager\Facades\Voyager;

use TCG\Voyager\Events\BreadDataUpdated;



use DevNab\LaraLibs\Traits\BreadAddController;
use DevNab\LaraLibs\Traits\BreadBrowseController;
use DevNab\LaraLibs\Traits\BreadDestroyController;
use TCG\Voyager\Http\Controllers\Controller as BaseController;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;


class PageController extends BaseController
{
    use BreadRelationshipParser,
        BreadBrowseController,
        BreadAddController,
        BreadDestroyController;




   
   
}
