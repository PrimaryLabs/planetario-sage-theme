@extends('layouts.app')

@section('content')
@php
$vlogs = $vlogs ?? [];
@endphp

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
    <p class="lead" style="margin-top:22px;max-width:62ch"
      data-edit-field="page_intro_lead"
      data-edit-type="textarea"
      data-edit-label="Intro Lead">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

{{-- Vlog grid --}}
<section class="section" style="padding-top:88px"
  data-edit-admin="edit.php?post_type=vlog"
  title="Click to manage vlogs in WP Admin">
  <div class="container">
    @if (empty($vlogs))
    <p style="text-align:center;color:var(--ink-2);padding:64px 0">No videos published yet.</p>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:36px">
      @foreach ($vlogs as $v)
      @php
      $mediaType = $v['mediaType'] ?? 'image';
      $cardThumb = $v['thumbnail'];
      if (! $cardThumb && $mediaType === 'youtube' && ! empty($v['youtube']['embed'])) {
          preg_match('#/embed/([A-Za-z0-9_-]+)#', $v['youtube']['embed'], $ytm);
          $cardThumb = isset($ytm[1]) ? 'https://img.youtube.com/vi/' . $ytm[1] . '/hqdefault.jpg' : '';
      }
      $hasPlay = $mediaType === 'youtube' || $mediaType === 'video';
      @endphp
      <a href="{{ $v['permalink'] }}"
        class="vlog-list-card reveal"
        style="transition-delay:{{ $loop->index * 0.06 }}s;display:flex;flex-direction:column;border:1px solid var(--line);border-radius:16px;overflow:hidden;background:var(--bg);text-decoration:none;color:inherit">

        {{-- Thumbnail with optional play overlay --}}
        <div style="position:relative;aspect-ratio:16/9;overflow:hidden;background:var(--bg-3);flex-shrink:0">
          @if ($cardThumb)
          <img src="{{ $cardThumb }}"
            alt="{{ esc_attr($v['title']) }}"
            loading="lazy"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease">
          @endif
          @if ($hasPlay)
          <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none">
            <svg width="52" height="52" viewBox="0 0 52 52" fill="none" aria-hidden="true" style="filter:drop-shadow(0 2px 10px rgba(0,0,0,.55))">
              <circle cx="26" cy="26" r="26" fill="rgba(0,0,0,.45)"/>
              <path d="M21 17l16 9-16 9V17z" fill="#fff"/>
            </svg>
          </div>
          @endif
        </div>

        {{-- Card body --}}
        <div style="padding:24px 24px 20px;display:flex;flex-direction:column;flex:1;gap:10px">
          @if ($v['location'])
          <span style="font-size:12px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--accent)">{{ $v['location'] }}</span>
          @endif

          <h2 class="h3" style="margin:0;line-height:1.25">{{ $v['title'] }}</h2>

          @if ($v['description'])
          <div style="font-size:15px;color:var(--ink-2);line-height:1.65;flex:1;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
            {!! wp_kses_post($v['description']) !!}
          </div>
          @endif
        </div>

      </a>
      @endforeach
    </div>
    @endif
  </div>
</section>

{{-- Closing CTA --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container" style="text-align:center">
    <p class="banner-quote">
      <span
        data-edit-field="page_closing_headline_lead"
        data-edit-type="text"
        data-edit-label="Closing Quote Lead">{{ $pageClosing['headlineLead'] }}</span>
      @if ($pageClosing['headlineEm'])
      <em
        data-edit-field="page_closing_headline_emphasis"
        data-edit-type="text"
        data-edit-label="Closing Quote Emphasis">{{ $pageClosing['headlineEm'] }}</em>
      @endif
      @if ($pageClosing['body'])
      <span
        data-edit-field="page_closing_body"
        data-edit-type="text"
        data-edit-label="Closing Quote Body">{{ $pageClosing['body'] }}</span>
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
<style>
  .vlog-list-card:hover { border-color: var(--line-2) !important }
  .vlog-list-card:hover img { transform: scale(1.04) }
</style>
@endsection
