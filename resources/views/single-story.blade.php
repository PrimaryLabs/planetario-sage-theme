@extends('layouts.app')

@section('content')
@php($s = $story ?? null)

@if (! $s)
<section class="section" style="padding-top:140px">
  <div class="container">
    <h1 class="display">Story not found.</h1>
    <p class="lead" style="margin-top:18px">
      <a href="{{ home_url('/stories') }}" class="btn">Back to stories</a>
    </p>
  </div>
</section>
@else

{{-- Hero --}}
<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-260px;top:-40px;opacity:.18" />

    <a href="{{ home_url('/stories') }}"
      style="display:inline-flex;align-items:center;gap:8px;font-family:var(--font-mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3);margin-bottom:36px">
      <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M12 7H2M6 3L2 7l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      All stories
    </a>

    <div class="tag-row" style="margin-bottom:18px">
      @if ($s['location'])
      <span style="color:var(--accent)">{{ $s['location'] }}</span>
      @endif
      @if ($s['location'] && $s['year'])
      <span class="sep">·</span>
      @endif
      @if ($s['year'])
      <span>{{ $s['year'] }}</span>
      @endif
    </div>

    <h1 class="display" style="max-width:22ch">{{ $s['client'] }}</h1>

    @if ($s['quote'])
    <blockquote style="margin-top:28px;max-width:64ch;font-family:var(--font-display);font-style:italic;font-size:clamp(20px,2vw,26px);color:var(--ink);line-height:1.4;padding-left:22px;border-left:2px solid var(--accent)">
      "{{ $s['quote'] }}"
    </blockquote>
    @endif
  </div>
</section>

{{-- Media --}}
@php($mediaType = $s['mediaType'] ?? 'image')
@if ($mediaType === 'youtube' && ! empty($s['youtube']['embed']))
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="position:relative;width:100%;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:#000">
      <iframe src="{{ $s['youtube']['embed'] }}"
        title="{{ esc_attr($s['client']) }}"
        loading="lazy"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        allowfullscreen
        style="position:absolute;inset:0;width:100%;height:100%;border:0"></iframe>
    </div>
  </div>
</section>
@elseif ($mediaType === 'video' && ! empty($s['video']['url']))
<section class="section" style="padding-top:0">
  <div class="container">
    <video controls playsinline preload="metadata"
      @if (! empty($s['image']))
      poster="{{ $s['image'] }}"
      @endif
      style="width:100%;border-radius:14px;border:1px solid var(--line);background:#000">
      <source src="{{ $s['video']['url'] }}" type="{{ $s['video']['mime'] }}">
    </video>
  </div>
</section>
@elseif (! empty($s['image']))
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="width:100%;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:var(--bg-3)">
      <img src="{{ $s['image'] }}" alt="{{ esc_attr($s['client']) }}"
        style="width:100%;height:100%;object-fit:cover;display:block">
    </div>
  </div>
</section>
@endif

{{-- Story body: stats sidebar + summary --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container">
    <div class="st-body-grid" style="display:grid;grid-template-columns:240px 1fr;gap:56px;align-items:start">

      {{-- Stats sidebar --}}
      @if (! empty($s['stats']))
      <div style="display:flex;flex-direction:column;gap:28px">
        @foreach ($s['stats'] as $st)
        <div>
          <div style="font-family:var(--font-display);font-size:clamp(28px,3vw,40px);font-weight:700;color:var(--ink);line-height:1">{{ $st['v'] }}</div>
          <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3);margin-top:6px">{{ $st['l'] }}</div>
        </div>
        @endforeach

        <div style="margin-top:8px">
          <a href="{{ home_url('/stories') }}" class="btn">
            ← Back to stories
          </a>
        </div>
      </div>
      @endif

      {{-- Summary + property link --}}
      <div>
        @if ($s['summary'])
        <div class="st-summary" style="color:var(--ink-2);line-height:1.75;font-size:15.5px">
          {!! wp_kses_post($s['summary']) !!}
        </div>
        @endif

        @if (! empty($s['property']))
        <div style="margin-top:32px">
          <a href="{{ $s['property']['url'] }}" class="btn btn-primary">
            View the property — {{ $s['property']['name'] }}
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
        </div>
        @endif
      </div>

    </div>
  </div>
</section>

{{-- Read Also --}}
@if (! empty($otherStories))
<section class="section" style="border-top:1px solid var(--line)">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">More stories</span>
      <h2 class="h2" style="margin-top:14px">
        Other families. <em>Other chapters.</em>
      </h2>
    </div>

    <div class="st-grid stagger-children" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:48px">
      @foreach ($otherStories as $card)
      <a href="{{ $card['url'] }}"
        class="st-card reveal"
        style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;text-decoration:none">

        @if (! empty($card['image']))
        <div style="aspect-ratio:4/3;overflow:hidden;background:#000;flex-shrink:0">
          <img src="{{ $card['image'] }}" alt="{{ esc_attr($card['client']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
            loading="lazy">
        </div>
        @endif

        <div style="padding:22px;flex:1;display:flex;flex-direction:column;gap:10px">
          <span style="font-family:var(--font-mono);font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--accent)">{{ $card['client'] }}</span>

          @if ($card['quote'])
          <p style="font-family:var(--font-display);font-style:italic;font-size:15px;color:var(--ink);line-height:1.5;margin:0;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
            "{{ $card['quote'] }}"
          </p>
          @endif

          <div class="tag-row" style="margin-top:auto;padding-top:8px">
            @if ($card['location'])
            <span style="color:var(--accent)">{{ $card['location'] }}</span>
            @endif
            @if ($card['location'] && $card['year'])
            <span class="sep">·</span>
            @endif
            @if ($card['year'])
            <span>{{ $card['year'] }}</span>
            @endif
          </div>
        </div>

      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<style>
  @media (max-width: 860px) {
    .st-body-grid { grid-template-columns: 1fr !important }
  }
  @media (max-width: 720px) {
    .st-grid { grid-template-columns: repeat(2, 1fr) !important }
  }
  @media (max-width: 480px) {
    .st-grid { grid-template-columns: 1fr !important }
  }
  .st-card:hover { border-color: var(--line-2) !important }
  .st-card:hover img { transform: scale(1.04) }
  .st-summary h2,
  .st-summary h3,
  .st-summary h4 {
    font-family: var(--font-display);
    color: var(--ink);
    margin-top: 28px;
    margin-bottom: 10px;
  }
  .st-summary a { color: var(--accent); text-decoration: underline !important }
  .st-summary ul,
  .st-summary ol { padding-left: 20px; margin-top: 12px }
  .st-summary li { margin-bottom: 6px }
</style>

@endif
@endsection
