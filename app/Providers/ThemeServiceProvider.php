<?php

namespace App\Providers;

use App\Admin\AcfMetaboxUi;
use App\Admin\HeroSlidesMetaBox;
use App\Admin\InlineEditorApi;
use App\Admin\MetaboxSettingsPage;
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
use App\PostTypes\BlogPost as BlogPostType;
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
        $this->commands([\App\Console\Commands\SeedDemoData::class]);
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
        \add_action('acf/init', [PropertyFields::class, 'register']);
        \add_action('acf/init', [TestimonialFields::class, 'register']);
        \add_action('acf/init', [TeamMemberFields::class, 'register']);
        \add_action('acf/init', [DeveloperFields::class, 'register']);
        \add_action('acf/init', [StoryFields::class, 'register']);
        \add_action('acf/init', [CompanyEventFields::class, 'register']);

        \add_action('acf/init', function () {
            if (MetaboxSettingsPage::isEnabled('front_page_acf')) {
                FrontPage::register();
            }

            if (MetaboxSettingsPage::isEnabled('about_page_acf')) {
                AboutPage::register();
            }

            if (MetaboxSettingsPage::isEnabled('page_intros_acf')) {
                PageIntros::register();
            }

            if (MetaboxSettingsPage::isEnabled('page_admin_links')) {
                PageAdminLinks::register();
            }
        });

        if (MetaboxSettingsPage::isEnabled('hero_slides')) {
            \add_action('add_meta_boxes', [HeroSlidesMetaBox::class, 'register']);
            \add_action('save_post',      [HeroSlidesMetaBox::class, 'save'], 10, 2);
        }
        \add_action('admin_init', [AcfMetaboxUi::class, 'register']);
        \add_action('admin_init', [CompanyEventPostType::class, 'seed']);
        \add_action('admin_init', [BlogPostType::class, 'seed']);

        \add_action('rest_api_init', [InlineEditorApi::class, 'register']);

        SiteIdentityPage::register();
        SiteIdentitySync::register();
        ThemeColorsPage::register();
        TeamImportPage::register();
        MetaboxSettingsPage::register();
    }
}
