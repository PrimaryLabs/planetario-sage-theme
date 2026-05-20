@extends('layouts.app')

@section('content')
@php
use App\Data\StaticData;
$properties = $featuredProperties ?? array_slice(StaticData::properties(), 0, 6);
$servicesData = $site['services'] ?? null;
$services = $servicesData['items'] ?? array_map(fn($s) => ['number' => $s['num'], 'title' => $s['title'], 'description' => $s['desc']], StaticData::services());
$testimonialItems = $testimonialsHighlights ?? array_slice(StaticData::testimonials(), 0, 6);
$accreditedDevelopers = $accreditedDevelopers ?? [];
@endphp

{{-- Hero --}}
<section class="hero">
  <div class="hero-bg">
    <img src="{{ $hero['image']['url'] }}" alt="{{ $hero['image']['alt'] }}" aria-hidden="true">
  </div>
  <div class="hero-overlay"></div>
  <x-orbit-deco />

  <div class="container hero-inner">
    @if ($hero['eyebrow'])
    <span class="eyebrow">{{ $hero['eyebrow'] }}</span>
    @endif
    <h1 class="display hero-headline">
      {{ $hero['headlineLead'] }} @if ($hero['headlineEm'])<em>{{ $hero['headlineEm'] }}</em>@endif
    </h1>
    @if ($hero['sub'])
    <p class="hero-sub">{!! nl2br(e($hero['sub'])) !!}</p>
    @endif
    <div class="hero-actions">
      @if ($hero['primaryCta']['label'])
      <a href="{{ $hero['primaryCta']['url'] }}" class="btn btn-primary">
        {{ $hero['primaryCta']['label'] }}
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      @endif
      @if ($hero['secondaryCta']['label'])
      <a href="{{ $hero['secondaryCta']['url'] }}" class="btn">
        {{ $hero['secondaryCta']['label'] }}
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      @endif
    </div>

    @if (! empty($hero['stats']))
    <div class="hero-meta">
      @foreach ($hero['stats'] as $stat)
      <div class="item">
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
        <span class="eyebrow">{{ $vm['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          {{ $vm['headlineLead'] }}
          @if ($vm['headlineEm']) <em>{{ $vm['headlineEm'] }}</em>@endif
          @if ($vm['headlineTrail']) {{ $vm['headlineTrail'] }}@endif
        </h2>
      </div>
      @if ($vm['intro'])
      <p class="lead reveal text-center" style="transition-delay:.1s">{{ $vm['intro'] }}</p>
      @endif
    </div>

    <div class="vm-stage">
      <article class="vm-card vm-card--vision reveal">
        <div class="vm-card__bg" aria-hidden="true"></div>
        <div class="vm-card__inner">
          <span class="vm-card__eyebrow">Our</span>
          <h3 class="vm-card__title">Vision</h3>
          <div class="vm-card__body">
            {!! $vm['vision'] !!}
          </div>
        </div>
        <svg class="vm-card__icon vm-card__icon--vision" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
          <circle cx="12" cy="12" r="3" />
        </svg>
      </article>
      <article class="vm-card vm-card--mission reveal">
        <div class="vm-card__bg" aria-hidden="true"></div>
        <div class="vm-card__inner">
          <span class="vm-card__eyebrow">Our</span>
          <h3 class="vm-card__title">Mission</h3>
          <div class="vm-card__body">
            {!! $vm['mission'] !!}
          </div>
        </div>
        <svg class="vm-card__icon vm-card__icon--mission" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="9" />
          <circle cx="12" cy="12" r="5" />
          <circle cx="12" cy="12" r="1.4" fill="currentColor" />
          <path d="M12 3V1.5M21 12h1.5M18.5 5.5L22 2" />
        </svg>
      </article>
    </div>

    @if ($vm['cta']['label'])
    <div style="margin-top:48px" class="w-full items-center justify-center flex">
      <a href="{{ $vm['cta']['url'] }}" class="btn">
        {{ $vm['cta']['label'] }}
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
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
        <span class="eyebrow">{{ $commitment['eyebrow'] }}</span>
        @endif
        <h2 class="h2 mb-20" style="margin-top:14px">
          {{ $commitment['headlineLead'] }}
          @if ($commitment['headlineEm'])<em>{{ $commitment['headlineEm'] }}</em>@endif
          @if ($commitment['headlineTrail']) {{ $commitment['headlineTrail'] }}@endif
        </h2>
      </div>
      <div class="reveal reveal-right pt-20" style="transition-delay:.12s">
        @if ($commitment['paragraph1'])
        <p class="lead">{{ $commitment['paragraph1'] }}</p>
        @endif
        @if ($commitment['paragraph2'])
        <p class="lead" style="margin-top:18px">{{ $commitment['paragraph2'] }}</p>
        @endif
        @if ($commitment['cta']['label'])
        <div style="margin-top:28px">
          <a href="{{ $commitment['cta']['url'] }}" class="btn">
            {{ $commitment['cta']['label'] }}
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
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
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Leadership</span>
        <h2 class="h2" style="margin-top:14px">
          Board of <em>Directors.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($boardOfDirectors as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3">
        <div class="media">
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
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Salesfloor</span>
        <h2 class="h2" style="margin-top:14px">
          <em>Brokers.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($brokers as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/3 lg:w-1/4">
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
    <div class="section-head-col">
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
      <div class="managers-strip" data-managers-strip>
        @foreach ($boholManagers as $member)
        <div class="min-w-50">
          <div class=" px-2 pt-2 bg-[var(--bg-2)]/80 flex justify-center rounded-3xl">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
              alt="{{ esc_attr($member['name']) }}"
              loading="lazy" class="object-cover h-50">
          </div>
          <div class="body flex flex-col relative items-center justify-center py-3">
            <div class="font-bold text-base tracking-wide uppercase">{{ $member['name'] }}</div>
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
      <div class="managers-strip" data-managers-strip>
        @foreach ($cebuManagers as $member)
        <div class="min-w-50">
          <div class="px-2 pt-2 bg-[var(--bg-2)]/80 flex justify-center rounded-3xl">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
              alt="{{ esc_attr($member['name']) }}"
              loading="lazy" class="object-cover h-50">
          </div>
          <div class="body flex flex-col relative items-center justify-center py-3">
            <div class="font-bold text-base tracking-wide uppercase">{{ $member['name'] }}</div>
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
    <div class="section-head-col">
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
      <div class="managers-strip" data-managers-strip>
        @foreach ($boholStaffs as $member)
        <div class="min-w-50">
          <div class="px-2 pt-2 bg-[var(--bg-2)]/80 flex justify-center rounded-3xl">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
              alt="{{ esc_attr($member['name']) }}"
              loading="lazy" class="object-cover h-50">
          </div>
          <div class="body flex flex-col relative items-center justify-center py-3">
            <div class="font-bold text-base tracking-wide uppercase">{{ $member['name'] }}</div>
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
      <div class="managers-strip" data-managers-strip>
        @foreach ($cebuStaffs as $member)
        <div class="min-w-50">
          <div class="px-2 pt-2 bg-[var(--bg-2)]/80 flex justify-center rounded-3xl">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
              alt="{{ esc_attr($member['name']) }}"
              loading="lazy" class="object-cover h-50">
          </div>
          <div class="body flex flex-col relative items-center justify-center py-3">
            <div class="font-bold text-base tracking-wide uppercase">{{ $member['name'] }}</div>
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

{{-- Services --}}
<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal items-center justify-center flex flex-col">
        @if (! empty($servicesData['eyebrow']))<span class="eyebrow">{{ $servicesData['eyebrow'] }}</span>@endif
        <h2 class="h2" style="margin-top:14px">
          {{ $servicesData['headlineLead'] ?? 'Six services.' }}
          @if (! empty($servicesData['headlineEm'])) <em>{{ $servicesData['headlineEm'] }}</em>@endif
        </h2>
      </div>
      @if (! empty($servicesData['intro']))
      <p class="lead reveal text-center" style="transition-delay:.1s">{{ $servicesData['intro'] }}</p>
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
    <div class="stagger-children feature-grid">
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
        <span class="eyebrow">{{ $locations['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          {{ $locations['headlineLead'] }}
          @if ($locations['headlineEm']) <em>{{ $locations['headlineEm'] }}</em>@endif
        </h2>
      </div>
      @if ($locations['intro'])
      <p class="lead reveal text-center" style="transition-delay:.1s">{{ $locations['intro'] }}</p>
      @endif
    </div>
    @if (! empty($locations['items']))
    <div style="display:grid;grid-template-columns:repeat({{ min(count($locations['items']), 2) }}, 1fr);gap:28px" class="loc-grid">
      @foreach ($locations['items'] as $i => $loc)
      <div class="reveal {{ $i % 2 === 0 ? 'reveal-left' : 'reveal-right' }}" style="position:relative;border-radius:14px;overflow:hidden;aspect-ratio:4/3;border:1px solid var(--line){{ $i > 0 ? ';transition-delay:.1s' : '' }}">
        @if ($loc['image'] && $loc['image']['url'])
        <img src="{{ $loc['image']['url'] }}" alt="{{ $loc['image']['alt'] }}"
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
</section>

{{-- Accredited Developers --}}
@if (! empty($accreditedDevelopers))
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Our partners</span>
        <h2 class="h2" style="margin-top:14px">
          Accredited <em>Developers.</em>
        </h2>
      </div>
      <p class="lead reveal text-center" style="transition-delay:.1s">
        A short list of builders we have walked with for years — vetted on title, build quality, and pricing before any unit reaches our floor.
      </p>
    </div>

    <div class="stagger-children dev-logo-wall" style="margin-top:36px">
      @foreach ($accreditedDevelopers as $d)
      <div class="dev-logo-item">
        @if (! empty($d['logo']))
        <img src="{{ $d['logo'] }}" alt="{{ esc_attr($d['name']) }}" loading="lazy">
        @else
        <span class="dev-logo-text">{{ $d['name'] }}</span>
        @endif
      </div>
      @endforeach
    </div>

    <div style="margin-top:36px;text-align:center">
      <a href="{{ home_url('/developers') }}" class="btn">
        See all developers
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </div>
  </div>
</section>
@endif

{{-- Testimonials slider --}}
<section class="section" style="padding-top:88px">
  <div class="container">
    <div class="reveal flex flex-col items-center" style="margin-bottom:52px">
      <span class="eyebrow-center">Testimonials</span>
      <h2 class="h2" style="margin-top:14px">
        In our <em>clients' words.</em>
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
          <blockquote class="testi-quote">{{ $t['quote'] }}</blockquote>
          <div class="testi-who">
            <img
              src="{{ $t['avatar'] ?? ('https://i.pravatar.cc/96?u=' . urlencode($t['name'])) }}"
              alt="{{ esc_attr($t['name']) }}"
              width="52"
              height="52"
              class="testi-avatar"
              loading="lazy">
            <div>
              <div class="testi-name">{{ $t['name'] }}</div>
              <div class="testi-role">{{ $t['role'] }}</div>
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
      {{ $ctaBanner['quoteLead'] }}
      @if ($ctaBanner['quoteEm']) <em>{{ $ctaBanner['quoteEm'] }}</em>@endif
      @if ($ctaBanner['quoteTrail'])<br>{{ $ctaBanner['quoteTrail'] }}@endif
    </p>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:36px;flex-wrap:wrap">
      @if ($ctaBanner['primary']['label'])
      <a href="{{ $ctaBanner['primary']['url'] }}" class="btn btn-primary">
        {{ $ctaBanner['primary']['label'] }}
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      @endif
      @if ($ctaBanner['secondary']['label'])
      <a href="{{ $ctaBanner['secondary']['url'] }}" class="btn">
        {{ $ctaBanner['secondary']['label'] }}
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      @endif
    </div>
  </div>
</section>
@endsection