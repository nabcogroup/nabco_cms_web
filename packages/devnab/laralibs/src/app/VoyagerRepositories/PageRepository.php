<?php 


namespace DevNab\LaraLibs\App\VoyagerRepositories; 

use TCG\Voyager\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PageRepository {  

    public function getBySlug($slug,$args = array()) {
        
        $page = Page::where("slug",$slug);
      
        return $page->firstOrFail();
    }
}