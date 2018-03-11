<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Page;

class ThemePageController extends Controller
{
    public function __invoke(Request $request,$slug = '') {

        $content = '';
        if(!empty($slug)) {

            $content = Page::where('slug',$slug)->active()->first();
            
            $view = "theme::pages.$content->slug";
            if(!view()->exists($view)) {
                $view = "theme::single";
            }
        }
        else {
            $view = "theme::index";
        }



        return view($view,compact('content'));

    }
}
