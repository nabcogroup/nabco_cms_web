<?php

namespace DevNab\LaraLibs\App\Http\Controllers;


use Illuminate\Http\Request;
use TCG\Voyager\Http\Controllers\Controller;
use DevNab\LaraLibs\App\VoyagerRepositories\PageRepository;

class PageController extends Controller
{

    private $repo;

    public function __construct(PageRepository $repo) {
        
        $this->repo = $repo;

    }
    public function index() {
        
        return view("theme::pages.home");
    }

    public function slug($slug) {
        
        
        $page = $this->repo->getBySlug($slug);
        
        //check if inside page 
        $view = "theme::pages.$page->slug";
        
        if(!view()->exists($view)) {
            
            $view = "theme::single-page";

        }

        
        
        return view($view,compact("page"));
        
    }


}
