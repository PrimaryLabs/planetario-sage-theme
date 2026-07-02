@extends('layouts.app')

@section('content')
@php
use App\Data\StaticData;
$properties = $featuredProperties ?? array_slice(StaticData::properties(), 0, 6);
$servicesData = $site['services'] ?? null;
$services = $servicesData['items'] ?? array_map(fn($s) => ['number' => $s['num'], 'title' => $s['title'], 'description' => $s['desc']], StaticData::services());
$testimonialItems = $testimonialsHighlights ?? array_slice(StaticData::testimonials(), 0, 6);
$accreditedDevelopers = $accreditedDevelopers ?? ['bohol' => [], 'cebu' => []];
@endphp

{{-- Hero --}}
<section class="hero">
  <div class="hero-bg">
    @foreach ($hero['images'] as $i => $img)
    <div
      class="hero-slide{{ $i === 0 ? ' is-active' : '' }}"
      data-transition="{{ $img['transition'] }}"
      aria-hidden="true">
      <img src="{{ $img['url'] }}" alt="{{ $img['alt'] }}">
    </div>
    @endforeach
  </div>
  <div class="hero-overlay"></div>
  <x-orbit-deco />

  @if (\is_user_logged_in() && \current_user_can('edit_posts'))
  <div class="hero-admin-bar">
    <a
      class="hero-admin-btn"
      href="{{ \admin_url('post.php?post=' . \get_option('page_on_front') . '&action=edit') }}"
      target="_blank"
      rel="noopener">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="3" y="3" width="18" height="18" rx="2" />
        <circle cx="8.5" cy="8.5" r="1.5" />
        <path d="M21 15l-5-5L5 21" />
      </svg>
      Edit Hero Images
    </a>
  </div>
  @endif

  <div class="container hero-inner">
    @if ($hero['eyebrow'])
    <span class="eyebrow"
      data-edit-field="hero_eyebrow"
      data-edit-type="text"
      data-edit-label="Hero Eyebrow">{{ $hero['eyebrow'] }}</span>
    @endif
    <h1 class="display hero-headline">
      <span
        data-edit-field="hero_headline_lead"
        data-edit-type="text"
        data-edit-label="Hero Headline Lead">{{ $hero['headlineLead'] }}</span>
      @if ($hero['headlineEm'])
      <em
        data-edit-field="hero_headline_emphasis"
        data-edit-type="text"
        data-edit-label="Hero Headline Emphasis">{{ $hero['headlineEm'] }}</em>
      @endif
    </h1>
    @if ($hero['sub'])
    <p class="hero-sub"
      data-edit-field="hero_sub"
      data-edit-type="nl2br"
      data-edit-label="Hero Subtitle">{!! nl2br(e($hero['sub'])) !!}</p>
    @endif
    <div class="hero-actions">
      @if ($hero['primaryCta']['label'])
      <a href="{{ $hero['primaryCta']['url'] }}"
        class="btn btn-primary"
        data-edit-type="button"
        data-edit-label="Hero Primary Button"
        data-edit-label-field="hero_primary_cta_label"
        data-edit-url-field="hero_primary_cta_url"
        data-edit-icon-field="hero_primary_cta_icon"
        data-edit-icon-value="{{ $hero['primaryCta']['icon'] }}">
        <span class="btn-text">{{ $hero['primaryCta']['label'] }}</span>
        @include('partials.btn-icon', ['icon' => $hero['primaryCta']['icon']])
      </a>
      @endif
      @if ($hero['secondaryCta']['label'])
      <a href="{{ $hero['secondaryCta']['url'] }}"
        class="btn"
        data-edit-type="button"
        data-edit-label="Hero Secondary Button"
        data-edit-label-field="hero_secondary_cta_label"
        data-edit-url-field="hero_secondary_cta_url"
        data-edit-icon-field="hero_secondary_cta_icon"
        data-edit-icon-value="{{ $hero['secondaryCta']['icon'] }}">
        <span class="btn-text">{{ $hero['secondaryCta']['label'] }}</span>
        @include('partials.btn-icon', ['icon' => $hero['secondaryCta']['icon']])
      </a>
      @endif
    </div>

    @if (! empty($hero['stats']))
    <div class="hero-meta">
      @foreach ($hero['stats'] as $i => $stat)
      <div class="item" style="display: {{ $i == 1 ? 'none' : 'block' }};">
        <div class="num">{{ $stat['prefix'] }}<span
            data-countup="{{ $stat['value'] }}"
            data-decimals="{{ $stat['decimals'] }}"
            @if ($stat['suffix']) data-suffix="{{ $stat['suffix'] }}" @endif>{{ $stat['value'] }}{{ $stat['suffix'] }}</span></div>
        <div class="lbl">{{ $stat['label'] }}</div>
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>

{{-- Vision / Mission --}}
<section class="section" style="background:var(--bg-1);border-top:0px solid var(--line);border-bottom:1px solid var(--line)">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        @if ($vm['eyebrow'])
        <span class="eyebrow"
          data-edit-field="vm_eyebrow"
          data-edit-type="text"
          data-edit-label="VM Eyebrow">{{ $vm['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="vm_headline_lead"
            data-edit-type="text"
            data-edit-label="VM Headline Lead">{{ $vm['headlineLead'] }}</span>
          @if ($vm['headlineEm'])
          <em
            data-edit-field="vm_headline_emphasis"
            data-edit-type="text"
            data-edit-label="VM Headline Emphasis">{{ $vm['headlineEm'] }}</em>
          @endif
          @if ($vm['headlineTrail'])
          <span
            data-edit-field="vm_headline_trail"
            data-edit-type="text"
            data-edit-label="VM Headline Trail">{{ $vm['headlineTrail'] }}</span>
          @endif
        </h2>
      </div>
      @if ($vm['intro'])
      <p class="lead reveal text-center" style="transition-delay:.1s"
        data-edit-field="vm_intro"
        data-edit-type="textarea"
        data-edit-label="VM Intro">{{ $vm['intro'] }}</p>
      @endif
    </div>

    <div class="vm-stage">
      <article class="vm-card vm-card--vision reveal">
        <div class="vm-card__bg" aria-hidden="true"></div>
        <div class="vm-card__inner">
          <span class="vm-card__eyebrow">Our</span>
          <h3 class="vm-card__title">Vision</h3>
          <div class="vm-card__body"
            data-edit-field="vm_vision"
            data-edit-type="wysiwyg"
            data-edit-label="Vision">
            {!! $vm['vision'] !!}
          </div>
        </div>
        <div class="animate-pulse">
          <svg class="vm-card__icon vm-card__icon--vision" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
        </div>
      </article>
      <article class="vm-card vm-card--mission reveal">
        <div class="vm-card__bg" aria-hidden="true"></div>
        <div class="vm-card__inner">
          <span class="vm-card__eyebrow">Our</span>
          <h3 class="vm-card__title">Mission</h3>
          <div class="vm-card__body"
            data-edit-field="vm_mission"
            data-edit-type="wysiwyg"
            data-edit-label="Mission">
            {!! $vm['mission'] !!}
          </div>
        </div>
        <div class="animate-pulse">
          <svg class="vm-card__icon vm-card__icon--mission" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <circle cx="12" cy="12" r="9" />
            <circle cx="12" cy="12" r="5" />
            <circle cx="12" cy="12" r="1.4" fill="currentColor" />
            <path d="M12 3V1.5M21 12h1.5M18.5 5.5L22 2" />
          </svg>
        </div>
      </article>
    </div>

    @if ($vm['cta']['label'])
    <div style="margin-top:48px" class="w-full items-center justify-center flex">
      <a href="{{ $vm['cta']['url'] }}"
        class="btn"
        data-edit-type="button"
        data-edit-label="Vision/Mission Button"
        data-edit-label-field="vm_cta_label"
        data-edit-url-field="vm_cta_url"
        data-edit-icon-field="vm_cta_icon"
        data-edit-icon-value="{{ $vm['cta']['icon'] }}">
        <span class="btn-text">{{ $vm['cta']['label'] }}</span>
        @include('partials.btn-icon', ['icon' => $vm['cta']['icon']])
      </a>
    </div>
    @endif
  </div>
</section>


{{-- Commitment --}}
<section class="section" style="background:var(--bg-2);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:60px;align-items:center" class="home-intro-grid">
      <div class="reveal reveal-left">
        @if ($commitment['eyebrow'])
        <span class="eyebrow"
          data-edit-field="commitment_eyebrow"
          data-edit-type="text"
          data-edit-label="Commitment Eyebrow">{{ $commitment['eyebrow'] }}</span>
        @endif
        <h2 class="h2 mb-20" style="margin-top:14px">
          <span
            data-edit-field="commitment_headline_lead"
            data-edit-type="text"
            data-edit-label="Commitment Headline Lead">{{ $commitment['headlineLead'] }}</span>
          @if ($commitment['headlineEm'])
          <em
            data-edit-field="commitment_headline_emphasis"
            data-edit-type="text"
            data-edit-label="Commitment Headline Emphasis">{{ $commitment['headlineEm'] }}</em>
          @endif
          @if ($commitment['headlineTrail'])
          <span
            data-edit-field="commitment_headline_trail"
            data-edit-type="text"
            data-edit-label="Commitment Headline Trail">{{ $commitment['headlineTrail'] }}</span>
          @endif
        </h2>
      </div>
      <div class="reveal reveal-right pt-20" style="transition-delay:.12s">
        @if ($commitment['paragraph1'])
        <p class="lead"
          data-edit-field="commitment_paragraph_1"
          data-edit-type="textarea"
          data-edit-label="Commitment Paragraph 1">{{ $commitment['paragraph1'] }}</p>
        @endif
        @if ($commitment['paragraph2'])
        <p class="lead" style="margin-top:18px"
          data-edit-field="commitment_paragraph_2"
          data-edit-type="textarea"
          data-edit-label="Commitment Paragraph 2">{{ $commitment['paragraph2'] }}</p>
        @endif
        @if ($commitment['cta']['label'])
        <div style="margin-top:28px">
          <a href="{{ $commitment['cta']['url'] }}"
            class="btn"
            data-edit-type="button"
            data-edit-label="Commitment Button"
            data-edit-label-field="commitment_cta_label"
            data-edit-url-field="commitment_cta_url"
            data-edit-icon-field="commitment_cta_icon"
            data-edit-icon-value="{{ $commitment['cta']['icon'] }}">
            <span class="btn-text">{{ $commitment['cta']['label'] }}</span>
            @include('partials.btn-icon', ['icon' => $commitment['cta']['icon']])
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
  <style>
    @media (max-width:860px) {
      .home-intro-grid {
        grid-template-columns: 1fr !important;
        gap: 24px !important
      }
    }
  </style>
</section>

{{-- Board of Directors --}}
@if (! empty($boardOfDirectors))
<section class="section" style="padding-top:88px;">
  <div class="container" style="text-align:center">
    <div class="section-head-col"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Leadership</span>
        <h2 class="h2" style="margin-top:14px">
          Board of <em>Directors.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">



      @foreach ($boardOfDirectors as $i => $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3 reveal" style="{{ $i > 0 ? 'transition-delay:.'.(($i+1)*1).'s' : '' }}">
        <div class="media bg-[var(--bg)]/60!">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
            alt="{{ esc_attr($member['name']) }}"
            loading="lazy">
        </div>
        <div class="body flex flex-col relative items-center justify-center">
          <div class="name font-bold text-xl tracking-wide uppercase">{{ $member['name'] }}</div>
          <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
          <div class="tracking-normal font-medium! text-sm mt-1 text-[var(--accent)]">{{ $member['role'] }}</div>
          @if (! empty($member['bio']))
          <p class="bio border-t-0 absolute top-11">{{ $member['bio'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Brokers --}}
@if (! empty($brokers))
<section class="section" style="background:var(--bg-2)">
  <div class="container" style="text-align:center">
    <div class="section-head-col"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Salesfloor</span>
        <h2 class="h2" style="margin-top:14px">
          <em>Brokers.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($brokers as $i => $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/3 lg:w-1/4 reveal" style="{{ $i > 0 ? 'transition-delay:.'.(($i+1)*1).'s' : '' }}">
        <div class="media">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
            alt="{{ esc_attr($member['name']) }}"
            loading="lazy">
        </div>
        <div class="body flex flex-col relative items-center justify-center">
          <div class="font-bold text-lg tracking-wide uppercase">{{ $member['name'] }}</div>
          <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
          <div class="tracking-wide font-medium! text-xs text-[var(--accent)]">{{ $member['role'] }}</div>
          @if (! empty($member['bio']))
          <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Managers (Bohol + Cebu tabbed) --}}
@if (! empty($boholManagers) || ! empty($cebuManagers))
<section class="section managers-section">
  <div class="container" style="text-align:center">
    <div class="section-head-col"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Our Team</span>
        <h2 class="h2" style="margin-top:14px">
          Our <em>Managers.</em>
        </h2>
      </div>
    </div>
    <div class="managers-tabs" role="tablist" aria-label="Managers by region" style="margin-top:28px">
      @if (! empty($boholManagers))
      <button class="managers-tab is-active" role="tab" aria-selected="true" aria-controls="managers-panel-bohol" data-managers-tab="bohol">
        Bohol
      </button>
      @endif
      @if (! empty($cebuManagers))
      <button class="managers-tab" role="tab" aria-selected="false" aria-controls="managers-panel-cebu" data-managers-tab="cebu">
        Cebu
      </button>
      @endif
    </div>
  </div>

  @if (! empty($boholManagers))
  <div class="managers-panel is-active" id="managers-panel-bohol" role="tabpanel" data-managers-panel="bohol" style="margin-top:36px">
    <div class="managers-scroll-wrap">
      <button class="managers-arrow managers-arrow--prev" aria-label="Previous managers">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <path d="M11 14L6 9l5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
      <div class="managers-strip stagger-children" data-managers-strip>
        @foreach ($boholManagers as $i => $member)

        <div class="min-w-50 reveal" style="{{ $i > 0 ? 'transition-delay:.'.(($i+1)*1).'s' : '' }}">
          <div class=" px-2 pt-2 bg-[var(--bg-2)]/80 flex justify-center rounded-3xl">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
              alt="{{ esc_attr($member['name']) }}"
              loading="lazy" class="object-cover h-50">
          </div>
          <div class="body flex flex-col relative items-center justify-center py-3">
            <div class="font-bold text-base tracking-wide uppercase text-center">{{ $member['name'] }}</div>
            <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
            <div class="tracking-wide font-medium! text-xs text-[var(--accent)]">{{ $member['role'] }}</div>
            @if (! empty($member['bio']))
            <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
            @endif
          </div>
        </div>
        @endforeach
      </div>
      <button class="managers-arrow managers-arrow--next" aria-label="Next managers">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>
  </div>
  @endif

  @if (! empty($cebuManagers))
  <div class="managers-panel" id="managers-panel-cebu" role="tabpanel" data-managers-panel="cebu" style="margin-top:36px">
    <div class="managers-scroll-wrap">
      <button class="managers-arrow managers-arrow--prev" aria-label="Previous managers">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <path d="M11 14L6 9l5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
      <div class="managers-strip stagger-children" data-managers-strip>
        @foreach ($cebuManagers as $i => $member)

        <div class="min-w-50 reveal" style="{{ $i > 0 ? 'transition-delay:.'.(($i+1)*1).'s' : '' }}">
          <div class="px-2 pt-2 bg-[var(--bg-2)]/80 flex justify-center rounded-3xl">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
              alt="{{ esc_attr($member['name']) }}"
              loading="lazy" class="object-cover h-50">
          </div>
          <div class="body flex flex-col relative items-center justify-center py-3">
            <div class="font-bold text-base tracking-wide uppercase text-center">{{ $member['name'] }}</div>
            <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
            <div class="tracking-wide font-medium! text-xs text-[var(--accent)]">{{ $member['role'] }}</div>
            @if (! empty($member['bio']))
            <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
            @endif
          </div>
        </div>
        @endforeach
      </div>
      <button class="managers-arrow managers-arrow--next" aria-label="Next managers">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>
  </div>
  @endif
  <div style="padding:36px 0;text-align:center">
    <a href="{{ home_url('/team') }}" class="btn py-2!">
      See all in teams
      <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </a>
  </div>
</section>
@endif

{{-- Staff (Bohol + Cebu tabbed) --}}
@if (! empty($boholStaffs) || ! empty($cebuStaffs))
<section class="section bg-[var(--bg-2)]/30">
  <div class="container" style="text-align:center">
    <div class="section-head-col"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Our Team</span>
        <h2 class="h2" style="margin-top:14px">
          Our <em>Staff.</em>
        </h2>
      </div>
    </div>
    <div class="managers-tabs" role="tablist" aria-label="Staff by region" style="margin-top:28px">
      @if (! empty($boholStaffs))
      <button class="managers-tab is-active" role="tab" aria-selected="true" aria-controls="staff-panel-bohol" data-managers-tab="staff-bohol">
        Bohol
      </button>
      @endif
      @if (! empty($cebuStaffs))
      <button class="managers-tab" role="tab" aria-selected="false" aria-controls="staff-panel-cebu" data-managers-tab="staff-cebu">
        Cebu
      </button>
      @endif
    </div>
  </div>


  @if (! empty($boholStaffs))
  <div class="managers-panel is-active" id="staff-panel-bohol" role="tabpanel" data-managers-panel="staff-bohol" style="margin-top:36px">
    <div class="managers-scroll-wrap">
      <button class="managers-arrow managers-arrow--prev" aria-label="Previous staff">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <path d="M11 14L6 9l5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
      <div class="managers-strip stagger-children" data-managers-strip>
        @foreach ($boholStaffs as $i => $member)

        <div class="min-w-50 reveal">
          <div class="px-2 pt-2 bg-[var(--bg-2)]/80 flex justify-center rounded-3xl">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
              alt="{{ esc_attr($member['name']) }}"
              loading="lazy" class="object-cover h-50">
          </div>
          <div class="body flex flex-col relative items-center justify-center py-3">
            <div class="font-bold text-base tracking-wide uppercase text-center">{{ $member['name'] }}</div>
            <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
            <div class="tracking-wide font-medium! text-xs text-[var(--accent)]">{{ $member['role'] }}</div>
            @if (! empty($member['bio']))
            <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
            @endif
          </div>
        </div>
        @endforeach
      </div>
      <button class="managers-arrow managers-arrow--next" aria-label="Next staff">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>
  </div>
  @endif

  @if (! empty($cebuStaffs))
  <div class="managers-panel" id="staff-panel-cebu" role="tabpanel" data-managers-panel="staff-cebu" style="margin-top:36px">
    <div class="managers-scroll-wrap">
      <button class="managers-arrow managers-arrow--prev" aria-label="Previous staff">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <path d="M11 14L6 9l5-5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
      <div class="managers-strip stagger-children" data-managers-strip>
        @foreach ($cebuStaffs as $i => $member)
        <div class="min-w-50 reveal">
          <div class="px-2 pt-2 bg-[var(--bg-2)]/80 flex justify-center rounded-3xl">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
              alt="{{ esc_attr($member['name']) }}"
              loading="lazy" class="object-cover h-50">
          </div>
          <div class="body flex flex-col relative items-center justify-center py-3">
            <div class="font-bold text-base tracking-wide uppercase text-center">{{ $member['name'] }}</div>
            <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
            <div class="tracking-wide font-medium! text-xs text-[var(--accent)]">{{ $member['role'] }}</div>
            @if (! empty($member['bio']))
            <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
            @endif
          </div>
        </div>
        @endforeach
      </div>
      <button class="managers-arrow managers-arrow--next" aria-label="Next staff">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
          <path d="M7 4l5 5-5 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>
  </div>
  @endif

  <div style="padding:36px 0;text-align:center">
    <a href="{{ home_url('/team') }}" class="btn py-2!">
      See all in teams
      <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </a>
  </div>

</section>
@endif


{{-- Featured properties --}}
<section class="section pt-20">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal items-center justify-center flex flex-col">
        <span class="eyebrow">Featured listings</span>
        <h2 class="h2" style="margin-top:14px">
          A small selection from <em>this season's</em> portfolio.
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        <p class="lead text-center">
          From Panglao beachfront villas to Cebu IT Park penthouses handpicked
          listings that have passed our title, build, and pricing checks.
        </p>
        <div class="mx-auto mt-4 text-center">
          <a href="{{ home_url('/properties') }}" class="btn">
            View all listings
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
        </div>
      </div>
    </div>

    <div class="stagger-children prop-grid">
      @foreach ($properties as $p)
      <x-property-card :property="$p" />
      @endforeach
    </div>
  </div>
</section>

{{-- Stories --}}
<section class="section" style="border-top:1px solid var(--line)">
  <div class="container">
    <div class="reveal" style="text-align:center">
      <span class="eyebrow-center">Stay connected</span>
      <h2 class="h2" style="margin-top:14px">Client <em>stories.</em></h2>
    </div>
    @if (empty($featuredContent['stories']))
    <p style="color:var(--ink-2);font-size:14px;margin-top:36px">No stories yet.</p>
    @else
    <div class="featured-grid stagger-children" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:36px">
      @foreach ($featuredContent['stories'] as $story)
      <div
        class="reveal story-card"
        style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column">
        @if ($story['image'])
        <div style="aspect-ratio:4/3;overflow:hidden;background:#000;flex-shrink:0">
          <img
            src="{{ $story['image'] }}"
            alt="{{ esc_attr($story['client']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block"
            loading="lazy">
        </div>
        @endif
        <div style="padding:22px;flex:1;display:flex;flex-direction:column;gap:8px">
          <div style="font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--accent)">
            {{ $story['client'] }}
          </div>
          @if ($story['quote'])
          <p style="font-size:15px;line-height:1.6;color:var(--ink);margin:0;display:-webkit-box;-webkit-line-clamp:3;line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
            &ldquo;{{ $story['quote'] }}&rdquo;
          </p>
          @endif
          @if ($story['location'] || $story['year'])
          <div style="margin-top:auto;padding-top:8px;font-size:12px;color:var(--ink-2)">
            {{ $story['location'] }}
            @if ($story['location'] && $story['year'])
            &nbsp;·&nbsp;
            @endif
            {{ $story['year'] }}
          </div>
          @endif
        </div>
      </div>
      @endforeach
    </div>
    @endif
    <div style="margin-top:28px;text-align:right">
      <a href="{{ $featuredContent['storiesUrl'] }}" class="btn btn-sm">
        View all stories
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </div>
  </div>
</section>

{{-- Events --}}
<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="reveal" style="text-align:center">
      <span class="eyebrow-center">Events</span>
      <h2 class="h2" style="margin-top:14px">Upcoming <em>events.</em></h2>
    </div>
    @if (empty($featuredContent['events']))
    <p style="color:var(--ink-2);font-size:14px;margin-top:36px">No events yet.</p>
    @else
    <div class="featured-grid stagger-children" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:36px">
      @foreach ($featuredContent['events'] as $ev)
      <a
        href="{{ $ev['permalink'] }}"
        class="ev-card reveal"
        style="position:relative;border-radius:14px;overflow:hidden;aspect-ratio:4/3;border:1px solid var(--line);display:block;text-decoration:none">
        @if ($ev['cover'])
        <img
          src="{{ $ev['cover'] }}"
          alt="{{ esc_attr($ev['title']) }}"
          style="width:100%;height:100%;object-fit:cover;filter:brightness(.65);transition:transform .4s ease"
          loading="lazy">
        @endif
        <div style="position:absolute;inset:0;padding:22px;display:flex;flex-direction:column;justify-content:flex-end;background:linear-gradient(180deg,transparent 30%,rgba(6,13,31,.92))">
          @if ($ev['dateLabel'] || $ev['location'])
          <span class="eyebrow">
            {{ $ev['dateLabel'] }}
            @if ($ev['dateLabel'] && $ev['location'])
            &nbsp;·&nbsp;
            @endif
            {{ $ev['location'] }}
          </span>
          @endif
          <h3 class="h3" style="margin-top:8px;font-size:clamp(16px,1.6vw,20px)">{{ $ev['title'] }}</h3>
          <div style="margin-top:10px;display:inline-flex;align-items:center;gap:6px;font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--accent)">
            View event
            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
        </div>
      </a>
      @endforeach
    </div>
    @endif
    <div style="margin-top:28px;text-align:right">
      <a href="{{ $featuredContent['eventsUrl'] }}" class="btn btn-sm">
        View all events
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </div>
  </div>
</section>

{{-- Blog --}}
<section class="section" style="border-top:1px solid var(--line)">
  <div class="container">
    <div class="reveal" style="text-align:center">
      <span class="eyebrow-center">Blog</span>
      <h2 class="h2" style="margin-top:14px">From <em>the blog.</em></h2>
    </div>
    @if (empty($featuredContent['blogPosts']))
    <p style="color:var(--ink-2);font-size:14px;margin-top:36px">No posts yet.</p>
    @else
    <div class="featured-grid stagger-children" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:36px">
      @foreach ($featuredContent['blogPosts'] as $post)
      <a
        href="{{ $post['permalink'] }}"
        class="reveal blog-card"
        style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;text-decoration:none">
        @if ($post['thumbnail'])
        <div style="aspect-ratio:16/9;overflow:hidden;background:#000;flex-shrink:0">
          <img
            src="{{ $post['thumbnail'] }}"
            alt="{{ esc_attr($post['title']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
            loading="lazy">
        </div>
        @endif
        <div style="padding:22px;flex:1;display:flex;flex-direction:column;gap:8px">
          <div class="tag-row" style="flex-wrap:wrap;gap:6px">
            <span style="color:var(--ink-2);font-size:12px">{{ $post['dateFormatted'] }}</span>
            @foreach ($post['categories'] as $cat)
            <span class="sep">·</span>
            <span style="color:var(--accent);font-size:12px">{{ $cat['name'] }}</span>
            @endforeach
          </div>
          <h3 class="h3" style="margin:0;font-size:clamp(16px,1.6vw,20px)">{{ $post['title'] }}</h3>
          @if ($post['excerpt'])
          <p style="color:var(--ink-2);font-size:13px;line-height:1.6;margin:0;display:-webkit-box;-webkit-line-clamp:3;line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
            {{ $post['excerpt'] }}
          </p>
          @endif
          <div style="margin-top:auto;padding-top:8px;display:inline-flex;align-items:center;gap:6px;font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--accent)">
            Read more
            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
        </div>
      </a>
      @endforeach
    </div>
    @endif
    <div style="margin-top:28px;text-align:right">
      <a href="{{ $featuredContent['blogUrl'] }}" class="btn btn-sm">
        View all posts
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </div>
  </div>

  <style>
    @media (max-width: 860px) {
      .featured-grid {
        grid-template-columns: repeat(2, 1fr) !important
      }
    }

    @media (max-width: 540px) {
      .featured-grid {
        grid-template-columns: 1fr !important
      }
    }
  </style>
</section>

{{-- Services --}}
<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal items-center justify-center flex flex-col">
        @if (! empty($servicesData['eyebrow']))
        <span class="eyebrow"
          data-edit-field="services_eyebrow"
          data-edit-type="text"
          data-edit-label="Services Eyebrow"
          data-edit-post="option">{{ $servicesData['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="services_headline_lead"
            data-edit-type="text"
            data-edit-label="Services Headline Lead"
            data-edit-post="option">{{ $servicesData['headlineLead'] ?? 'Six services.' }}</span>
          @if (! empty($servicesData['headlineEm']))
          <em
            data-edit-field="services_headline_emphasis"
            data-edit-type="text"
            data-edit-label="Services Headline Emphasis"
            data-edit-post="option">{{ $servicesData['headlineEm'] }}</em>
          @endif
        </h2>
      </div>
      @if (! empty($servicesData['intro']))
      <p class="lead reveal text-center" style="transition-delay:.1s"
        data-edit-field="services_intro"
        data-edit-type="textarea"
        data-edit-label="Services Intro"
        data-edit-post="option">{{ $servicesData['intro'] }}</p>
      @endif
    </div>
    @php
    $serviceIcons = [
    '01' => '
    <path d="M3 12 12 4l9 8" />
    <path d="M5 10v10h14V10" />
    <path d="M10 20v-6h4v6" />',
    '02' => '
    <path d="m11 17 2 2a1 1 0 1 0 3-3" />
    <path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4" />
    <path d="m21 3 1 11h-2" />
    <path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3" />
    <path d="M3 4h8" />',
    '03' => '
    <path d="m3 11 18-5v12L3 14v-3z" />
    <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6" />',
    '04' => '
    <path d="M3 3v16a2 2 0 0 0 2 2h16" />
    <path d="m7 14 4-4 4 4 5-5" />',
    '05' => '
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
    <path d="M14 2v6h6" />
    <path d="M9 13h6" />
    <path d="M9 17h6" />',
    '06' => '
    <path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
    <circle cx="12" cy="10" r="3" />',
    ];
    @endphp
    <div class="stagger-children feature-grid"
      data-edit-admin="admin.php?page=acf-options-site-settings"
      title="Click to manage service items in WP Admin">
      @foreach ($services as $s)
      <div class="feature">
        <svg class="bg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          {!! $serviceIcons[$s['number'] ?? ''] ?? '' !!}
        </svg>
        <div class="feature-body">
          <div class="num">{{ $s['number'] ?? '' }}</div>
          <div class="ttl">{{ $s['title'] }}</div>
          <p class="desc">{{ $s['description'] ?? '' }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Locations --}}
<section class="section">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        @if ($locations['eyebrow'])
        <span class="eyebrow"
          data-edit-field="locations_eyebrow"
          data-edit-type="text"
          data-edit-label="Locations Eyebrow">{{ $locations['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="locations_headline_lead"
            data-edit-type="text"
            data-edit-label="Locations Headline Lead">{{ $locations['headlineLead'] }}</span>
          @if ($locations['headlineEm'])
          <em
            data-edit-field="locations_headline_emphasis"
            data-edit-type="text"
            data-edit-label="Locations Headline Emphasis">{{ $locations['headlineEm'] }}</em>
          @endif
        </h2>
      </div>
      @if ($locations['intro'])
      <p class="lead reveal text-center" style="transition-delay:.1s"
        data-edit-field="locations_intro"
        data-edit-type="textarea"
        data-edit-label="Locations Intro">{{ $locations['intro'] }}</p>
      @endif
    </div>
    @if (! empty($locations['items']))
    <div style="display:grid;grid-template-columns:repeat({{ min(count($locations['items']), 2) }}, 1fr);gap:28px" class="loc-grid">
      @foreach ($locations['items'] as $i => $loc)
      <div class="reveal {{ $i % 2 === 0 ? 'reveal-left' : 'reveal-right' }}" style="position:relative;border-radius:14px;overflow:hidden;aspect-ratio:4/3;border:1px solid var(--line){{ $i > 0 ? ';transition-delay:.1s' : '' }}">

        @if ($i === 0)
        {{-- Card 1: Google Map (same embed as contact page) --}}

        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d867.2313810006589!2d123.85299659679596!3d9.650150520001345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33aa4c4d46953b65%3A0x2505987c8ce7172c!2sJaz-M%20Bldg.!5e0!3m2!1sen!2sph!4v1779539068766!5m2!1sen!2sph" width="100%"
          height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

        @elseif ($i === 1)
        {{-- Card 2: Auto-cycling office gallery (no thumbs) --}}
        @if (! empty($officePhotos))
        @foreach ($officePhotos as $pi => $photo)
        <img
          class="loc-gallery-img"
          src="{{ $photo['url'] }}"
          alt="{{ $photo['alt'] }}"
          style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;filter:brightness(.7);opacity:{{ $pi === 0 ? '1' : '0' }};transition:opacity .8s ease"
          loading="{{ $pi === 0 ? 'eager' : 'lazy' }}">
        @endforeach
        @elseif (! empty($loc['image']['url']))
        <img
          src="{{ $loc['image']['url'] }}"
          alt="{{ $loc['image']['alt'] ?? '' }}"
          style="width:100%;height:100%;object-fit:cover;filter:brightness(.7)">
        @endif
        <div style="position:absolute;inset:0;padding:32px;display:flex;flex-direction:column;justify-content:flex-end;background:linear-gradient(180deg,transparent 40%,rgba(6,13,31,.9))">
          @if ($loc['eyebrow'])
          <span class="eyebrow">{{ $loc['eyebrow'] }}</span>
          @endif
          <h3 class="h2" style="font-size:clamp(22px,2.4vw,36px);margin-top:10px">{{ $loc['title'] }}</h3>
          @if ($loc['description'])
          <p class="muted" style="margin-top:8px;max-width:36ch">{{ $loc['description'] }}</p>
          @endif
        </div>

        @else
        {{-- Remaining cards: standard image + overlay --}}
        @if (! empty($loc['image']['url']))
        <img
          src="{{ $loc['image']['url'] }}"
          alt="{{ $loc['image']['alt'] ?? '' }}"
          style="width:100%;height:100%;object-fit:cover;filter:brightness(.7)">
        @endif
        <div style="position:absolute;inset:0;padding:32px;display:flex;flex-direction:column;justify-content:flex-end;background:linear-gradient(180deg,transparent 40%,rgba(6,13,31,.9))">
          @if ($loc['eyebrow'])
          <span class="eyebrow">{{ $loc['eyebrow'] }}</span>
          @endif
          <h3 class="h2" style="font-size:clamp(22px,2.4vw,36px);margin-top:10px">{{ $loc['title'] }}</h3>
          @if ($loc['description'])
          <p class="muted" style="margin-top:8px;max-width:36ch">{{ $loc['description'] }}</p>
          @endif
        </div>
        @endif

      </div>
      @endforeach
    </div>
    @endif
  </div>
  <style>
    @media (max-width:800px) {
      .loc-grid {
        grid-template-columns: 1fr !important
      }
    }
  </style>
  <script>
    (function() {
      var imgs = document.querySelectorAll('.loc-gallery-img');
      if (imgs.length < 2) return;
      var cur = 0;
      setInterval(function() {
        imgs[cur].style.opacity = '0';
        cur = (cur + 1) % imgs.length;
        imgs[cur].style.opacity = '1';
      }, 3000);
    })();
  </script>
</section>

{{-- Accredited Developers --}}
@php
$boholDevs = $accreditedDevelopers['bohol'] ?? [];
$cebuDevs = $accreditedDevelopers['cebu'] ?? [];
@endphp
@if (! empty($boholDevs) || ! empty($cebuDevs))
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow"
          data-edit-field="developers_eyebrow"
          data-edit-type="text"
          data-edit-label="Developers Eyebrow">{{ $developersSection['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="developers_headline_lead"
            data-edit-type="text"
            data-edit-label="Developers Headline Lead">{{ $developersSection['headlineLead'] }}</span>
          <em
            data-edit-field="developers_headline_emphasis"
            data-edit-type="text"
            data-edit-label="Developers Headline Emphasis">{{ $developersSection['headlineEm'] }}</em>
        </h2>
      </div>
      <p class="lead reveal text-center" style="transition-delay:.1s"
        data-edit-field="developers_intro"
        data-edit-type="textarea"
        data-edit-label="Developers Intro">{{ $developersSection['intro'] }}</p>
    </div>

    <div class="managers-tabs" role="tablist" aria-label="Filter developers by region" style="margin-top:28px" data-dev-filter-bar>
      <button class="managers-tab is-active" role="tab" aria-selected="true" data-dev-filter="all">
        All
      </button>
      @if (! empty($boholDevs))
      <button class="managers-tab" role="tab" aria-selected="false" data-dev-filter="bohol">
        Bohol
      </button>
      @endif
      @if (! empty($cebuDevs))
      <button class="managers-tab" role="tab" aria-selected="false" data-dev-filter="cebu">
        Cebu
      </button>
      @endif
    </div>
  </div>

  <div class="managers-panel is-active" id="dev-panel-all" role="tabpanel" style="margin-top:36px">
    <div class="container">
      <div class="stagger-children dev-logo-wall">
        @foreach ($boholDevs as $d)
        <div class="dev-logo-item" data-dev-region="bohol"
          @if (! empty($d['postId']))
          data-edit-admin="post.php?post={{ $d['postId'] }}&action=edit"
          title="Click to edit {{ esc_attr($d['name']) }} in WP Admin"
          @endif>
          @if (! empty($d['logo']))
          <img src="{{ $d['logo'] }}" alt="{{ esc_attr($d['name']) }}" loading="lazy">
          @else
          <span class="dev-logo-text">{{ $d['name'] }}</span>
          @endif
        </div>
        @endforeach
        @foreach ($cebuDevs as $d)
        <div class="dev-logo-item" data-dev-region="cebu"
          @if (! empty($d['postId']))
          data-edit-admin="post.php?post={{ $d['postId'] }}&action=edit"
          title="Click to edit {{ esc_attr($d['name']) }} in WP Admin"
          @endif>
          @if (! empty($d['logo']))
          <img src="{{ $d['logo'] }}" alt="{{ esc_attr($d['name']) }}" loading="lazy">
          @else
          <span class="dev-logo-text">{{ $d['name'] }}</span>
          @endif
        </div>
        @endforeach
      </div>
    </div>
  </div>

  <div style="margin-top:36px;text-align:center">
    <a href="{{ home_url('/developers') }}" class="btn">
      See all developers
      <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
      </svg>
    </a>
  </div>
</section>
@endif

{{-- Testimonials slider --}}
<section class="section" style="padding-top:88px">
  <div class="container">
    <div class="reveal flex flex-col items-center" style="margin-bottom:52px">
      <span class="eyebrow-center"
        data-edit-field="testimonials_eyebrow"
        data-edit-type="text"
        data-edit-label="Testimonials Eyebrow">{{ $testimonialsSection['eyebrow'] }}</span>
      <h2 class="h2" style="margin-top:14px">
        <span
          data-edit-field="testimonials_headline_lead"
          data-edit-type="text"
          data-edit-label="Testimonials Headline Lead">{{ $testimonialsSection['headlineLead'] }}</span>
        <em
          data-edit-field="testimonials_headline_emphasis"
          data-edit-type="text"
          data-edit-label="Testimonials Headline Emphasis">{{ $testimonialsSection['headlineEm'] }}</em>
      </h2>
    </div>

    <div class="testi-slider" id="testiSlider">
      <button class="testi-nav testi-nav--prev" id="testiPrev" aria-label="Previous testimonial">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M11 4L6 9l5 5" />
        </svg>
      </button>

      <div class="testi-track" id="testiTrack" aria-live="polite" aria-atomic="true">
        @foreach ($testimonialItems as $i => $t)
        <div
          class="testi-slide"
          role="group"
          aria-roledescription="slide"
          aria-label="Testimonial {{ $i + 1 }} of {{ count($testimonialItems) }}"
          {!! $i !==0 ? 'aria-hidden="true"' : '' !!}>
          <div class="testi-quote-icon" aria-hidden="true">&ldquo;</div>
          <div class="testi-stars" aria-label="5 out of 5 stars" role="img">★ ★ ★ ★ ★</div>
          <blockquote class="testi-quote"
            @if (! empty($t['postId']))
            data-edit-field="testimonial_quote"
            data-edit-type="textarea"
            data-edit-label="Testimonial Quote"
            data-edit-post="{{ $t['postId'] }}"
            @endif>{{ $t['quote'] }}</blockquote>
          <div class="testi-who">
            <img
              src="{{ $t['avatar'] ?? ('https://i.pravatar.cc/96?u=' . urlencode($t['name'])) }}"
              alt="{{ esc_attr($t['name']) }}"
              width="52"
              height="52"
              class="testi-avatar"
              loading="lazy">
            <div>
              <div class="testi-name"
                @if (! empty($t['postId']))
                data-edit-field="testimonial_name"
                data-edit-type="text"
                data-edit-label="Testimonial Name"
                data-edit-post="{{ $t['postId'] }}"
                @endif>{{ $t['name'] }}</div>
              <div class="testi-role"
                @if (! empty($t['postId']))
                data-edit-field="testimonial_role"
                data-edit-type="text"
                data-edit-label="Testimonial Role"
                data-edit-post="{{ $t['postId'] }}"
                @endif>{{ $t['role'] }}</div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <button class="testi-nav testi-nav--next" id="testiNext" aria-label="Next testimonial">
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M7 4l5 5-5 5" />
        </svg>
      </button>
    </div>

    <div class="testi-dots" id="testiDots" role="tablist" aria-label="Testimonials navigation">
      @foreach ($testimonialItems as $i => $t)
      <button
        class="testi-dot {{ $i === 0 ? 'active' : '' }}"
        role="tab"
        aria-label="Go to testimonial {{ $i + 1 }}"
        aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
        data-testi-dot="{{ $i }}"></button>
      @endforeach
    </div>

    <div style="margin-top:40px;text-align:center">
      <a href="{{ home_url('/testimonials') }}" class="btn">
        More testimonials
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </div>
  </div>
</section>

{{-- CTA Banner --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
  <div class="container" style="position:relative;text-align:center">
    <x-orbit-deco style="right:auto;left:-180px;top:-50px;width:380px;height:380px;opacity:.25" />
    <p class="banner-quote">
      <span
        data-edit-field="cta_quote_lead"
        data-edit-type="text"
        data-edit-label="CTA Quote Lead">{{ $ctaBanner['quoteLead'] }}</span>
      @if ($ctaBanner['quoteEm'])
      <em
        data-edit-field="cta_quote_emphasis"
        data-edit-type="text"
        data-edit-label="CTA Quote Emphasis">{{ $ctaBanner['quoteEm'] }}</em>
      @endif
      @if ($ctaBanner['quoteTrail'])
      <br>
      <span
        data-edit-field="cta_quote_trail"
        data-edit-type="text"
        data-edit-label="CTA Quote Trail">{{ $ctaBanner['quoteTrail'] }}</span>
      @endif
    </p>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:36px;flex-wrap:wrap">
      @if ($ctaBanner['primary']['label'])
      <a href="{{ $ctaBanner['primary']['url'] }}"
        class="btn btn-primary"
        data-edit-type="button"
        data-edit-label="CTA Banner Primary Button"
        data-edit-label-field="cta_primary_label"
        data-edit-url-field="cta_primary_url"
        data-edit-icon-field="cta_primary_icon"
        data-edit-icon-value="{{ $ctaBanner['primary']['icon'] }}">
        <span class="btn-text">{{ $ctaBanner['primary']['label'] }}</span>
        @include('partials.btn-icon', ['icon' => $ctaBanner['primary']['icon']])
      </a>
      @endif
      @if ($ctaBanner['secondary']['label'])
      <a href="{{ $ctaBanner['secondary']['url'] }}"
        class="btn"
        data-edit-type="button"
        data-edit-label="CTA Banner Secondary Button"
        data-edit-label-field="cta_secondary_label"
        data-edit-url-field="cta_secondary_url"
        data-edit-icon-field="cta_secondary_icon"
        data-edit-icon-value="{{ $ctaBanner['secondary']['icon'] }}">
        <span class="btn-text">{{ $ctaBanner['secondary']['label'] }}</span>
        @include('partials.btn-icon', ['icon' => $ctaBanner['secondary']['icon']])
      </a>
      @endif
    </div>
  </div>
</section>
@endsection