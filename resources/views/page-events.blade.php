@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
  <div class="container flex flex-col items-center justify-center text-center">
    @if ($pageIntro['eyebrow'])
    <span class="eyebrow-center"
      data-edit-field="page_intro_eyebrow"
      data-edit-type="text"
      data-edit-label="Intro Eyebrow">{{ $pageIntro['eyebrow'] }}</span>
    @endif

    <h1 class="display" style="margin-top:18px;max-width:22ch">
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
    <p class="lead" style="margin-top:22px;max-width:64ch"
      data-edit-field="page_intro_lead"
      data-edit-type="textarea"
      data-edit-label="Intro Lead">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

{{-- Featured Events --}}
@if (! empty($featuredEvents))
<section class="section" style="padding-top:88px">
  <div class="container">
    <div class="section-head flex!">
      <div class="reveal w-full!">
        <span class="eyebrow"
          data-edit-field="events_featured_eyebrow"
          data-edit-type="text"
          data-edit-label="Featured — Eyebrow">{{ $eventsSections['featured']['eyebrow'] }}</span>
        <h2 class="h2 flex w-full gap-3" style="margin-top:14px">
          <span
            data-edit-field="events_featured_heading_lead"
            data-edit-type="text"
            data-edit-label="Featured — Heading Lead">{{ $eventsSections['featured']['headingLead'] }}</span>
          @if ($eventsSections['featured']['headingEm'])
          <em
            data-edit-field="events_featured_heading_em"
            data-edit-type="text"
            data-edit-label="Featured — Heading Emphasis">{{ $eventsSections['featured']['headingEm'] }}</em>
          @endif
        </h2>
      </div>
    </div>

    <div class="ev-featured stagger-children grid-cols-[2.2fr_1fr]"
      data-edit-admin="edit.php?post_type=company_event"
      title="Click to manage events in WP Admin"
      style="display:grid;gap:24px;margin-top:48px">

      {{-- Primary featured — full-bleed loc-style card --}}
      @php($primary = $featuredEvents[0])
      <a href="{{ $primary['permalink'] }}"
        class="ev-card reveal"
        style="position:relative;border-radius:14px;overflow:hidden;aspect-ratio:4/3;border:1px solid var(--line);display:block;text-decoration:none">
        @if ($primary['cover'])
        <img src="{{ $primary['cover'] }}" alt="{{ esc_attr($primary['title']) }}"
          style="width:100%;height:100%;object-fit:cover;filter:brightness(.65);transition:transform .4s ease"
          loading="eager">
        @endif
        <div style="position:absolute;inset:0;padding:32px;display:flex;flex-direction:column;justify-content:flex-end;background:linear-gradient(180deg,transparent 35%,rgba(6,13,31,.92))">
          @if ($primary['dateLabel'] || $primary['location'])
          <span class="eyebrow">
            {{ $primary['dateLabel'] }}
            @if ($primary['dateLabel'] && $primary['location'])
            &nbsp;·&nbsp;
            @endif
            {{ $primary['location'] }}
          </span>
          @endif
          <h2 class="h2" style="font-size:clamp(20px,2.8vw,26px);margin-top:10px">{{ $primary['title'] }}</h2>
          @if ($primary['summary'])
          <p class="muted line-clamp-5" style="margin-top:8px;max-width:40ch">{{ $primary['summary'] }}</p>
          @endif
          <div style="margin-top:16px;display:inline-flex;align-items:center;gap:6px;font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--accent)">
            View event
            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </div>
        </div>
      </a>

      {{-- Secondary (2nd + 3rd) — same loc-style, flex column --}}
      <div style="display:flex;flex-direction:column;gap:20px">
        @foreach (array_slice($featuredEvents, 1, 2) as $ev)
        <a href="{{ $ev['permalink'] }}"
          class="ev-card reveal"
          style="position:relative;border-radius:14px;overflow:hidden;flex:1;border:1px solid var(--line);display:block;text-decoration:none">
          @if ($ev['cover'])
          <img src="{{ $ev['cover'] }}" alt="{{ esc_attr($ev['title']) }}"
            style="width:100%;height:100%;object-fit:cover;filter:brightness(.65);transition:transform .4s ease"
            loading="lazy">
          @endif
          <div style="position:absolute;inset:0;padding:24px;display:flex;flex-direction:column;justify-content:flex-end;background:linear-gradient(180deg,transparent 30%,rgba(6,13,31,.92))">
            @if ($ev['dateLabel'] || $ev['location'])
            <span class="eyebrow">
              {{ $ev['dateLabel'] }}
              @if ($ev['dateLabel'] && $ev['location'])
              &nbsp;·&nbsp;
              @endif
              {{ $ev['location'] }}
            </span>
            @endif
            <h3 class="h3" style="margin-top:8px;font-size:clamp(17px,1.8vw,22px)">{{ $ev['title'] }}</h3>
            <div style="margin-top:12px;display:inline-flex;align-items:center;gap:6px;font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--accent)">
              View event
              <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </div>
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
        <span class="eyebrow"
          data-edit-field="events_all_eyebrow"
          data-edit-type="text"
          data-edit-label="All Events — Eyebrow">{{ $eventsSections['all']['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="events_all_heading_lead"
            data-edit-type="text"
            data-edit-label="All Events — Heading Lead">{{ $eventsSections['all']['headingLead'] }}</span>
          @if ($eventsSections['all']['headingEm'])
          <em
            data-edit-field="events_all_heading_em"
            data-edit-type="text"
            data-edit-label="All Events — Heading Emphasis">{{ $eventsSections['all']['headingEm'] }}</em>
          @endif
        </h2>
      </div>
    </div>

    <div class="ev-grid stagger-children"
      data-edit-admin="edit.php?post_type=company_event"
      title="Click to manage events in WP Admin"
      style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:48px">
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
        @else
        <div style="aspect-ratio:16/9;overflow:hidden;background:#000;flex-shrink:0">
          <img src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/logo.jpeg') }}" alt="{{ esc_attr($ev['title']) }}"
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
          <h3 class="h3 line-clamp-1" style="margin:0;font-size:clamp(18px,1.8vw,22px)">{{ $ev['title'] }}</h3>
          @if ($ev['summary'])
          <p class="line-clamp-3" style="color:var(--ink-2);font-size:14px;line-height:1.6;margin:0">{{ $ev['summary'] }}</p>
          @endif
          <div style="margin-top:auto;padding-top:8px;display:inline-flex;align-items:center;gap:6px;font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--accent)">
            View event
            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
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
      </a>
      @endif
    </div>
  </div>
</section>
@endif

<style>
  @media (max-width: 860px) {
    .ev-featured {
      grid-template-columns: 1fr !important
    }
  }

  @media (max-width: 720px) {
    .ev-grid {
      grid-template-columns: repeat(2, 1fr) !important
    }
  }

  @media (max-width: 480px) {
    .ev-grid {
      grid-template-columns: 1fr !important
    }
  }

  .ev-card:hover {
    border-color: var(--line-2) !important
  }

  .ev-card:hover img {
    transform: scale(1.04)
  }
</style>

@endsection