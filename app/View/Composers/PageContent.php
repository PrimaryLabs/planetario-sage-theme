<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class PageContent extends Composer
{
    protected static $views = [
        'page-blog',
        'page-contact',
        'page-developers',
        'page-stories',
        'page-testimonials',
        'page-properties',
        'page-team',
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

        return [
            'pageIntro'   => $this->intro($pageId, $defaults['intro'] ?? []),
            'pageClosing' => $this->closing($pageId, $defaults['closing'] ?? []),
        ];
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

    private function ensureUrl(string $url): string
    {
        if ($url === '' || preg_match('#^(https?:|mailto:|tel:)#i', $url)) return $url;
        return \home_url('/' . ltrim($url, '/'));
    }
}
