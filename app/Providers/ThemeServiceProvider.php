<?php

namespace App\Providers;

use App\Admin\AcfMetaboxUi;
use App\Admin\SiteIdentityPage;
use App\Admin\TeamImportPage;
use App\Admin\ThemeColorsPage;
use App\Fields\AboutPage;
use App\Fields\CompanyEvent as CompanyEventFields;
use App\Fields\Developer as DeveloperFields;
use App\Fields\FrontPage;
use App\Fields\PageAdminLinks;
use App\Fields\PageIntros;
use App\Fields\Property as PropertyFields;
use App\Fields\SiteSettings;
use App\Fields\Story as StoryFields;
use App\Fields\TeamMember as TeamMemberFields;
use App\Fields\Testimonial as TestimonialFields;
use App\PostTypes\CompanyEvent as CompanyEventPostType;
use App\PostTypes\Developer as DeveloperPostType;
use App\PostTypes\Property as PropertyPostType;
use App\PostTypes\Story as StoryPostType;
use App\PostTypes\TeamMember as TeamMemberPostType;
use App\PostTypes\Testimonial as TestimonialPostType;
use App\SiteIdentitySync;
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
        \add_action('init', [TeamMemberPostType::class, 'register']);
        \add_action('init', [DeveloperPostType::class, 'register']);
        \add_action('init', [StoryPostType::class, 'register']);
        \add_action('init', [CompanyEventPostType::class, 'register']);
        \add_action('acf/init', [SiteSettings::class, 'register']);
        \add_action('acf/init', [FrontPage::class, 'register']);
        \add_action('acf/init', [PropertyFields::class, 'register']);
        \add_action('acf/init', [TestimonialFields::class, 'register']);
        \add_action('acf/init', [TeamMemberFields::class, 'register']);
        \add_action('acf/init', [DeveloperFields::class, 'register']);
        \add_action('acf/init', [StoryFields::class, 'register']);
        \add_action('acf/init', [CompanyEventFields::class, 'register']);
        \add_action('acf/init', [AboutPage::class, 'register']);
        \add_action('acf/init', [PageIntros::class, 'register']);
        \add_action('acf/init', [PageAdminLinks::class, 'register']);
        \add_action('admin_init', [AcfMetaboxUi::class, 'register']);

        SiteIdentityPage::register();
        SiteIdentitySync::register();
        ThemeColorsPage::register();
        TeamImportPage::register();
    }
}
