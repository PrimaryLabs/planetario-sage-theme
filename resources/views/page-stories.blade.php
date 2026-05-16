@extends('layouts.app')

@section('content')
@php
use App\Data\StaticData;
$stories = $stories ?? StaticData::stories();
@endphp

{{-- Intro — Tier 2 compact hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
  <div class="container flex flex-col items-center justify-center text-center">
    @if ($pageIntro['eyebrow'])
    <span class="eyebrow-center">{{ $pageIntro['eyebrow'] }}</span>
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
    <p class="lead" style="margin-top:22px;max-width:62ch">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

<section class="section" style="padding-top:88px">
  <div class="container">
    @foreach ($stories as $i => $s)
    <article class="story reveal {{ $i % 2 === 0 ? 'reveal-left' : 'reveal-right' }}" style="transition-delay:{{ $i * 0.08 }}s">
      <div class="story-media">
        @php($mediaType = $s['mediaType'] ?? 'image')
        @if ($mediaType === 'youtube' && ! empty($s['youtube']['embed']))
        <div style="position:relative;width:100%;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:#000">
          <iframe src="{{ $s['youtube']['embed'] }}"
            title="{{ esc_attr($s['client']) }}"
            loading="lazy"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
            style="position:absolute;inset:0;width:100%;height:100%;border:0"></iframe>
        </div>
        @elseif ($mediaType === 'video' && ! empty($s['video']['url']))
        <video controls playsinline preload="metadata"
          @if (! empty($s['image'])) poster="{{ $s['image'] }}" @endif
          style="width:100%;border-radius:14px;border:1px solid var(--line);background:#000">
          <source src="{{ $s['video']['url'] }}" type="{{ $s['video']['mime'] }}">
        </video>
        @elseif (! empty($s['image']))
        <img src="{{ $s['image'] }}" alt="{{ esc_attr($s['client']) }}" loading="lazy">
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
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
        </div>
        @endif
      </div>
    </article>
    @endforeach
  </div>
</section>

@if (! empty($companyEvents))
<section class="section" id="company-events" style="padding-top:48px;border-top:1px solid var(--line)">
  <div class="container">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:24px;flex-wrap:wrap;margin-bottom:36px">
      <div>
        <span class="eyebrow">Company Events</span>
        <h2 class="h2" style="margin-top:14px;max-width:22ch">
          Moments from the <em>Planetario</em> calendar.
        </h2>
      </div>
      <p class="muted" style="max-width:42ch;font-size:14px">
        Launches, brokerages awards, community walks — a running gallery of the team out in the field.
      </p>
    </div>

    <div class="events-grid" style="display:grid;grid-template-columns:repeat(2,1fr);gap:32px">
      @foreach ($companyEvents as $event)
      <article class="event-card" style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column">
        @if ($event['cover'])
        <div style="aspect-ratio:16/9;background:#000;overflow:hidden">
          <img src="{{ $event['cover'] }}" alt="{{ esc_attr($event['title']) }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
        </div>
        @endif

        <div style="padding:28px;display:flex;flex-direction:column;gap:14px;flex:1">
          <div class="tag-row" style="display:flex;gap:10px;align-items:center;font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">
            @if ($event['dateLabel'])
            <span>{{ $event['dateLabel'] }}</span>
            @endif
            @if ($event['dateLabel'] && $event['location'])
            <span class="sep">·</span>
            @endif
            @if ($event['location'])
            <span style="color:var(--accent)">{{ $event['location'] }}</span>
            @endif
          </div>

          <h3 class="h2" style="font-size:clamp(22px,2vw,28px);margin:0">{{ $event['title'] }}</h3>

          @if ($event['summary'])
          <p style="color:var(--ink-2);line-height:1.65;font-size:14.5px;margin:0">{{ $event['summary'] }}</p>
          @endif

          @if (! empty($event['gallery']))
          <div class="event-gallery" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:6px">
            @foreach ($event['gallery'] as $item)
            @if ($item['kind'] === 'youtube')
            <div style="position:relative;aspect-ratio:1;border-radius:8px;overflow:hidden;border:1px solid var(--line);background:#000">
              <iframe src="{{ $item['embed'] }}"
                title="{{ esc_attr($item['caption'] ?: $event['title']) }}"
                loading="lazy"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen
                style="position:absolute;inset:0;width:100%;height:100%;border:0"></iframe>
            </div>
            @elseif ($item['kind'] === 'video')
            <video controls playsinline preload="metadata" style="width:100%;aspect-ratio:1;border-radius:8px;border:1px solid var(--line);background:#000;object-fit:cover">
              <source src="{{ $item['url'] }}" type="{{ $item['mime'] }}">
            </video>
            @else
            <a href="{{ $item['url'] }}" target="_blank" rel="noopener noreferrer" style="display:block;aspect-ratio:1;border-radius:8px;overflow:hidden;border:1px solid var(--line)">
              <img src="{{ $item['url'] }}" alt="{{ esc_attr($item['caption'] ?: $item['alt'] ?: $event['title']) }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block">
            </a>
            @endif
            @endforeach
          </div>
          @endif
        </div>
      </article>
      @endforeach
    </div>
  </div>
  <style>
    @media (max-width:880px) {
      .events-grid {
        grid-template-columns: 1fr !important
      }
    }

    @media (max-width:520px) {
      .event-gallery {
        grid-template-columns: repeat(2, 1fr) !important
      }
    }
  </style>
</section>
@endif

<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
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
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      @endif
    </div>
  </div>
</section>
@endsection