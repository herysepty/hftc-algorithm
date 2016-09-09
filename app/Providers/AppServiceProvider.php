<?php

namespace herysepty\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Laravel <strong>5.2</strong> | PHP versi <strong>5.5.15</strong>.
        view()->share('footer_desc', '<div class="pull-right">
                        
                    </div>
                    <div>
                        <strong></strong> &copy;  '.date("Y").' Tugas Akhir. <a href="http://twitter.com/herysepty">@herysepty</a> 
                    </div>');
        view()->share('AppController', 'herysepty\Http\Controllers\AppController');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
