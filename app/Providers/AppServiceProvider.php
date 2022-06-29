<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use ConsoleTVs\Charts\Registrar as Charts;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        //    if (env('APP_ENV') === 'production') {
        // $this->app['request']->server->set('HTTPS', true);
   // }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Charts $charts)
    
    {
        $charts->register([
            \App\Charts\FaultIncidencesByType::class,
            \App\Charts\AverageAcknowledgeTimePerFaultChart::class,
            \App\Charts\AverageResolveTimePerFaultChart::class,
          
        ]);
        Schema::defaultStringLength(191);
    //     //this is for the geolocation
    //URL::forceScheme('https');// use for ngrok only.
      

    //    if (env('APP_ENV') === 'local') {
    //     $this->app['request']->server->set('HTTPS', true);
    // }
    }
}
