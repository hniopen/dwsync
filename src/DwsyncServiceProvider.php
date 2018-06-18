<?php

namespace Hni\Dwsync;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class DwsyncServiceProvider extends LaravelServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {

        $this->handleConfigs();
        $this->handleHelpers();
        $this->handleMigrations();
        $this->handleSeeds();
        $this->handleViews();
        // $this->handleTranslations();
        $this->handleRoutes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        // register our controller
        $this->app->make('Hni\Dwsync\Http\Controllers\DwEntityTypeController');
        $this->app->make('Hni\Dwsync\Http\Controllers\DwProjectController');
        $this->app->make('Hni\Dwsync\Http\Controllers\DwQuestionController');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [];
    }

    private function handleConfigs() {
        $configPath = __DIR__ . '/../config/dwsync.php';
        $this->publishes([$configPath => config_path('dwsync.php')], 'config');
        $this->mergeConfigFrom($configPath, 'dwsync');
    }

    private function handleHelpers() {
        $helpersPath =  __DIR__.'/../helpers';
        $dwHelperPath = $helpersPath.'/dw.php';
        $pushIdnrHelperPath = $helpersPath.'/push_idnr.php';
        require $dwHelperPath;
        require $pushIdnrHelperPath;
        $this->publishes([$helpersPath => base_path('app/helpers')], 'helper');
    }

    private function handleTranslations() {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'dwsync');
    }

    private function handleViews() {
        $overrideView = config('dwsync.overrideViews');
        if($overrideView)
            $this->loadViewsFrom(base_path('resources/views/dwsync'), 'dwsync');
        else
            $this->loadViewsFrom(__DIR__.'/../views', 'dwsync');
        $this->publishes([__DIR__.'/../views' => base_path('resources/views/dwsync')], 'view');
    }

    private function handleMigrations() {
        $this->publishes([__DIR__ . '/../migrations' => base_path('database/migrations')], 'migration');
    }

    private function handleSeeds() {
        $this->publishes([__DIR__ . '/Seeds' => base_path('database/seeds')], 'seed');
    }

    private function handleRoutes() {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
    }
}
