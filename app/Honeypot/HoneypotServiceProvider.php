<?php
/**
 * Service Provider for Honeypot spam detection
 * 
 * @category Spam
 * @package  Honeypot
 * @author   KRSP <agency@krsp.co>
 * @license  MIT http://krsp.co
 * @link     http://krsp.co
 */
namespace App\Honeypot;

use App\Honeypot\View\Components\Honeypot as ComponentsHoneypot;
use App\Honeypot\Honeypot;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider for Honeypot spam detection
 * 
 * @category Spam
 * @package  Honeypot
 * @author   KRSP <agency@krsp.co>
 * @license  MIT http://krsp.co
 * @link     http://krsp.co
 */
class HoneypotServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/honeypot.php', 'honeypot');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('honeypot', ComponentsHoneypot::class);
        view()->addNamespace('honeypot', app_path('Honeypot/views'));
        $this->app->singleton(
            Honeypot::class,
            function () {
                return new Honeypot(
                    $this->app['request'],
                    $this->app['config']->get('honeypot')
                );
            }
        );

        Honeypot::abortUsing(
            function () {
                return abort(404);
            }
        );
    }
}
