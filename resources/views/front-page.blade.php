@extends('layouts.app')

@section('content')
@php
use App\Data\StaticData;
$properties = array_slice(StaticData::properties(), 0, 6);
$services = StaticData::services();
$testimonials = array_slice(StaticData::testimonials(), 0, 2);
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
                @if ($stat['suffix']) data-suffix="{{ $stat['suffix'] }}" @endif
              >{{ $stat['value'] }}{{ $stat['suffix'] }}</span></div>
            <div class="lbl">{{ $stat['label'] }}</div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</section>

{{-- Commitment --}}
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:60px;align-items:center" class="home-intro-grid">
      <div class="reveal reveal-left">
        @if ($commitment['eyebrow'])
          <span class="eyebrow">{{ $commitment['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          {{ $commitment['headlineLead'] }}
          @if ($commitment['headlineEm'])<em>{{ $commitment['headlineEm'] }}</em>@endif
          @if ($commitment['headlineTrail']) {{ $commitment['headlineTrail'] }}@endif
        </h2>
      </div>
      <div class="reveal reveal-right" style="transition-delay:.12s">
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

{{-- Vision / Mission --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
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

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px" class="vm-grid">
      <div class="reveal reveal-left">
        <span class="eyebrow">Vision</span>
        <div class="vm-body" style="font-family:var(--font-display);font-size:clamp(16px,2vw,24px);line-height:1.35;margin-top:18px;color:var(--ink)">
          {!! $vm['vision'] !!}
        </div>
      </div>
      <div class="reveal reveal-right" style="transition-delay:.15s">
        <span class="eyebrow">Mission</span>
        <div class="vm-body" style="font-family:var(--font-display);font-size:clamp(16px,2vw,24px);line-height:1.35;margin-top:18px;color:var(--ink)">
          {!! $vm['mission'] !!}
        </div>
      </div>
    </div>

    @if ($vm['cta']['label'])
      <div style="margin-top:48px">
        <a href="{{ $vm['cta']['url'] }}" class="btn">
          {{ $vm['cta']['label'] }}
          <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
      </div>
    @endif
  </div>
  <style>
    @media (max-width:860px) {
      .vm-grid {
        grid-template-columns: 1fr !important;
        gap: 40px !important
      }
    }
  </style>
</section>

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
            View all {{ count(StaticData::properties()) }} listings
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
        <span class="eyebrow">What we do</span>
        <h2 class="h2" style="margin-top:14px">Six services. <em>One promise.</em></h2>
      </div>
      <p class="lead reveal text-center" style="transition-delay:.1s">
        Property goals are personal. Our services are built around the actual
        decisions you'll need to make not the listings we want to push.
      </p>
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
          {!! $serviceIcons[$s['num']] ?? '' !!}
        </svg>
        <div class="feature-body">
          <div class="num">{{ $s['num'] }}</div>
          <div class="ttl">{{ $s['title'] }}</div>
          <p class="desc">{{ $s['desc'] }}</p>
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

{{-- Testimonials snippet --}}
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="stagger-children" style="display:grid;grid-template-columns:1fr 1fr;gap:28px" class="t-grid">
      @foreach ($testimonials as $t)
      <div class="testimonial">
        <div class="quote-mk">"</div>
        <blockquote>{{ $t['quote'] }}</blockquote>
        <div class="who">
          <img src="https://i.pravatar.cc/96?u={{ urlencode($t['name']) }}" alt="" width="48" height="48">
          <div>
            <div class="name">{{ $t['name'] }}</div>
            <div class="role">{{ $t['role'] }}</div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div style="margin-top:28px;text-align:center">
      <a href="{{ home_url('/testimonials') }}" class="btn">
        More testimonials
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </div>
  </div>
  <style>
    @media (max-width:800px) {
      .t-grid {
        grid-template-columns: 1fr !important
      }
    }
  </style>
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