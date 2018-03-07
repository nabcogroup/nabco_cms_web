<?php

use DevNab\LaraLibs\App\Models\Post;
use DevNab\LaraLibs\App\VoyagerRepositories\PostRepository;

if(!function_exists("get_image_path")) {

    function get_image_path($url_path) {
        return Voyager::image($url_path);
    }
};

if(!function_exists("get_the_post")) {

    function get_the_post($slug,$args = array()) {
        
        $repository = new PostRepository();

        return $repository->getBySlug($slug,$args);
        
    }
};

if(!function_exists("get_post_by_category")) {

    function get_post_by_category($category_name,$args = array()) {
        
        $repository = new PostRepository();
        
        return $repository->getByCategory($category_name,$args);
        
    }
}


if(!function_exists("add_view_shared")) {

    function add_view_shared($variable,$register) {
        
        if(function_exists($register) && is_callable($register)) {
            
            $value = call_user_func($register);
        
            view()->share($variable,$value);
        }
    }
};