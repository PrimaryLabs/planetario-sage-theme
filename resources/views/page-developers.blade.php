@extends('layouts.app')

@section('content')
@php use App\Data\StaticData; $devs = $developers ?? StaticData::developers(); @endphp

{{-- Intro — Tier 2 compact hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
  <div class="container">
    @if ($pageIntro['eyebrow'])
      <span class="eyebrow">{{ $pageIntro['eyebrow'] }}</span>
    @endif

    <h1 class="display" style="margin-top:18px;max-width:20ch">
      {{ $pageIntro['headlineLead'] }}
      @if ($pageIntro['headlineEm'])
        <em>{{ $pageIntro['headlineEm'] }}</em>
      @endif
      @if ($pageIntro['headlineTrail'])
        {{ $pageIntro['headlineTrail'] }}
      @endif
    </h1>

    @if ($pageIntro['lead'])
      <p class="lead" style="margin-top:22px;max-width:64ch">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

<section class="section" style="padding-top:24px">
  <div class="container">
    <div class="stagger-children dev-list">
      @foreach ($devs as $d)
      <div class="dev">
        <div>
          <div class="name">{{ $d['name'] }}</div>
          <div class="meta">
            @if (! empty($d['locations']))
            <span>{{ implode(' · ', $d['locations']) }}</span>
            @endif

            @if (! empty($d['locations']) && ! empty($d['portfolio']))
            <span style="color:var(--line-2)">·</span>
            @endif

            @if (! empty($d['portfolio']))
            <span style="color:var(--ink-3)">{{ $d['portfolio'] }}</span>
            @endif
          </div>

          @if (! empty($d['desc']))
          <p class="desc">{{ $d['desc'] }}</p>
          @endif
        </div>
        <div class="sigil">
          @if (! empty($d['logo']))
          <img src="{{ $d['logo'] }}" alt="{{ $d['name'] }}" style="max-width:60px;max-height:60px;object-fit:contain">
          @else
          {{ $d['sigil'] ?? mb_substr($d['name'], 0, 1) }}
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">How we work with developers</span>
        <h2 class="h2" style="margin-top:14px">A small list. <em>Carefully kept.</em></h2>
      </div>
      <p class="lead reveal" style="transition-delay:.1s">
        Our partnership is a stake on both sides. Here is what working with Planetario
        typically looks like — for the builder, and for the buyer.
      </p>
    </div>

    <div class="stagger-children feature-grid">
      @foreach ([
      ['num' => '01', 'title' => 'Site visit & build audit', 'desc' => 'Before any unit reaches our listings, our senior brokers walk the site and inspect at least three completed projects from that developer.'],
      ['num' => '02', 'title' => 'Pricing benchmark', 'desc' => 'We benchmark every developer offering against comparable open-market inventory so our buyers see fair, current numbers.'],
      ['num' => '03', 'title' => 'Exclusive corridors', 'desc' => 'For several partners we hold first-look or exclusive selling rights in specific towns — Panglao, Anda, Carmen, and parts of Mactan.'],
      ['num' => '04', 'title' => 'Co-launch marketing', 'desc' => 'We co-fund the launch campaign, photography, and qualified-buyer events for selected developer phases.'],
      ['num' => '05', 'title' => 'Buyer protection', 'desc' => 'We never market a phase whose pre-selling pricing or turnover dates we don\'t believe the developer can honor.'],
      ['num' => '06', 'title' => 'Long view', 'desc' => 'Our partnerships are measured in decades, not units. Every developer here has been with us for at least three years.'],
      ] as $s)
      <div class="feature">
        <div class="num">{{ $s['num'] }}</div>
        <div class="ttl">{{ $s['title'] }}</div>
        <p class="desc">{{ $s['desc'] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:60px;align-items:center" class="join-grid">
      <div class="reveal">
        @if ($pageClosing['eyebrow'])
        <span class="eyebrow">{{ $pageClosing['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          {{ $pageClosing['headlineLead'] }}
          @if ($pageClosing['headlineEm'])
          <em>{{ $pageClosing['headlineEm'] }}</em>
          @endif
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        @if ($pageClosing['body'])
        <p class="lead">{{ $pageClosing['body'] }}</p>
        @endif
        @if ($pageClosing['primaryLabel'])
        <div style="margin-top:24px">
          <a href="{{ $pageClosing['primaryUrl'] }}" class="btn btn-primary">
            {{ $pageClosing['primaryLabel'] }}
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
      .join-grid {
        grid-template-columns: 1fr !important;
        gap: 24px !important
      }
    }
  </style>
</section>
@endsection