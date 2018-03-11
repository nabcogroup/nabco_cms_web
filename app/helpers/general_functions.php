<?php


if(!function_exists('get_page_nav')) {

    function get_page_nav($template,$excluded = array()) {

        $pages = \TCG\Voyager\Models\Page::select('slug','title','sort_order')->active();
        if(count($excluded) > 0) {
            $pages = $pages->whereNotIn('slug', $excluded);
        }

        return new \Illuminate\Support\HtmlString(
            \Illuminate\Support\Facades\View::make($template,['items' => $pages->orderBy('sort_order')])->render()
        );
        //return $pages->get();
    }
}



if(!function_exists("get_image_path")) {

    function get_image_path($url_path) {
        return Voyager::image($url_path);
    }
};

if(!function_exists("get_the_post")) {

    function get_the_post($slug,$args = array()) {

        $post = \App\Models\Post::where('slug', $slug);

        return $post->orderBy('sort_order')->get();

    }
};

if(!function_exists("get_post_by_category")) {

    function get_post_by_category($category_name,$args = array()) {

        $post = \App\Models\Post::where('category',$category_name);

        return $post->orderBy('sort_order')->get();
    }
}
