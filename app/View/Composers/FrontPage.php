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
            'hero'       => $this->hero(),
            'commitment' => $this->commitment(),
            'vm'         => $this->visionMission(),
            'locations'  => $this->locations(),
            'ctaBanner'  => $this->ctaBanner(),
        ];
    }

    public function hero(): array
    {
        $pageId = (int) \get_option('page_on_front');
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
                'url'   => $this->field('hero_primary_cta_url', $pageId, \home_url('/properties')),
            ],
            'secondaryCta' => [
                'label' => $this->field('hero_secondary_cta_label', $pageId, 'Talk to a broker'),
                'url'   => $this->field('hero_secondary_cta_url', $pageId, \home_url('/contact')),
            ],
            'stats'        => $this->stats($pageId),
        ];
    }

    public function commitment(): array
    {
        $pageId = (int) \get_option('page_on_front');

        return [
            'eyebrow'      => $this->field('commitment_eyebrow', $pageId, 'Our commitment'),
            'headlineLead' => $this->field('commitment_headline_lead', $pageId, 'We walk with you from'),
            'headlineEm'   => $this->field('commitment_headline_emphasis', $pageId, 'first viewing'),
            'headlineTrail' => $this->field('commitment_headline_trail', $pageId, 'to final signature.'),
            'paragraph1'   => $this->field('commitment_paragraph_1', $pageId, "You don't have to worry about documentation, coordination, or negotiation. We handle it all and we'll tell you to wait if the unit isn't right. That kind of honesty is rarer than a clean title."),
            'paragraph2'   => $this->field('commitment_paragraph_2', $pageId, "Most of our clients are referrals from clients. That's a number we work quietly to keep true."),
            'cta'          => [
                'label' => $this->field('commitment_cta_label', $pageId, 'Read our story'),
                'url'   => $this->field('commitment_cta_url', $pageId, \home_url('/about')),
            ],
        ];
    }

    public function visionMission(): array
    {
        $pageId = (int) \get_option('page_on_front');

        return [
            'eyebrow'       => $this->field('vm_eyebrow', $pageId, 'Who we are'),
            'headlineLead'  => $this->field('vm_headline_lead', $pageId, 'A'),
            'headlineEm'    => $this->field('vm_headline_emphasis', $pageId, 'steady house'),
            'headlineTrail' => $this->field('vm_headline_trail', $pageId, 'with a planet-sized horizon.'),
            'intro'         => $this->field('vm_intro', $pageId, 'Two quiet commitments anchor everything we do the company we want to be, and the work we promise our clients every day.'),
            'vision'        => $this->field('vm_vision', $pageId, '<p>To be a <em>world-class</em> real-estate company delivering exceptional service to clients, salespeople, business partners, and team members transforming lives by creating opportunities for growth, empowering communities, and fostering progress, all while contributing to a sustainable future for our planet.</p>'),
            'mission'       => $this->field('vm_mission', $pageId, "<p>To deliver <em>world-class</em> services in the realty industry ensuring our clients' happiness and complete satisfaction. We continuously enhance our competitive edge through innovation, motivation, and training, while fostering long-term relationships built on trust and excellence.</p>"),
            'cta'           => [
                'label' => $this->field('vm_cta_label', $pageId, 'Read the full story'),
                'url'   => $this->field('vm_cta_url', $pageId, \home_url('/about')),
            ],
        ];
    }

    public function locations(): array
    {
        $pageId = (int) \get_option('page_on_front');
        $rows   = function_exists('get_field') ? \get_field('locations_items', $pageId) : null;

        $items = [];
        if (is_array($rows) && ! empty($rows)) {
            foreach ($rows as $row) {
                $image = $row['image'] ?? null;
                $items[] = [
                    'eyebrow'     => (string) ($row['eyebrow'] ?? ''),
                    'title'       => (string) ($row['title'] ?? ''),
                    'description' => (string) ($row['description'] ?? ''),
                    'image'       => is_array($image) ? [
                        'url' => $image['url'] ?? '',
                        'alt' => $image['alt'] ?? '',
                    ] : null,
                ];
            }
        } else {
            $items = [
                [
                    'eyebrow'     => 'Bohol home',
                    'title'       => 'Tagbilaran, Panglao, Loboc, Carmen, Anda',
                    'description' => 'Our headquarters and where we know every road, every notary, and every honest builder.',
                    'image'       => [
                        'url' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1000&h=750&fit=crop&q=80',
                        'alt' => 'Bohol',
                    ],
                ],
                [
                    'eyebrow'     => 'Cebu sister team',
                    'title'       => 'Cebu City, Mactan, Talisay, Liloan',
                    'description' => 'Urban and bayfront condominiums, commercial floors, and gated family residences.',
                    'image'       => [
                        'url' => 'https://images.unsplash.com/photo-1514924013411-cbf25faa35bb?w=1000&h=750&fit=crop&q=80',
                        'alt' => 'Cebu',
                    ],
                ],
            ];
        }

        return [
            'eyebrow'      => $this->field('locations_eyebrow', $pageId, 'Where we work'),
            'headlineLead' => $this->field('locations_headline_lead', $pageId, 'Two islands.'),
            'headlineEm'   => $this->field('locations_headline_emphasis', $pageId, 'One quiet sea between them.'),
            'intro'        => $this->field('locations_intro', $pageId, "Planetario operates from Tagbilaran, with active brokerage across Bohol's coastal towns and a senior team in Cebu City."),
            'items'        => $items,
        ];
    }

    public function ctaBanner(): array
    {
        $pageId = (int) \get_option('page_on_front');

        return [
            'quoteLead'  => $this->field('cta_quote_lead', $pageId, 'Tell us what your next'),
            'quoteEm'    => $this->field('cta_quote_emphasis', $pageId, 'five years'),
            'quoteTrail' => $this->field('cta_quote_trail', $pageId, "should look like. We'll find the address."),
            'primary'    => [
                'label' => $this->field('cta_primary_label', $pageId, 'Book a tripping'),
                'url'   => $this->field('cta_primary_url', $pageId, \home_url('/contact')),
            ],
            'secondary'  => [
                'label' => $this->field('cta_secondary_label', $pageId, 'Browse listings'),
                'url'   => $this->field('cta_secondary_url', $pageId, \home_url('/properties')),
            ],
        ];
    }

    private function stats(int $pageId): array
    {
        $defaults = [
            1 => ['label' => 'Established',         'suffix' => '',   'value' => '2018'],
            2 => ['label' => 'Transactions Closed', 'suffix' => 'B+', 'value' => '₱2.4'],
            3 => ['label' => 'Families Placed',     'suffix' => '+',  'value' => '420'],
            4 => ['label' => 'Developer Partners',  'suffix' => '',   'value' => '6'],
        ];

        $stats = [];
        foreach ($defaults as $i => $fallback) {
            $group = function_exists('get_field') ? \get_field("hero_stat_{$i}", $pageId) : null;
            $row   = is_array($group) ? $group : [];

            $label  = (string) ($row['label']  ?? $fallback['label']);
            $value  = (string) ($row['value']  ?? $fallback['value']);
            $suffix = (string) ($row['suffix'] ?? $fallback['suffix']);

            if ($label === '' && $value === '') continue;

            [$prefix, $numeric] = $this->splitValue($value);

            $stats[] = [
                'label'    => $label,
                'prefix'   => $prefix,
                'value'    => $numeric !== '' ? $numeric : $value,
                'suffix'   => $suffix,
                'decimals' => $this->countDecimals($numeric),
            ];
        }

        return $stats;
    }

    /**
     * Split "₱2.4" → ["₱", "2.4"], "2018" → ["", "2018"].
     */
    private function splitValue(string $value): array
    {
        if (preg_match('/^(\D*)([-+]?\d+(?:\.\d+)?)/', $value, $m)) {
            return [$m[1], $m[2]];
        }
        return ['', ''];
    }

    private function countDecimals(string $value): int
    {
        if (preg_match('/[-+]?\d+\.(\d+)/', $value, $m)) {
            return strlen($m[1]);
        }
        return 0;
    }

    private function field(string $name, int $pageId, mixed $fallback = ''): mixed
    {
        if (! function_exists('get_field')) {
            return $fallback;
        }

        $value = \get_field($name, $pageId);

        return ($value === null || $value === '' || $value === false) ? $fallback : $value;
    }
}
