<?php

namespace App\View\Composers;

use App\Data\StaticData;
use App\PostTypes\TeamMember as TeamMemberPostType;
use Roots\Acorn\View\Composer;
use WP_Post;
use WP_Query;

class AboutPage extends Composer
{
    protected static $views = [
        'page-about',
    ];

    public function with(): array
    {
        $pageId = $this->pageId();

        return [
            'aboutIntro'   => $this->intro($pageId),
            'aboutVm'      => $this->visionMission($pageId),
            'aboutBoard'   => $this->boardOfDirectors(),
            'aboutValues'  => $this->values($pageId),
            'aboutWhy'     => $this->why($pageId),
            'aboutOffice'  => $this->officePhotos($pageId),
            'aboutClosing' => $this->closing($pageId),
        ];
    }

    private function pageId(): int
    {
        $page = \get_page_by_path('about');
        return $page ? (int) $page->ID : 0;
    }

    private function intro(int $pageId): array
    {
        $image    = $this->field('about_intro_image', $pageId);
        $imageUrl = is_array($image) ? ($image['url'] ?? '') : '';

        return [
            'eyebrow'       => (string) $this->field('about_intro_eyebrow', $pageId, 'About Planetario'),
            'headlineLead'  => (string) $this->field('about_intro_headline_lead', $pageId, 'A realty house built on'),
            'headlineEm'    => (string) $this->field('about_intro_headline_emphasis', $pageId, 'relationships,'),
            'headlineTrail' => (string) $this->field('about_intro_headline_trail', $pageId, 'not transactions.'),
            'lead'          => (string) $this->field('about_intro_lead', $pageId, 'Planetario Realty & Brokerage Services Inc. is a trusted Bohol-rooted realty company dedicated to professional, reliable, and client-focused property solutions. We specialize in property sales, brokerage, and real-estate services across the Visayas committed to helping clients find the right investments while ensuring smooth and transparent transactions.'),
            'imageUrl'      => $imageUrl ?: 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1600&h=686&fit=crop&q=80',
            'imageAlt'      => is_array($image) ? (string) ($image['alt'] ?? '') : 'Bohol landscape',
        ];
    }

    private function visionMission(int $pageId): array
    {
        return [
            'vision'  => (string) $this->field('about_vm_vision', $pageId, '<p>To be a <em>world-class</em> real-estate company delivering exceptional service to clients, salespeople, business partners, and team members transforming lives by creating opportunities for growth, empowering communities, and fostering progress, all while contributing to a sustainable future for our planet.</p>'),
            'mission' => (string) $this->field('about_vm_mission', $pageId, "<p>To deliver <em>world-class</em> services in the realty industry ensuring our clients' happiness and complete satisfaction. We continuously enhance our competitive edge through innovation, motivation, and training, while fostering long-term relationships built on trust and excellence.</p>"),
        ];
    }

    private function boardOfDirectors(): array
    {
        if (! \post_type_exists(TeamMemberPostType::POST_TYPE)) {
            return [];
        }

        $query = new WP_Query([
            'post_type'      => TeamMemberPostType::POST_TYPE,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => ['menu_order' => 'ASC', 'date' => 'ASC'],
            'tax_query'      => [[
                'taxonomy' => TeamMemberPostType::TAX_ROLE,
                'field'    => 'name',
                'terms'    => 'Board of Directors',
            ]],
        ]);

        if (! $query->have_posts()) {
            return [];
        }

        return array_map(function (WP_Post $post): array {
            $photo    = \get_field('team_photo', $post->ID);
            $photoUrl = is_array($photo) ? ($photo['url'] ?? '') : '';
            if (! $photoUrl) {
                $photoUrl = 'https://i.pravatar.cc/500?u=' . rawurlencode($post->post_title);
            }
            return [
                'name'  => $post->post_title,
                'role'  => (string) \get_field('team_title', $post->ID),
                'photo' => $photoUrl,
                'bio'   => (string) \get_field('team_bio', $post->ID),
            ];
        }, $query->posts);
    }

    private function values(int $pageId): array
    {
        $rows = is_array($raw = \get_field('about_values_items', $pageId)) ? $raw : [];
        if (empty($rows)) {
            $items = array_map(static fn ($v) => [
                'number'      => $v['n'],
                'title'       => $v['t'],
                'description' => $v['d'],
            ], StaticData::values());
        } else {
            $items = array_map(static fn ($r) => [
                'number'      => (string) ($r['number'] ?? ''),
                'title'       => (string) ($r['title'] ?? ''),
                'description' => (string) ($r['description'] ?? ''),
            ], $rows);
        }

        return [
            'eyebrow'      => (string) $this->field('about_values_eyebrow', $pageId, 'Core values'),
            'headlineLead' => (string) $this->field('about_values_headline_lead', $pageId, 'Five quiet commitments.'),
            'headlineEm'   => (string) $this->field('about_values_headline_emphasis', $pageId, 'One steady house.'),
            'intro'        => (string) $this->field('about_values_intro', $pageId, "These aren't poster words. They show up in the smallest decisions: which call we return first, what we tell a buyer when a listing isn't right, who we hire and who we don't."),
            'items'        => $items,
        ];
    }

    private function why(int $pageId): array
    {
        $rows = is_array($raw = \get_field('about_why_items', $pageId)) ? $raw : [];
        if (empty($rows)) {
            $items = [
                ['number' => 'I',  'title' => 'Licensed & experienced',  'description' => 'PRC-licensed brokers backed by a senior team with two decades of Bohol and Cebu market experience.'],
                ['number' => 'II', 'title' => 'Strong network',          'description' => 'Direct working relationships with six developers, surveyors, notaries, and BIR offices across both provinces.'],
                ['number' => 'III','title' => 'Transparent process',     'description' => 'Every figure, every fee, every step written down. Nothing surfaces late.'],
                ['number' => 'IV', 'title' => 'Client-first approach',   'description' => 'Patient site visits. Plain-language explanations. No pressure, no rushed closings.'],
                ['number' => 'V',  'title' => 'Vetted inventory',        'description' => 'Every listing passes a title, build-quality, and pricing review before it reaches our site.'],
                ['number' => 'VI', 'title' => 'End-to-end documentation','description' => 'BIR, RD, LGU filings handled in-house. You sign we walk the paper from start to finish.'],
            ];
        } else {
            $items = array_map(static fn ($r) => [
                'number'      => (string) ($r['number'] ?? ''),
                'title'       => (string) ($r['title'] ?? ''),
                'description' => (string) ($r['description'] ?? ''),
            ], $rows);
        }

        return [
            'eyebrow'      => (string) $this->field('about_why_eyebrow', $pageId, 'Why choose Planetario'),
            'headlineLead' => (string) $this->field('about_why_headline_lead', $pageId, 'Five practical reasons.'),
            'headlineEm'   => (string) $this->field('about_why_headline_emphasis', $pageId, 'Felt, not advertised.'),
            'items'        => $items,
        ];
    }

    private function officePhotos(int $pageId): array
    {
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

        return [
            'eyebrow'  => (string) $this->field('about_office_eyebrow', $pageId, 'Our workspace'),
            'headline' => (string) $this->field('about_office_headline', $pageId, 'Where we work'),
            'photos'   => $photos,
        ];
    }

    private function closing(int $pageId): array
    {
        return [
            'quote'           => (string) $this->field('about_closing_quote', $pageId, '"We work with you every step of the way from property selection to closing ensuring a <em>hassle-free</em> and <em>secure</em> transaction."'),
            'attribution'     => (string) $this->field('about_closing_attribution', $pageId, 'A word from our founders'),
            'primaryLabel'    => (string) $this->field('about_closing_primary_label', $pageId, 'Meet the team'),
            'primaryUrl'      => (string) $this->field('about_closing_primary_url', $pageId, \home_url('/team')),
            'secondaryLabel'  => (string) $this->field('about_closing_secondary_label', $pageId, 'Get in touch'),
            'secondaryUrl'    => (string) $this->field('about_closing_secondary_url', $pageId, \home_url('/contact')),
        ];
    }

    private function field(string $name, int $pageId, mixed $fallback = ''): mixed
    {
        if (! function_exists('get_field')) return $fallback;
        $value = \get_field($name, $pageId);
        return ($value === null || $value === '' || $value === false) ? $fallback : $value;
    }
}
