<?php

namespace DevNab\LaraLibs;


class CMSRoute {

    public static function routes() {
        
        $routes = __DIR__ . "/../routes/web.php";
        
        require $routes;
    }
}