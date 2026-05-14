<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class FrontPage extends Composer
{
    protected static $views = [
        'front-page',
    ];

    public function with(): array
    {
        return [
            'hero' => $this->hero(),
        ];
    }

    public function hero(): array
    {
        $pageId = (int) get_option('page_on_front');
        $image  = $this->field('hero_background_image', $pageId);

        return [
            'eyebrow'        => $this->field('hero_eyebrow', $pageId, 'Bohol · Cebu · Visayas'),
            'headlineLead'   => $this->field('hero_headline_lead', $pageId, 'A home is the long answer to a'),
            'headlineEm'     => $this->field('hero_headline_emphasis', $pageId, 'short prayer.'),
            'sub'            => $this->field('hero_sub', $pageId, "Planetario Realty & Brokerage Services Inc. has guided Boholano families, OFW investors, and first-time buyers across the Visayas for nearly a decade with patience, with paperwork done right, and with the kind of honesty that keeps clients coming back."),
            'image'          => is_array($image) ? [
                'url' => $image['url'] ?? '',
                'alt' => $image['alt'] ?? '',
            ] : [
                'url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920&h=1080&fit=crop&q=80',
                'alt' => '',
            ],
            'primaryCta'   => [
                'label' => $this->field('hero_primary_cta_label', $pageId, 'Browse properties'),
                'url'   => $this->field('hero_primary_cta_url', $pageId, home_url('/properties')),
            ],
            'secondaryCta' => [
                'label' => $this->field('hero_secondary_cta_label', $pageId, 'Talk to a broker'),
                'url'   => $this->field('hero_secondary_cta_url', $pageId, home_url('/contact')),
            ],
            'stats'        => $this->stats($pageId),
        ];
    }

    private function stats(int $pageId): array
    {
        $rows = function_exists('get_field') ? get_field('hero_stats', $pageId) : null;

        if (! is_array($rows) || empty($rows)) {
            return [
                ['prefix' => '',  'value' => '2018', 'decimals' => 0, 'suffix' => '',   'label' => 'Established'],
                ['prefix' => '₱', 'value' => '2.4',  'decimals' => 1, 'suffix' => 'B+', 'label' => 'Transactions Closed'],
                ['prefix' => '',  'value' => '420',  'decimals' => 0, 'suffix' => '+',  'label' => 'Families Placed'],
                ['prefix' => '',  'value' => '6',    'decimals' => 0, 'suffix' => '',   'label' => 'Developer Partners'],
            ];
        }

        return array_map(static fn ($row) => [
            'prefix'   => (string) ($row['prefix'] ?? ''),
            'value'    => (string) ($row['value'] ?? ''),
            'decimals' => (int) ($row['decimals'] ?? 0),
            'suffix'   => (string) ($row['suffix'] ?? ''),
            'label'    => (string) ($row['label'] ?? ''),
        ], $rows);
    }

    private function field(string $name, int $pageId, mixed $fallback = ''): mixed
    {
        if (! function_exists('get_field')) {
            return $fallback;
        }

        $value = get_field($name, $pageId);

        return ($value === null || $value === '' || $value === false) ? $fallback : $value;
    }
}
