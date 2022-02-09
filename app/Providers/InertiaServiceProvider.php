<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Inertia::share('errors', function () {
        //     if (session()->get('errors')) {
        //         $bags = [];
        
        //         foreach (session()->get('errors')->getBags() as $bag => $error) {
        //             $bags[$bag] = $error->getMessages();
        //         }
        
        //         return $bags;
        //     }
        
        //     return (object)[];
        // });

        Inertia::share(
            'flash', function () {
                return [
                'message' => session()->get('message'),
                ];
            }
        );
    }
}
