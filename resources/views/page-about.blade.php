@extends('layouts.app')

@section('content')

{{-- Intro --}}
<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-260px;top:-40px;opacity:.3" />
    @if ($aboutIntro['eyebrow'])
      <span class="eyebrow">{{ $aboutIntro['eyebrow'] }}</span>
    @endif
    <h1 class="display" style="margin-top:18px;max-width:18ch">
      {{ $aboutIntro['headlineLead'] }}
      @if ($aboutIntro['headlineEm']) <em>{{ $aboutIntro['headlineEm'] }}</em>@endif
      @if ($aboutIntro['headlineTrail']) {{ $aboutIntro['headlineTrail'] }}@endif
    </h1>
    @if ($aboutIntro['lead'])
      <p class="lead" style="margin-top:28px;max-width:60ch">{{ $aboutIntro['lead'] }}</p>
    @endif
  </div>
</section>

{{-- Landscape image --}}
@if ($aboutIntro['imageUrl'])
  <section style="padding:0 0 60px">
    <div class="container">
      <div class="reveal" style="aspect-ratio:21/9;border-radius:14px;overflow:hidden;border:1px solid var(--line)">
        <img src="{{ $aboutIntro['imageUrl'] }}"
          alt="{{ $aboutIntro['imageAlt'] }}"
          style="width:100%;height:100%;object-fit:cover;filter:brightness(.85)">
      </div>
    </div>
  </section>
@endif

{{-- Vision / Mission --}}
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px" class="vm-grid">
      <div class="reveal reveal-left">
        <span class="eyebrow">Vision</span>
        <div class="vm-body" style="font-family:var(--font-display);font-size:clamp(20px,2vw,28px);line-height:1.35;margin-top:18px;color:var(--ink)">
          {!! $aboutVm['vision'] !!}
        </div>
      </div>
      <div class="reveal reveal-right" style="transition-delay:.15s">
        <span class="eyebrow">Mission</span>
        <div class="vm-body" style="font-family:var(--font-display);font-size:clamp(20px,2vw,28px);line-height:1.35;margin-top:18px;color:var(--ink)">
          {!! $aboutVm['mission'] !!}
        </div>
      </div>
    </div>
  </div>
  <style>@media (max-width:860px){.vm-grid{grid-template-columns:1fr!important;gap:40px!important}}</style>
</section>

{{-- Core Values --}}
<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        @if ($aboutValues['eyebrow'])<span class="eyebrow">{{ $aboutValues['eyebrow'] }}</span>@endif
        <h2 class="h2" style="margin-top:14px">
          {{ $aboutValues['headlineLead'] }}
          @if ($aboutValues['headlineEm']) <em>{{ $aboutValues['headlineEm'] }}</em>@endif
        </h2>
      </div>
      @if ($aboutValues['intro'])
        <p class="lead reveal" style="transition-delay:.1s">{{ $aboutValues['intro'] }}</p>
      @endif
    </div>
    <div class="stagger-children value-list">
      @foreach ($aboutValues['items'] as $v)
        <div class="value-row">
          <div class="num">{{ $v['number'] }}</div>
          <div class="ttl">{{ $v['title'] }}</div>
          <div class="desc">{{ $v['description'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Why Choose Us --}}
<section class="section">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal text-center">
        @if ($aboutWhy['eyebrow'])<span class="eyebrow">{{ $aboutWhy['eyebrow'] }}</span>@endif
        <h2 class="h2" style="margin-top:14px">
          {{ $aboutWhy['headlineLead'] }}
          @if ($aboutWhy['headlineEm']) <br /><em>{{ $aboutWhy['headlineEm'] }}</em>@endif
        </h2>
      </div>
    </div>
    <div class="stagger-children feature-grid">
      @foreach ($aboutWhy['items'] as $s)
        <div class="feature">
          <div class="num">{{ $s['number'] }}</div>
          <div class="ttl">{{ $s['title'] }}</div>
          <p class="desc">{{ $s['description'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Closing quote --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container" style="text-align:center">
    <div class="banner-quote">{!! $aboutClosing['quote'] !!}</div>
    @if ($aboutClosing['attribution'])
      <div style="margin-top:16px;font-family:var(--font-mono);font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:var(--ink-3)">
        {{ $aboutClosing['attribution'] }}
      </div>
    @endif
    <div style="display:flex;justify-content:center;margin-top:36px;gap:12px;flex-wrap:wrap">
      @if ($aboutClosing['primaryLabel'])
        <a href="{{ $aboutClosing['primaryUrl'] }}" class="btn btn-primary">
          {{ $aboutClosing['primaryLabel'] }}
          <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
      @endif
      @if ($aboutClosing['secondaryLabel'])
        <a href="{{ $aboutClosing['secondaryUrl'] }}" class="btn">
          {{ $aboutClosing['secondaryLabel'] }}
          <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>
      @endif
    </div>
  </div>
</section>
@endsection
