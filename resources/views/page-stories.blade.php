@extends('layouts.app')

@section('content')
@php
  use App\Data\StaticData;
  $stories = $stories ?? StaticData::stories();
@endphp

<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
    @if ($pageIntro['eyebrow'])<span class="eyebrow">{{ $pageIntro['eyebrow'] }}</span>@endif
    <h1 class="display" style="margin-top:18px;max-width:20ch">
      {{ $pageIntro['headlineLead'] }}@if ($pageIntro['headlineEm']) <em>{{ $pageIntro['headlineEm'] }}</em>@endif@if ($pageIntro['headlineTrail']) {{ $pageIntro['headlineTrail'] }}@endif
    </h1>
    @if ($pageIntro['lead'])
      <p class="lead" style="margin-top:22px;max-width:62ch">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

<section class="section" style="padding-top:24px">
  <div class="container">
    @foreach ($stories as $i => $s)
      <article class="story reveal {{ $i % 2 === 0 ? 'reveal-left' : 'reveal-right' }}" style="transition-delay:{{ $i * 0.08 }}s">
        <div class="story-media">
          @if (! empty($s['image']))
            <img src="{{ $s['image'] }}"
                 alt="{{ esc_attr($s['client']) }}" loading="lazy">
          @endif
        </div>
        <div class="story-body">
          <div class="tag-row">
            <span>Case {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
            <span class="sep">·</span>
            <span style="color:var(--accent)">{{ $s['location'] }}</span>
          </div>
          <h2 class="h2" style="font-size:clamp(26px,3vw,40px);margin-top:6px">{{ $s['client'] }}</h2>
          <blockquote style="font-family:var(--font-display);font-style:italic;font-size:clamp(18px,1.6vw,22px);color:var(--ink);margin:0;line-height:1.4;padding-left:18px;border-left:2px solid var(--accent)">
            "{{ $s['quote'] }}"
          </blockquote>
          <p style="color:var(--ink-2);line-height:1.65;font-size:15px">{{ $s['summary'] }}</p>
          <div class="story-stats">
            @foreach ($s['stats'] as $st)
              <div class="s">
                <div class="v">{{ $st['v'] }}</div>
                <div class="l">{{ $st['l'] }}</div>
              </div>
            @endforeach
          </div>
          @if (! empty($s['property']))
            <div style="margin-top:18px">
              <a href="{{ $s['property']['url'] }}" class="btn">
                View the property — {{ $s['property']['name'] }}
                <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
              </a>
            </div>
          @endif
        </div>
      </article>
    @endforeach
  </div>
</section>

<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container" style="text-align:center">
    <p class="banner-quote">
      {{ $pageClosing['headlineLead'] }}@if ($pageClosing['headlineEm']) <em>{{ $pageClosing['headlineEm'] }}</em>@endif @if ($pageClosing['body']){{ $pageClosing['body'] }}@endif
    </p>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:32px;flex-wrap:wrap">
      @if ($pageClosing['primaryLabel'])
        <a href="{{ $pageClosing['primaryUrl'] }}" class="btn btn-primary">
          {{ $pageClosing['primaryLabel'] }}
          <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      @endif
      @if ($pageClosing['secondaryLabel'])
        <a href="{{ $pageClosing['secondaryUrl'] }}" class="btn">
          {{ $pageClosing['secondaryLabel'] }}
          <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      @endif
    </div>
  </div>
</section>
@endsection
