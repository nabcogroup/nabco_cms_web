<?php

namespace DevNab\LaraLibs\Facades;

use Illuminate\Support\Facades\Facade;

class CMSRoute extends Facade {

    protected static function getFacadeAccessor()
    {
        return 'cmsRoute';
    }

}