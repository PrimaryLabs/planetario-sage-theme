@extends('layouts.app')

@section('content')
@php use App\Data\StaticData; $devs = $developers ?? StaticData::developers(); $devGroups = $devGroups ?? []; @endphp

{{-- Intro — Tier 2 compact hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
  <div class="container flex flex-col items-center justify-center text-center">
    @if ($pageIntro['eyebrow'])
    <span class="eyebrow-center"
      data-edit-field="page_intro_eyebrow"
      data-edit-type="text"
      data-edit-label="Intro Eyebrow">{{ $pageIntro['eyebrow'] }}</span>
    @endif

    <h1 class="display" style="margin-top:18px;max-width:20ch">
      <span
        data-edit-field="page_intro_headline_lead"
        data-edit-type="text"
        data-edit-label="Intro Headline Lead">{{ $pageIntro['headlineLead'] }}</span>
      @if ($pageIntro['headlineEm'])
      <em
        data-edit-field="page_intro_headline_emphasis"
        data-edit-type="text"
        data-edit-label="Intro Headline Emphasis">{{ $pageIntro['headlineEm'] }}</em>
      @endif
      @if ($pageIntro['headlineTrail'])
      <span
        data-edit-field="page_intro_headline_trail"
        data-edit-type="text"
        data-edit-label="Intro Headline Trail">{{ $pageIntro['headlineTrail'] }}</span>
      @endif
    </h1>

    @if ($pageIntro['lead'])
    <p class="lead" style="margin-top:22px;max-width:64ch"
      data-edit-field="page_intro_lead"
      data-edit-type="textarea"
      data-edit-label="Intro Lead">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

<section class="section" style="padding-top:64px">
  <div class="container">
    <div class="managers-tabs" role="tablist" aria-label="Filter by region" style="margin-bottom:36px" data-dev-filter-bar>
      <button class="managers-tab is-active" role="tab" aria-selected="true" data-dev-filter="all">
        All
      </button>
      @foreach ($devGroups as $group)
      <button class="managers-tab" role="tab" aria-selected="false" data-dev-filter="{{ $group['slug'] }}">
        {{ $group['label'] }}
      </button>
      @endforeach
    </div>

    @foreach ($devGroups as $group)
    <div class="dev-region-group" data-dev-region="{{ $group['slug'] }}" style="margin-bottom:48px">
      <h3 class="dev-region-heading" style="margin-bottom:18px">{{ $group['label'] }} Developers</h3>

      <div class="stagger-children dev-logo-wall"
        data-edit-admin="edit.php?post_type=developer"
        title="Click to manage developers in WP Admin">
        @foreach ($group['items'] as $d)
        @if (! empty($d['website']))
        <a href="{{ $d['website'] }}" class="dev-logo-item reveal"
          target="_blank" rel="noopener noreferrer">
          @if (! empty($d['logo']))
          <img src="{{ $d['logo'] }}" alt="{{ esc_attr($d['name']) }}" loading="lazy">
          @else
          <span class="dev-logo-text">{{ $d['name'] }}</span>
          @endif
          <span class="dev-logo-label">{{ $d['name'] }}</span>
        </a>
        @else
        <div class="dev-logo-item reveal">
          @if (! empty($d['logo']))
          <img src="{{ $d['logo'] }}" alt="{{ esc_attr($d['name']) }}" loading="lazy">
          @else
          <span class="dev-logo-text">{{ $d['name'] }}</span>
          @endif
          <span class="dev-logo-label">{{ $d['name'] }}</span>
        </div>
        @endif
        @endforeach
      </div>
    </div>
    @endforeach
  </div>
</section>

<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow"
          data-edit-field="devs_how_eyebrow"
          data-edit-type="text"
          data-edit-label="How We Work — Eyebrow">{{ $howWeWork['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="devs_how_headline_lead"
            data-edit-type="text"
            data-edit-label="How We Work — Headline Lead">{{ $howWeWork['headlineLead'] }}</span>
          @if ($howWeWork['headlineEm'])
          <em
            data-edit-field="devs_how_headline_em"
            data-edit-type="text"
            data-edit-label="How We Work — Headline Em">{{ $howWeWork['headlineEm'] }}</em>
          @endif
        </h2>
      </div>
      <p class="lead reveal" style="transition-delay:.1s"
        data-edit-field="devs_how_lead"
        data-edit-type="textarea"
        data-edit-label="How We Work — Lead">{{ $howWeWork['lead'] }}</p>
    </div>

    @php $devPageId = \url_to_postid(\home_url('/developers')); @endphp
    <div class="stagger-children feature-grid"
      data-edit-admin="post.php?post={{ $devPageId }}&action=edit"
      title="Click to edit feature cards in WP Admin">
      @foreach ($howWeWork['items'] as $s)
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
        <span class="eyebrow"
          data-edit-field="page_closing_eyebrow"
          data-edit-type="text"
          data-edit-label="Closing Eyebrow">{{ $pageClosing['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="page_closing_headline_lead"
            data-edit-type="text"
            data-edit-label="Closing Headline Lead">{{ $pageClosing['headlineLead'] }}</span>
          @if ($pageClosing['headlineEm'])
          <em
            data-edit-field="page_closing_headline_emphasis"
            data-edit-type="text"
            data-edit-label="Closing Headline Emphasis">{{ $pageClosing['headlineEm'] }}</em>
          @endif
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        @if ($pageClosing['body'])
        <p class="lead"
          data-edit-field="page_closing_body"
          data-edit-type="textarea"
          data-edit-label="Closing Body">{{ $pageClosing['body'] }}</p>
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