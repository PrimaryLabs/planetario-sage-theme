<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class SiteSettings extends Composer
{
    protected static $views = [
        '*',
    ];

    public function with(): array
    {
        return [
            'site' => $this->site(),
        ];
    }

    public function site(): array
    {
        return [
            'brand'    => $this->brand(),
            'contact'  => $this->contact(),
            'socials'  => $this->socials(),
            'services' => $this->services(),
            'footer'   => $this->footer(),
        ];
    }

    private function services(): array
    {
        $rows = $this->repeater('services_items');
        if (empty($rows)) {
            $items = array_map(static fn ($s) => [
                'number'      => $s['num'],
                'title'       => $s['title'],
                'description' => $s['desc'],
            ], \App\Data\StaticData::services());
        } else {
            $items = array_map(static fn ($r) => [
                'number'      => (string) ($r['number'] ?? ''),
                'title'       => (string) ($r['title'] ?? ''),
                'description' => (string) ($r['description'] ?? ''),
            ], $rows);
        }

        return [
            'eyebrow'      => $this->option('services_eyebrow', 'What we do'),
            'headlineLead' => $this->option('services_headline_lead', 'Six services.'),
            'headlineEm'   => $this->option('services_headline_emphasis', 'One promise.'),
            'intro'        => $this->option('services_intro', "Property goals are personal. Our services are built around the actual decisions you'll need to make not the listings we want to push."),
            'items'        => $items,
        ];
    }

    private function brand(): array
    {
        return [
            'name'        => $this->option('brand_name', 'Planetario Realty'),
            'legal'       => $this->option('brand_legal', 'Planetario Realty & Brokerage Services Inc.'),
            'tagline'     => $this->option('brand_tagline', 'Turning Property Dreams into Reality'),
            'short'       => $this->option('brand_short', 'A Bohol-rooted realty house, brokering homes and investments across the Visayas with care and clarity.'),
            'founded'     => $this->option('brand_founded', '2018'),
            'license'     => $this->option('brand_license', 'PRC Licensed Brokerage'),
            'logoUrl'     => $this->imageUrl('brand_logo', 'full'),
            'logoDarkUrl' => $this->imageUrl('brand_logo_dark', 'full'),
            'iconUrl'     => $this->imageUrl('brand_site_icon', 'full'),
            'ogImageUrl'  => $this->imageUrl('brand_og_image', 'full'),
        ];
    }

    private function contact(): array
    {
        return [
            'phone'         => $this->option('contact_phone', '0910 267 1424'),
            'phoneLink'     => $this->option('contact_phone_link', '09102671424'),
            'email'         => $this->option('contact_email', 'planetariorealtyandbrokerage@gmail.com'),
            'addressLine1'  => $this->option('contact_address_line_1', '66 Remolador Ext., Brgy. Cogon,'),
            'addressLine2'  => $this->option('contact_address_line_2', 'Tagbilaran City, Bohol'),
            'hoursWeekday'  => $this->option('contact_hours_weekday', 'Monday – Saturday · 8:00 to 18:00'),
            'hoursWeekend'  => $this->option('contact_hours_weekend', 'Sunday · by appointment'),
            'note'          => $this->option('contact_note', 'Walk-ins welcome at our Tagbilaran office. For Cebu meetings, our senior team coordinates a private appointment at a venue convenient to you.'),
        ];
    }

    private function socials(): array
    {
        $items = [];
        foreach (['facebook', 'instagram', 'linkedin', 'youtube'] as $key) {
            $url = $this->option("social_{$key}", '');
            if ($url) {
                $items[$key] = $url;
            }
        }

        return $items;
    }

    private function footer(): array
    {
        $explore = $this->repeater('footer_explore') ?: [
            ['label' => 'Properties', 'url' => '/properties'],
            ['label' => 'Developers', 'url' => '/developers'],
            ['label' => 'Success Stories', 'url' => '/stories'],
            ['label' => 'Testimonials', 'url' => '/testimonials'],
        ];

        $company = $this->repeater('footer_company') ?: [
            ['label' => 'About Us', 'url' => '/about'],
            ['label' => 'Our Team', 'url' => '/team'],
            ['label' => 'Contact', 'url' => '/contact'],
        ];

        return [
            'copyrightOwner' => $this->option('footer_copyright_owner', 'Planetario Realty & Brokerage Services Inc.'),
            'sigilLeft'      => $this->option('footer_sigil_left', 'PRC Lic. No. ████-██'),
            'sigilRight'     => $this->option('footer_sigil_right', 'Tagbilaran · Cebu'),
            'explore'        => array_map([$this, 'normalizeLink'], $explore),
            'company'        => array_map([$this, 'normalizeLink'], $company),
        ];
    }

    private function option(string $name, string $fallback = ''): string
    {
        if (! function_exists('get_field')) {
            return $fallback;
        }

        $value = \get_field($name, 'option');

        return ($value === null || $value === '' || $value === false) ? $fallback : (string) $value;
    }

    private function imageUrl(string $name, string $size = 'full'): string
    {
        if (! function_exists('get_field')) {
            return '';
        }

        $id = \get_field($name, 'option');
        if (! $id) {
            return '';
        }

        $src = \wp_get_attachment_image_src((int) $id, $size);

        return is_array($src) ? (string) $src[0] : '';
    }

    private function repeater(string $name): array
    {
        if (! function_exists('get_field')) {
            return [];
        }

        $rows = \get_field($name, 'option');

        return is_array($rows) ? $rows : [];
    }

    private function normalizeLink(array $row): array
    {
        $url = (string) ($row['url'] ?? '');
        if ($url !== '' && ! preg_match('#^(https?:|mailto:|tel:|/)#i', $url)) {
            $url = '/' . ltrim($url, '/');
        }
        if ($url !== '' && str_starts_with($url, '/')) {
            $url = \home_url($url);
        }

        return [
            'label' => (string) ($row['label'] ?? ''),
            'url'   => $url,
        ];
    }
}
