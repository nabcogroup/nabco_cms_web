<?php

use TCG\Voyager\Models\Menu;
use Illuminate\Events\Dispatcher;
use TCG\Voyager\Models\Permission;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;


class ChatSupportProvider extends ServiceProvider {
    
    public function register() {
        
        if(request()->is(config('voyager.prefix')) || request()->is(config('voyager.prefix').'/*')) {
            
            $this->createChatTable();

            app(Dispatcher::class)->listen('voyager.menu.display',function($menu) {

                $this->addChatMenuItem($menu);

            });

            app(Dispatcher::class)->listen('voyager.admin.routing',function($router) {
                
            });

        }
    }


    public function boot() {

    }


      /**
     * Adds the Theme icon to the admin menu.
     *
     * @param TCG\Voyager\Models\Menu $menu
     */
    public function addChatMenuItem(Menu $menu) {

        if($menu->name == "admin") {
            
            $url = route("voyager.chatsupport.index",[],false);
            
            $menuItem = $menu->items->where('url', $url)->first();
            
            if (is_null($menuItem)) {
                
                $menu->items->add(MenuItem::create([
                    'menu_id' => $menu->id,
                    'url' => $url,
                    'title' => 'Chat Support',
                    'target' => '_self',
                    'icon_class' => 'voyager-chat',
                    'color' => null,
                    'parent_id' => null,
                    'order' => 99,
                ]));

                $this->ensurePermissionExist();

                return redirect()->back();
            }
        }
    }


     /**
     * Add Permissions for themes if they do not exist yet.
     *
     * @return none
     */
    protected function ensurePermissionExist()
    {
        
        $permission = Permission::firstOrNew([
            'key' => 'browse_themes',
            'table_name' => 'admin',
        ]);

        if (!$permission->exists) {
            $permission->save();
            $role = Role::where('name', 'admin')->first();
            if (!is_null($role)) {
                $role->permissions()->attach($permission);
            }
        }
    }


    /**
     * Create Schema
     *
     * @return none
     */
    public function createChatTable() {
        
        if(Schema::hasTable('nabco_thread')) return false;

        Schema::create('nabco_thread',function(Blueprint $table) {
            
            $table->increments('id');

            $table->longText('message');
            
            $table->string('email_address')->index();

            $table->string('user_id')->index();
            
            $table->timestamps();
            
        });

    }
}