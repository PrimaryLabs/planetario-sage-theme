<?php

namespace App\Providers;

use App\Fields\FrontPage;
use App\Fields\SiteSettings;
use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        \add_action('acf/init', [SiteSettings::class, 'register']);
        \add_action('acf/init', [FrontPage::class, 'register']);
    }
}
