@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
  <div class="container flex flex-col items-center justify-center text-center">
    @if ($pageIntro['eyebrow'])
    <span class="eyebrow-center">{{ $pageIntro['eyebrow'] }}</span>
    @endif

    <h1 class="display" style="margin-top:18px;max-width:22ch">
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

{{-- Featured Events --}}
@if (! empty($featuredEvents))
<section class="section" style="padding-top:88px">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">Featured</span>
        <h2 class="h2" style="margin-top:14px">Moments worth <em>marking.</em></h2>
      </div>
    </div>

    <div class="ev-featured stagger-children" style="display:grid;grid-template-columns:1.4fr 1fr;gap:24px;margin-top:48px">

      {{-- Primary featured --}}
      @php($primary = $featuredEvents[0])
      <a href="{{ $primary['permalink'] }}"
        class="ev-card reveal"
        style="border-radius:14px;overflow:hidden;background:var(--bg-2);border:1px solid var(--line);display:flex;flex-direction:column;text-decoration:none">
        @if ($primary['cover'])
        <div style="aspect-ratio:16/9;overflow:hidden;background:#000;flex-shrink:0">
          <img src="{{ $primary['cover'] }}" alt="{{ esc_attr($primary['title']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
            loading="eager">
        </div>
        @endif
        <div style="padding:28px 32px;flex:1;display:flex;flex-direction:column;gap:14px">
          <div class="tag-row">
            @if ($primary['dateLabel'])
            <span>{{ $primary['dateLabel'] }}</span>
            @endif
            @if ($primary['dateLabel'] && $primary['location'])
            <span class="sep">·</span>
            @endif
            @if ($primary['location'])
            <span style="color:var(--accent)">{{ $primary['location'] }}</span>
            @endif
          </div>
          <h2 class="h2" style="font-size:clamp(22px,2.8vw,36px);margin:0">{{ $primary['title'] }}</h2>
          @if ($primary['summary'])
          <p class="lead" style="font-size:14.5px;margin:0">{{ $primary['summary'] }}</p>
          @endif
          <div style="margin-top:auto;padding-top:8px;display:inline-flex;align-items:center;gap:6px;font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--accent)">
            View event
            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      </a>

      {{-- Secondary (2nd + 3rd) --}}
      <div style="display:flex;flex-direction:column;gap:20px">
        @foreach (array_slice($featuredEvents, 1, 2) as $ev)
        <a href="{{ $ev['permalink'] }}"
          class="ev-card reveal"
          style="border-radius:14px;overflow:hidden;background:var(--bg-2);border:1px solid var(--line);display:flex;flex-direction:column;flex:1;text-decoration:none">
          @if ($ev['cover'])
          <div style="aspect-ratio:16/7;overflow:hidden;background:#000;flex-shrink:0">
            <img src="{{ $ev['cover'] }}" alt="{{ esc_attr($ev['title']) }}"
              style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
              loading="lazy">
          </div>
          @endif
          <div style="padding:20px 24px;flex:1;display:flex;flex-direction:column;gap:10px">
            <div class="tag-row">
              @if ($ev['dateLabel'])
              <span>{{ $ev['dateLabel'] }}</span>
              @endif
              @if ($ev['dateLabel'] && $ev['location'])
              <span class="sep">·</span>
              @endif
              @if ($ev['location'])
              <span style="color:var(--accent)">{{ $ev['location'] }}</span>
              @endif
            </div>
            <h3 class="h3" style="margin:0">{{ $ev['title'] }}</h3>
          </div>
        </a>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif

{{-- All Events Grid --}}
@if (! empty($allEvents))
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">All events</span>
        <h2 class="h2" style="margin-top:14px">The full <em>calendar.</em></h2>
      </div>
    </div>

    <div class="ev-grid stagger-children" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:48px">
      @foreach ($allEvents as $ev)
      <a href="{{ $ev['permalink'] }}"
        class="ev-card reveal"
        style="background:var(--bg-3);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;text-decoration:none">
        @if ($ev['cover'])
        <div style="aspect-ratio:16/9;overflow:hidden;background:#000;flex-shrink:0">
          <img src="{{ $ev['cover'] }}" alt="{{ esc_attr($ev['title']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
            loading="lazy">
        </div>
        @endif
        <div style="padding:22px;flex:1;display:flex;flex-direction:column;gap:10px">
          <div class="tag-row">
            @if ($ev['dateLabel'])
            <span>{{ $ev['dateLabel'] }}</span>
            @endif
            @if ($ev['dateLabel'] && $ev['location'])
            <span class="sep">·</span>
            @endif
            @if ($ev['location'])
            <span style="color:var(--accent)">{{ $ev['location'] }}</span>
            @endif
          </div>
          <h3 class="h3" style="margin:0;font-size:clamp(18px,1.8vw,22px)">{{ $ev['title'] }}</h3>
          @if ($ev['summary'])
          <p style="color:var(--ink-2);font-size:14px;line-height:1.6;margin:0">{{ $ev['summary'] }}</p>
          @endif
          <div style="margin-top:auto;padding-top:8px;display:inline-flex;align-items:center;gap:6px;font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--accent)">
            View event
            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Closing CTA --}}
@if ($pageClosing['primaryLabel'] || $pageClosing['headlineLead'])
<section class="section" style="border-top:1px solid var(--line)">
  <div class="container" style="text-align:center">
    <p class="banner-quote">
      {{ $pageClosing['headlineLead'] }}
      @if ($pageClosing['headlineEm'])
      <em>{{ $pageClosing['headlineEm'] }}</em>
      @endif
      @if ($pageClosing['body'])
      {{ $pageClosing['body'] }}
      @endif
    </p>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:32px;flex-wrap:wrap">
      @if ($pageClosing['primaryLabel'])
      <a href="{{ $pageClosing['primaryUrl'] }}" class="btn btn-primary">
        {{ $pageClosing['primaryLabel'] }}
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      @endif
      @if ($pageClosing['secondaryLabel'])
      <a href="{{ $pageClosing['secondaryUrl'] }}" class="btn">
        {{ $pageClosing['secondaryLabel'] }}
      </a>
      @endif
    </div>
  </div>
</section>
@endif

<style>
  @media (max-width: 860px) {
    .ev-featured { grid-template-columns: 1fr !important }
  }
  @media (max-width: 720px) {
    .ev-grid { grid-template-columns: repeat(2, 1fr) !important }
  }
  @media (max-width: 480px) {
    .ev-grid { grid-template-columns: 1fr !important }
  }
  .ev-card:hover { border-color: var(--line-2) !important }
  .ev-card:hover img { transform: scale(1.04) }
</style>

@endsection
