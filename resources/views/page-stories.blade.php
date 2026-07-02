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

<section class="section" style="padding-top:88px"
  data-edit-admin="edit.php?post_type=story"
  title="Click to manage stories in WP Admin">
  <div class="container">
    @foreach ($stories as $i => $s)
    <article class="story reveal {{ $i % 2 === 0 ? 'reveal-left' : 'reveal-right' }} grid grid-cols-1 lg:grid-cols-2" style="transition-delay:{{ $i * 0.08 }}s">
      <div class="h-full bg-(--bg) flex items-center">
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
      </div>
      <div class="story-body">
        <div class="tag-row">
          <span>Story {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
          <span class="sep">·</span>
          <span style="color:var(--accent)">{{ $s['location'] }}</span>
        </div>
        <h2 class="h2" style="font-size:clamp(22px,3vw,26px);margin-top:6px">{{ $s['client'] }}</h2>
        <blockquote style="font-family:var(--font-display);font-style:italic;font-size:clamp(14px,1.6vw,16px);color:var(--ink);margin:0;line-height:1.4;padding-left:18px;border-left:2px solid var(--accent)">
          "{{ $s['quote'] }}"
        </blockquote>
        <p style="color:var(--ink-2);line-height:1.65;font-size:13px">{{ $s['summary'] }}</p>
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
        @if (! empty($s['url']))
        <div style="margin-top:14px">
          <a href="{{ $s['url'] }}" class="btn btn-primary">
            Read their story
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
@endsection