@extends('layouts.app')

@section('content')
@php($v = $vlog ?? null)

@if (! $v)
<section class="section" style="padding-top:140px">
  <div class="container">
    <h2 class="h2">Video not found.</h2>
    <p class="lead" style="margin-top:18px">
      <a href="{{ home_url('/vlog') }}" class="btn">Back to vlogs</a>
    </p>
  </div>
</section>
@else

{{-- Header --}}
<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-260px;top:-40px;opacity:.18" />

    <a href="{{ home_url('/vlog') }}"
      style="display:inline-flex;align-items:center;gap:8px;font-family:var(--font-mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3);margin-bottom:36px">
      <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M12 7H2M6 3L2 7l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      All videos
    </a>

    @if ($v['location'])
    <div class="tag-row" style="margin-bottom:18px">
      <span style="color:var(--accent)">{{ $v['location'] }}</span>
    </div>
    @endif

    <h2 class="h2" style="max-width:22ch">{{ $v['title'] }}</h2>
  </div>
</section>

{{-- Media --}}
@php($mediaType = $v['mediaType'] ?? 'image')
@if ($mediaType === 'youtube' && ! empty($v['youtube']['embed']))
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="position:relative;width:100%;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:#000">
      <iframe src="{{ $v['youtube']['embed'] }}"
        title="{{ esc_attr($v['title']) }}"
        loading="lazy"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        allowfullscreen
        style="position:absolute;inset:0;width:100%;height:100%;border:0"></iframe>
    </div>
  </div>
</section>
@elseif ($mediaType === 'video' && ! empty($v['video']['url']))
@php($poster = ! empty($v['thumbnail']) ? ' poster="' . esc_attr($v['thumbnail']) . '"' : '')
<section class="section" style="padding-top:0">
  <div class="container">
    <video controls playsinline preload="metadata"{!! $poster !!}
      style="width:100%;border-radius:14px;border:1px solid var(--line);background:#000">
      <source src="{{ $v['video']['url'] }}" type="{{ $v['video']['mime'] }}">
    </video>
  </div>
</section>
@elseif (! empty($v['thumbnail']))
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="width:100%;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:var(--bg-3)">
      <img src="{{ $v['thumbnail'] }}" alt="{{ esc_attr($v['title']) }}"
        style="width:100%;height:100%;object-fit:cover;display:block">
    </div>
  </div>
</section>
@endif

{{-- Description --}}
@if ($v['description'])
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container">
    <div class="vlog-description" style="max-width:72ch;color:var(--ink-2);line-height:1.75;font-size:15.5px">
      {!! wp_kses_post($v['description']) !!}
    </div>
    <div style="margin-top:32px">
      <a href="{{ home_url('/vlog') }}" class="btn">← Back to vlogs</a>
    </div>
  </div>
</section>
@endif

{{-- Related vlogs --}}
@if (! empty($otherVlogs))
<section class="section" style="border-top:1px solid var(--line)">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">More videos</span>
      <h2 class="h2" style="margin-top:14px">
        Keep watching. <em>More from the field.</em>
      </h2>
    </div>

    <div class="vlog-rel-grid stagger-children" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:48px">
      @foreach ($otherVlogs as $card)
      <a href="{{ $card['permalink'] }}"
        class="vlog-card reveal"
        style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;text-decoration:none">

        @if (! empty($card['thumbnail']))
        <div style="aspect-ratio:16/9;overflow:hidden;background:#000;flex-shrink:0;position:relative">
          <img src="{{ $card['thumbnail'] }}" alt="{{ esc_attr($card['title']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
            loading="lazy">
          <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none">
            <svg width="44" height="44" viewBox="0 0 44 44" fill="none" aria-hidden="true" style="filter:drop-shadow(0 2px 8px rgba(0,0,0,.5))">
              <circle cx="22" cy="22" r="22" fill="rgba(0,0,0,.45)"/>
              <path d="M18 15l13 7-13 7V15z" fill="#fff"/>
            </svg>
          </div>
        </div>
        @endif

        <div style="padding:20px;flex:1;display:flex;flex-direction:column;gap:8px">
          @if ($card['location'])
          <span style="font-family:var(--font-mono);font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--accent)">{{ $card['location'] }}</span>
          @endif
          <p style="font-weight:600;font-size:15px;color:var(--ink);line-height:1.35;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
            {{ $card['title'] }}
          </p>
        </div>

      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<style>
  @media (max-width: 720px) {
    .vlog-rel-grid { grid-template-columns: repeat(2, 1fr) !important }
  }
  @media (max-width: 480px) {
    .vlog-rel-grid { grid-template-columns: 1fr !important }
  }
  .vlog-card:hover { border-color: var(--line-2) !important }
  .vlog-card:hover img { transform: scale(1.04) }
  .vlog-description h2,
  .vlog-description h3,
  .vlog-description h4 {
    font-family: var(--font-display);
    color: var(--ink);
    margin-top: 28px;
    margin-bottom: 10px;
  }
  .vlog-description a { color: var(--accent); text-decoration: underline !important }
  .vlog-description ul,
  .vlog-description ol { padding-left: 20px; margin-top: 12px }
  .vlog-description li { margin-bottom: 6px }
</style>

@endif
@endsection
