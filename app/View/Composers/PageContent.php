<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class PageContent extends Composer
{
    protected static $views = [
        'page-blog',
        'page-contact',
        'page-developers',
        'page-events',
        'page-stories',
        'page-testimonials',
        'page-properties',
        'page-team',
        'page-vlog',
    ];

    /**
     * Per-page default copy. Pull these from the prototype so we can render an empty page
     * gracefully before any ACF values are set.
     */
    private const DEFAULTS = [
        'blog' => [
            'intro' => [
                'eyebrow'       => 'Insights & news',
                'headlineLead'  => 'From the',
                'headlineEm'    => 'Planetario desk.',
                'headlineTrail' => '',
                'lead'          => 'Market updates, buying guides, and stories from the field — written by the brokers who close the deals.',
            ],
            'closing' => [
                'eyebrow'        => 'Ready to move?',
                'headlineLead'   => 'Reading is a start.',
                'headlineEm'     => 'A conversation',
                'body'           => 'gets you further. Tell us what you\'re looking for and one of our brokers will follow up the same day.',
                'primaryLabel'   => 'Get in touch',
                'primaryUrl'     => '/contact',
                'secondaryLabel' => 'Browse listings',
                'secondaryUrl'   => '/properties',
            ],
        ],
        'contact' => [
            'intro' => [
                'eyebrow'      => 'Contact us',
                'headlineLead' => "Tell us what you're",
                'headlineEm'   => 'looking for.',
                'headlineTrail'=> '',
                'lead'         => 'A real broker reads every message that comes through this form. Most replies go out within four working hours, often the same morning.',
            ],
            'closing' => [],
        ],
        'developers' => [
            'intro' => [
                'eyebrow'      => 'Developer partners',
                'headlineLead' => 'The builders we',
                'headlineEm'   => 'quietly stake our name on.',
                'headlineTrail'=> '',
                'lead'         => "We don't sell every developer in the Visayas — only the ones whose specifications, craft, and after-sales we can vouch for. Each name below has been a working partner of Planetario for at least three years.",
            ],
            'closing' => [
                'eyebrow'        => 'For developers',
                'headlineLead'   => 'Are you a Visayan developer with a',
                'headlineEm'     => 'project worth selling well?',
                'body'           => "We accept a small number of new developer mandates each year. If your phase is in Bohol, Cebu, Negros, or Siquijor — and your build and after-sales are something you'd let your own family buy into — we'd like to hear from you.",
                'primaryLabel'   => 'Submit your project',
                'primaryUrl'     => '/contact',
                'secondaryLabel' => '',
                'secondaryUrl'   => '',
            ],
        ],
        'stories' => [
            'intro' => [
                'eyebrow'      => 'Success stories',
                'headlineLead' => 'Four families.',
                'headlineEm'   => 'Four small chapters',
                'headlineTrail'=> 'in a longer book.',
                'lead'         => 'Every closing is a private milestone. With permission, we share a few here — not because the numbers are big, but because the people behind them trusted us with something that mattered.',
            ],
            'closing' => [
                'eyebrow'        => '',
                'headlineLead'   => 'What will',
                'headlineEm'     => 'your story',
                'body'           => 'sound like, three years from now?',
                'primaryLabel'   => 'Start a conversation',
                'primaryUrl'     => '/contact',
                'secondaryLabel' => 'Browse listings',
                'secondaryUrl'   => '/properties',
            ],
        ],
        'testimonials' => [
            'intro' => [
                'eyebrow'      => "In our clients' words",
                'headlineLead' => "We don't ask for these.",
                'headlineEm'   => 'They arrive anyway.',
                'headlineTrail'=> '',
                'lead'         => 'What follows is unedited — pulled from emails, voice notes, and handwritten cards that have come through our Tagbilaran office over the years. Names shared with permission.',
            ],
            'closing' => [
                'eyebrow'        => '',
                'headlineLead'   => '',
                'headlineEm'     => '',
                'body'           => '',
                'primaryLabel'   => 'Read the full success stories',
                'primaryUrl'     => '/stories',
                'secondaryLabel' => '',
                'secondaryUrl'   => '',
            ],
        ],
        'properties' => [
            'intro' => [
                'eyebrow'      => 'Current portfolio',
                'headlineLead' => 'Every listing here has',
                'headlineEm'   => 'passed our review.',
                'headlineTrail'=> '',
                'lead'         => "Title verified, build inspected, price benchmarked. If a property isn't on this page, we didn't think it was ready to be.",
            ],
            'closing' => [
                'eyebrow'        => 'Private inventory',
                'headlineLead'   => 'Some properties never see',
                'headlineEm'     => 'the public site.',
                'body'           => "Estate sales, off-market villas, developer pre-launches. If your brief is specific, send it to us directly and we'll match against listings held quietly with the firm.",
                'primaryLabel'   => 'Send a private brief',
                'primaryUrl'     => '/contact',
                'secondaryLabel' => '',
                'secondaryUrl'   => '',
            ],
        ],
        'events' => [
            'intro' => [
                'eyebrow'       => 'Company events',
                'headlineLead'  => 'Moments from the',
                'headlineEm'    => 'Planetario calendar.',
                'headlineTrail' => '',
                'lead'          => 'Launches, brokerage nights, community walks, and field trips — a running record of the team out in Visayas.',
            ],
            'closing' => [
                'eyebrow'        => 'Join the community',
                'headlineLead'   => 'Want to be part of',
                'headlineEm'     => 'what happens next?',
                'body'           => 'Our events are where buyers, brokers, and builders meet in person. Get in touch and we\'ll add you to the list.',
                'primaryLabel'   => 'Get in touch',
                'primaryUrl'     => '/contact',
                'secondaryLabel' => '',
                'secondaryUrl'   => '',
            ],
        ],
        'vlog' => [
            'intro' => [
                'eyebrow'      => 'From the field',
                'headlineLead' => 'Videos from',
                'headlineEm'   => 'Planetario.',
                'headlineTrail'=> '',
                'lead'         => 'Property walkthroughs, market updates, and moments from the Visayas — straight from our brokers on the ground.',
            ],
            'closing' => [
                'eyebrow'        => 'See it in person',
                'headlineLead'   => 'A video is a start.',
                'headlineEm'     => 'A site visit',
                'body'           => 'tells you everything the camera missed. Tell us which property caught your eye and we\'ll arrange a visit.',
                'primaryLabel'   => 'Get in touch',
                'primaryUrl'     => '/contact',
                'secondaryLabel' => 'Browse listings',
                'secondaryUrl'   => '/properties',
            ],
        ],
        'team' => [
            'intro' => [
                'eyebrow'      => 'The Planetario bench',
                'headlineLead' => "The people you'll actually",
                'headlineEm'   => 'shake hands with.',
                'headlineTrail'=> '',
                'lead'         => 'A PRC-licensed brokerage between Tagbilaran and Cebu City. Most of our bench has lived in the Visayas their whole lives. None of them will hand your file to someone else once your name is on it.',
            ],
            'closing' => [
                'eyebrow'        => 'Sales partnership',
                'headlineLead'   => 'Want to join the',
                'headlineEm'     => 'Planetario salesfloor?',
                'body'           => 'We accredit a small, vetted network of independent sales associates across Bohol and Cebu. Strong training, generous splits, and the quiet credibility of working under a PRC-licensed brokerage.',
                'primaryLabel'   => 'Apply to partner',
                'primaryUrl'     => '/contact',
                'secondaryLabel' => '',
                'secondaryUrl'   => '',
            ],
        ],
    ];

    public function with(): array
    {
        $slug = $this->currentSlug();
        $defaults = self::DEFAULTS[$slug] ?? ['intro' => [], 'closing' => []];

        $pageId = $this->pageId($slug);

        $data = [
            'pageIntro'   => $this->intro($pageId, $defaults['intro'] ?? []),
            'pageClosing' => $this->closing($pageId, $defaults['closing'] ?? []),
        ];

        if ($slug === 'developers') {
            $data['howWeWork'] = $this->howWeWork($pageId);
        }

        if ($slug === 'events') {
            $data['eventsSections'] = $this->eventsSections($pageId);
        }

        return $data;
    }

    private function currentSlug(): string
    {
        $post = \get_post();
        return $post ? (string) $post->post_name : '';
    }

    private function pageId(string $slug): int
    {
        $page = $slug ? \get_page_by_path($slug) : null;
        return $page ? (int) $page->ID : 0;
    }

    private function intro(int $pageId, array $defaults): array
    {
        return [
            'eyebrow'       => (string) $this->field('page_intro_eyebrow', $pageId, $defaults['eyebrow'] ?? ''),
            'headlineLead'  => (string) $this->field('page_intro_headline_lead', $pageId, $defaults['headlineLead'] ?? ''),
            'headlineEm'    => (string) $this->field('page_intro_headline_emphasis', $pageId, $defaults['headlineEm'] ?? ''),
            'headlineTrail' => (string) $this->field('page_intro_headline_trail', $pageId, $defaults['headlineTrail'] ?? ''),
            'lead'          => (string) $this->field('page_intro_lead', $pageId, $defaults['lead'] ?? ''),
        ];
    }

    private function closing(int $pageId, array $defaults): array
    {
        $primaryUrl   = $this->field('page_closing_primary_url', $pageId, $defaults['primaryUrl'] ?? '');
        $secondaryUrl = $this->field('page_closing_secondary_url', $pageId, $defaults['secondaryUrl'] ?? '');

        return [
            'eyebrow'        => (string) $this->field('page_closing_eyebrow', $pageId, $defaults['eyebrow'] ?? ''),
            'headlineLead'   => (string) $this->field('page_closing_headline_lead', $pageId, $defaults['headlineLead'] ?? ''),
            'headlineEm'     => (string) $this->field('page_closing_headline_emphasis', $pageId, $defaults['headlineEm'] ?? ''),
            'body'           => (string) $this->field('page_closing_body', $pageId, $defaults['body'] ?? ''),
            'primaryLabel'   => (string) $this->field('page_closing_primary_label', $pageId, $defaults['primaryLabel'] ?? ''),
            'primaryUrl'     => $this->ensureUrl($primaryUrl),
            'secondaryLabel' => (string) $this->field('page_closing_secondary_label', $pageId, $defaults['secondaryLabel'] ?? ''),
            'secondaryUrl'   => $this->ensureUrl($secondaryUrl),
        ];
    }

    private function field(string $name, int $pageId, mixed $fallback = ''): mixed
    {
        if (! $pageId || ! function_exists('get_field')) return $fallback;
        $value = \get_field($name, $pageId);
        return ($value === null || $value === '' || $value === false) ? $fallback : $value;
    }

    private function eventsSections(int $pageId): array
    {
        return [
            'featured' => [
                'eyebrow'    => (string) $this->field('events_featured_eyebrow', $pageId, 'Featured'),
                'headingLead' => (string) $this->field('events_featured_heading_lead', $pageId, 'Moments worth'),
                'headingEm'  => (string) $this->field('events_featured_heading_em', $pageId, 'marking.'),
            ],
            'all' => [
                'eyebrow'    => (string) $this->field('events_all_eyebrow', $pageId, 'All events'),
                'headingLead' => (string) $this->field('events_all_heading_lead', $pageId, 'The full'),
                'headingEm'  => (string) $this->field('events_all_heading_em', $pageId, 'calendar.'),
            ],
        ];
    }

    private function howWeWork(int $pageId): array
    {
        $rows = is_array($raw = $this->field('devs_how_items', $pageId, false)) ? $raw : [];

        return [
            'eyebrow'      => (string) $this->field('devs_how_eyebrow', $pageId, 'How we work with developers'),
            'headlineLead' => (string) $this->field('devs_how_headline_lead', $pageId, 'A small list.'),
            'headlineEm'   => (string) $this->field('devs_how_headline_em', $pageId, 'Carefully kept.'),
            'lead'         => (string) $this->field('devs_how_lead', $pageId, 'Our partnership is a stake on both sides. Here is what working with Planetario typically looks like — for the builder, and for the buyer.'),
            'items'        => empty($rows) ? self::defaultHowWeWorkItems() : array_map(static fn ($r) => [
                'num'   => (string) ($r['num'] ?? ''),
                'title' => (string) ($r['title'] ?? ''),
                'desc'  => (string) ($r['desc'] ?? ''),
            ], $rows),
        ];
    }

    private static function defaultHowWeWorkItems(): array
    {
        return [
            ['num' => '01', 'title' => 'Site visit & build audit', 'desc' => 'Before any unit reaches our listings, our senior brokers walk the site and inspect at least three completed projects from that developer.'],
            ['num' => '02', 'title' => 'Pricing benchmark', 'desc' => 'We benchmark every developer offering against comparable open-market inventory so our buyers see fair, current numbers.'],
            ['num' => '03', 'title' => 'Exclusive corridors', 'desc' => 'For several partners we hold first-look or exclusive selling rights in specific towns — Panglao, Anda, Carmen, and parts of Mactan.'],
            ['num' => '04', 'title' => 'Co-launch marketing', 'desc' => 'We co-fund the launch campaign, photography, and qualified-buyer events for selected developer phases.'],
            ['num' => '05', 'title' => 'Buyer protection', 'desc' => "We never market a phase whose pre-selling pricing or turnover dates we don't believe the developer can honor."],
            ['num' => '06', 'title' => 'Long view', 'desc' => 'Our partnerships are measured in decades, not units. Every developer here has been with us for at least three years.'],
        ];
    }

    private function ensureUrl(string $url): string
    {
        if ($url === '' || preg_match('#^(https?:|mailto:|tel:)#i', $url)) return $url;
        return \home_url('/' . ltrim($url, '/'));
    }
}
