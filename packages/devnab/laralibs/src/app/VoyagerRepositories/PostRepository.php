<?php 


namespace DevNab\LaraLibs\App\VoyagerRepositories;

use TCG\Voyager\Models\Post;
use TCG\Voyager\Models\Category;

class PostRepository {


    public function getBySlug($slug_post,$args = array()) {
        
        return $this->withPublish()->where('slug',$slug_post)->get();
    }

    public function getByCategory($categorySlug,$sortArgs = array(),$options = array()) {

        $posts = Category::with("posts")
                    ->join("posts","categories.id","=","posts.category_id")
                    ->where("categories.slug",$categorySlug);

        if(count($sortArgs) > 1) {

            foreach($sortArgs as $key => $value) {
                
                $posts->orderBy($key,$value);
                
            }
        }
        
        return $posts->get();
        
    }

    public function getAll() {

        return $this->withPublish()->get();

    }



    protected function withPublish() {

        return Post::where('status','PUBLISHED');

    }
}
