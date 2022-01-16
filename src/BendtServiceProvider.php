<?php

namespace Bendt\Report;

use Illuminate\Support\ServiceProvider;

class BendtServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/bendt-report.php' => config_path('bendt-report.php'),
            __DIR__.'/Enums/ExampleReportList.php' => app_path('Enums/ExampleReportList.php'),
        ], 'config');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
