@extends('layouts.app')

@section('content')
@php
  use App\Data\StaticData;
  $stories = StaticData::stories();
  $storyImages = [
    'https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=800&h=600&fit=crop&q=80',
    'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop&q=80',
    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop&q=80',
    'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop&q=80',
  ];
@endphp

<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
    <span class="eyebrow">Success stories</span>
    <h1 class="display" style="margin-top:18px;max-width:20ch">
      Four families. <em>Four small chapters</em> in a longer book.
    </h1>
    <p class="lead" style="margin-top:22px;max-width:62ch">
      Every closing is a private milestone. With permission, we share a few here —
      not because the numbers are big, but because the people behind them trusted
      us with something that mattered.
    </p>
  </div>
</section>

<section class="section" style="padding-top:24px">
  <div class="container">
    @foreach ($stories as $i => $s)
      <article class="story reveal {{ $i % 2 === 0 ? 'reveal-left' : 'reveal-right' }}" style="transition-delay:{{ $i * 0.08 }}s">
        <div class="story-media">
          <img src="{{ $storyImages[$i] ?? $storyImages[0] }}"
               alt="{{ esc_attr($s['client']) }}" loading="lazy">
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
        </div>
      </article>
    @endforeach
  </div>
</section>

<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container" style="text-align:center">
    <p class="banner-quote">
      What will <em>your story</em> sound like, three years from now?
    </p>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:32px;flex-wrap:wrap">
      <a href="{{ home_url('/contact') }}" class="btn btn-primary">
        Start a conversation
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <a href="{{ home_url('/properties') }}" class="btn">
        Browse listings
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>
@endsection
