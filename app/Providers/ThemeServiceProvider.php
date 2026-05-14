<?php

namespace App\Providers;

use App\Fields\FrontPage;
use App\Fields\Property as PropertyFields;
use App\Fields\SiteSettings;
use App\Fields\Testimonial as TestimonialFields;
use App\PostTypes\Property as PropertyPostType;
use App\PostTypes\Testimonial as TestimonialPostType;
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

        \add_action('init', [PropertyPostType::class, 'register']);
        \add_action('init', [TestimonialPostType::class, 'register']);
        \add_action('acf/init', [SiteSettings::class, 'register']);
        \add_action('acf/init', [FrontPage::class, 'register']);
        \add_action('acf/init', [PropertyFields::class, 'register']);
        \add_action('acf/init', [TestimonialFields::class, 'register']);
    }
}
