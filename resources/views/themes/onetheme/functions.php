<?php

if(!function_exists('getProducts')) {
    
    function getProducts() {
        
        $pages = \TCG\Voyager\Models\Page::all();
    
        return $pages;  
    }
}


add_view_shared('products','getProducts');



