<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use App\Data\StaticData;
use App\PostTypes\Developer as DeveloperPostType;
use App\PostTypes\TeamMember as TeamMemberPostType;

use WP_Post;
use WP_Query;

class FrontPage extends Composer
{
    protected static $views = [
        'front-page',
    ];

    public function with(): array
    {
        $members = $this->members();
        $byTier  = $this->groupByTier($members);
        return [
            'hero'                 => $this->hero(),
            'commitment'           => $this->commitment(),
            'vm'                   => $this->visionMission(),
            'locations'            => $this->locations(),
            'officePhotos'         => $this->officePhotos(),
            'ctaBanner'            => $this->ctaBanner(),
            'accreditedDevelopers' => $this->accreditedDevelopersByRegion(),
            'team'             => $members,
            'boardOfDirectors' => $byTier['Board of Directors'] ?? [],
            'brokers'          => $byTier['broker'] ?? [],
            'boholManagers'    => $this->sortByManagerOrder($byTier['Bohol Managers'] ?? []),
            'cebuManagers'     => $this->sortByManagerOrder($byTier['Cebu Managers']  ?? []),
            'boholStaffs'      => $this->sortByStaffOrder($byTier['Bohol Staff']      ?? []),
            'cebuStaffs'       => $this->sortByStaffOrder($byTier['Cebu Staff']        ?? []),
            'featuredContent'  => $this->featuredContent(),
        ];
    }

    private function featuredContent(): array
    {
        $storiesPage = \get_page_by_path('stories');
        $eventsPage  = \get_page_by_path('events');
        $blogPage    = \get_page_by_path('blog');

        return [
            'storiesUrl' => $storiesPage ? \get_permalink($storiesPage) : \home_url('/stories'),
            'eventsUrl'  => $eventsPage  ? \get_permalink($eventsPage)  : \home_url('/events'),
            'blogUrl'    => $blogPage    ? \get_permalink($blogPage)     : \home_url('/blog'),
            'stories'    => $this->featuredStories(),
            'events'     => $this->featuredEvents(),
            'blogPosts'  => $this->featuredBlogPosts(),
        ];
    }

    private function featuredStories(): array
    {
        if (! \post_type_exists('story')) {
            return [];
        }

        $query = new WP_Query([
            'post_type'      => 'story',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
            'no_found_rows'  => true,
        ]);

        $items = [];
        foreach ($query->posts as $post) {
            $image    = \get_field('story_image', $post->ID);
            $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';
            if (! $imageUrl) {
                $imageUrl = (string) (\get_field('story_image_url', $post->ID)
                    ?: \get_the_post_thumbnail_url($post->ID, 'large'));
            }
            $items[] = [
                'client'   => $post->post_title,
                'quote'    => (string) \get_field('story_quote', $post->ID),
                'location' => (string) \get_field('story_location', $post->ID),
                'year'     => (string) \get_field('story_year', $post->ID),
                'image'    => $imageUrl ?: '',
            ];
        }
        \wp_reset_postdata();

        return $items;
    }

    private function featuredEvents(): array
    {
        if (! \post_type_exists('company_event')) {
            return [];
        }

        $query = new WP_Query([
            'post_type'      => 'company_event',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'meta_key'       => 'event_date',
            'orderby'        => ['meta_value' => 'DESC', 'menu_order' => 'ASC'],
            'no_found_rows'  => true,
        ]);

        $items = [];
        foreach ($query->posts as $post) {
            $rawDate   = (string) \get_post_meta($post->ID, 'event_date', true);
            $coverField = function_exists('get_field') ? \get_field('event_cover', $post->ID) : null;
            $cover      = is_array($coverField) ? ($coverField['url'] ?? '') : '';
            if (! $cover) {
                $cover = (string) (\get_the_post_thumbnail_url($post->ID, 'large') ?: '');
            }
            $items[] = [
                'title'     => $post->post_title,
                'permalink' => \get_permalink($post),
                'cover'     => $cover,
                'dateLabel' => $rawDate ? \date_i18n('F j, Y', strtotime($rawDate)) : '',
                'location'  => (string) \get_post_meta($post->ID, 'event_location', true),
            ];
        }
        \wp_reset_postdata();

        return $items;
    }

    private function featuredBlogPosts(): array
    {
        $query = new WP_Query([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => 3,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        ]);

        $items = [];
        foreach ($query->posts as $post) {
            $cats = array_map(static fn ($c) => [
                'name' => $c->name,
                'url'  => \get_category_link($c->term_id),
            ], \get_the_category($post->ID) ?: []);

            $items[] = [
                'title'         => $post->post_title,
                'permalink'     => \get_permalink($post),
                'thumbnail'     => (string) (\get_the_post_thumbnail_url($post->ID, 'large') ?: ''),
                'dateFormatted' => \get_the_date('F j, Y', $post),
                'excerpt'       => \wp_trim_words(\get_the_excerpt($post), 20, '…'),
                'categories'    => $cats,
            ];
        }
        \wp_reset_postdata();

        return $items;
    }

    private function officePhotos(): array
    {
        if (! function_exists('get_field')) {
            return [];
        }

        $page   = \get_page_by_path('about');
        $pageId = $page ? (int) $page->ID : 0;
        if (! $pageId) {
            return [];
        }

        $raw  = \get_field('about_office_gallery', $pageId);
        $rows = is_array($raw) ? $raw : [];

        $photos = [];
        foreach ($rows as $row) {
            $img = is_array($row['photo'] ?? null) ? $row['photo'] : null;
            if (! $img || empty($img['url'])) {
                continue;
            }
            $photos[] = [
                'url' => (string) $img['url'],
                'alt' => (string) ($img['alt'] ?? ''),
            ];
        }

        return $photos;
    }

    public function members(): array
    {
        if (! \post_type_exists(TeamMemberPostType::POST_TYPE)) {
            return $this->fallback();
        }

        $query = new WP_Query([
            'post_type'      => TeamMemberPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
        ]);

        if (! $query->have_posts()) return $this->fallback();

        return array_map([$this, 'normalize'], $query->posts);
    }

    private function normalize(WP_Post $post): array
    {
        $photo    = \get_field('team_photo', $post->ID);
        $photoUrl = is_array($photo) ? ($photo['url'] ?? '') : '';
        if (! $photoUrl) {
            $photoUrl = 'https://i.pravatar.cc/500?u=' . rawurlencode($post->post_title);
        }

        $terms = \get_the_terms($post->ID, TeamMemberPostType::TAX_ROLE);
        $tier  = is_array($terms) && ! empty($terms) ? (string) $terms[0]->name : '';

        return [
            'name'             => $post->post_title,
            'role'             => (string) \get_field('team_title', $post->ID),
            'tier'             => $tier ?: 'staff',
            'region'           => (string) (\get_field('team_region', $post->ID) ?: 'all'),
            'bio'              => (string) \get_field('team_bio', $post->ID),
            'photo'            => $photoUrl,
            'email'            => (string) \get_field('team_email', $post->ID),
            'linkedin'         => (string) \get_field('team_linkedin', $post->ID),
            'managing_broker'  => (bool) \get_field('team_managing_broker', $post->ID),
        ];
    }

    private function rankRole(string $role, array $map): int
    {
        $lower = strtolower($role);
        foreach ($map as $keyword => $rank) {
            if (strpos($lower, $keyword) !== false) {
                return $rank;
            }
        }
        return 999;
    }

    private function sortByManagerOrder(array $members): array
    {
        $map = [
            'senior division manager' => 1,
            'division manager'        => 2,
            'sales manager'           => 3,
        ];
        usort($members, fn($a, $b) =>
            $this->rankRole($a['role'], $map) <=> $this->rankRole($b['role'], $map)
        );
        return $members;
    }

    private function sortByStaffOrder(array $members): array
    {
        $map = [
            'general manager'   => 1,
            'operation manager' => 2,
            'accounting'        => 3,
            'marketing head'    => 4,
            'compliance head'   => 5,
            'collection head'   => 6,
            'collection team'   => 7,
            'marketing officer' => 8,
            'admin officer'     => 9,
            'data analyst'      => 10,
        ];
        usort($members, fn($a, $b) =>
            $this->rankRole($a['role'], $map) <=> $this->rankRole($b['role'], $map)
        );
        return $members;
    }

    private function groupByTier(array $members): array
    {
        $groups = [];
        foreach ($members as $m) {
            $tier = $m['tier'] ?? 'staff';
            $groups[$tier][] = $m;
        }
        return $groups;
    }

    public function accreditedDevelopersByRegion(int $limit = 12): array
    {
        $devs    = $this->accreditedDevelopers($limit);
        $grouped = ['bohol' => [], 'cebu' => []];

        foreach ($devs as $d) {
            $region = strtolower(trim($d['region'] ?? ''));
            if (str_contains($region, 'cebu')) {
                $grouped['cebu'][] = $d;
            } else {
                $grouped['bohol'][] = $d;
            }
        }

        return $grouped;
    }

    public function accreditedDevelopers(int $limit = 12): array
    {
        if (! \post_type_exists(DeveloperPostType::POST_TYPE) || ! function_exists('get_field')) {
            return array_slice(StaticData::developers(), 0, $limit);
        }

        $query = new WP_Query([
            'post_type'      => DeveloperPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
            'no_found_rows'  => true,
        ]);

        if (! $query->have_posts()) {
            return array_slice(StaticData::developers(), 0, $limit);
        }

        return array_map([$this, 'normalizeDeveloper'], $query->posts);
    }

    private function normalizeDeveloper(WP_Post $post): array
    {
        $logo = \get_field('developer_logo', $post->ID);

        return [
            'name'    => $post->post_title,
            'region'  => (string) \get_field('developer_region', $post->ID),
            'website' => (string) \get_field('developer_website', $post->ID),
            'logo'    => is_array($logo) ? ($logo['url'] ?? '') : '',
        ];
    }
    public function hero(): array
    {
        $pageId = (int) \get_option('page_on_front');

        return [
            'eyebrow'        => $this->field('hero_eyebrow', $pageId, 'Bohol · Cebu · Visayas'),
            'headlineLead'   => $this->field('hero_headline_lead', $pageId, 'A home is the long answer to a'),
            'headlineEm'     => $this->field('hero_headline_emphasis', $pageId, 'short prayer.'),
            'sub'            => $this->field('hero_sub', $pageId, "Planetario Realty & Brokerage Services Inc. has guided Boholano families, OFW investors, and first-time buyers across the Visayas for nearly a decade with patience, with paperwork done right, and with the kind of honesty that keeps clients coming back."),
            'images'         => $this->heroImages($pageId),
            'primaryCta'   => [
                'label' => $this->field('hero_primary_cta_label', $pageId, 'Browse properties'),
                'url'   => $this->field('hero_primary_cta_url', $pageId, \home_url('/properties')),
                'icon'  => $this->field('hero_primary_cta_icon', $pageId, 'arrow'),
            ],
            'secondaryCta' => [
                'label' => $this->field('hero_secondary_cta_label', $pageId, 'Talk to a broker'),
                'url'   => $this->field('hero_secondary_cta_url', $pageId, \home_url('/contact')),
                'icon'  => $this->field('hero_secondary_cta_icon', $pageId, 'arrow'),
            ],
            'stats'        => $this->stats($pageId),
        ];
    }

    private function heroImages(int $pageId): array
    {
        $defaults = [
            ['id' => 0, 'url' => 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=1920&h=1080&fit=crop&q=80', 'alt' => '', 'transition' => 'crossfade'],
            ['id' => 0, 'url' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1920&h=1080&fit=crop&q=80', 'alt' => '', 'transition' => 'slide'],
            ['id' => 0, 'url' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1920&h=1080&fit=crop&q=80', 'alt' => '', 'transition' => 'zoom'],
        ];

        $raw    = \get_post_meta($pageId, \App\Admin\HeroSlidesMetaBox::META_KEY, true);
        $slides = json_decode($raw ?: '[]', true);

        if (! empty($slides) && is_array($slides)) {
            return $slides;
        }

        return $defaults;
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
                'icon'  => $this->field('commitment_cta_icon', $pageId, 'arrow'),
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
                'icon'  => $this->field('vm_cta_icon', $pageId, 'arrow'),
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
                    'eyebrow'     => 'Our Office',
                    'title'       => '66 Remolador Ext.Brgy.Cogon Tagbilaran City',
                    'description' => 'beside Reddorze Building or JazzM Building .',
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
                'icon'  => $this->field('cta_primary_icon', $pageId, 'arrow'),
            ],
            'secondary'  => [
                'label' => $this->field('cta_secondary_label', $pageId, 'Browse listings'),
                'url'   => $this->field('cta_secondary_url', $pageId, \home_url('/properties')),
                'icon'  => $this->field('cta_secondary_icon', $pageId, 'arrow'),
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
