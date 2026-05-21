@extends('layouts.app')

@section('content')
@php($ev = $event)

@if (! $ev)
<section class="section" style="padding-top:140px">
  <div class="container">
    <h1 class="display">Event not found.</h1>
    <p class="lead" style="margin-top:18px">
      <a href="{{ home_url('/events') }}" class="btn">Back to events</a>
    </p>
  </div>
</section>
@else

{{-- Back nav + title --}}
<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-260px;top:-40px;opacity:.18" />

    <a href="{{ home_url('/events') }}"
      style="display:inline-flex;align-items:center;gap:8px;font-family:var(--font-mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3);margin-bottom:36px">
      <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M12 7H2M6 3L2 7l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      All events
    </a>

    <div class="tag-row" style="margin-bottom:18px">
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

    <h1 class="display" style="max-width:22ch">{{ $ev['title'] }}</h1>

    @if ($ev['summary'])
    <p class="lead" style="margin-top:22px;max-width:64ch">{{ $ev['summary'] }}</p>
    @endif
  </div>
</section>

{{-- Media Gallery --}}
@if (! empty($ev['gallery']))
<section class="section" style="padding-top:0">
  <div class="container">

    @php($galleryItems = $ev['gallery'])
    @php($galleryCount = count($galleryItems))

    {{-- Gallery grid: hero + thumbnails --}}
    <div class="ev-sl-gallery" style="display:grid;gap:10px">

      {{-- Hero item (first) --}}
      @php($hero = $galleryItems[0])
      <button type="button"
        class="ev-gal-thumb ev-gal-hero"
        data-ev-idx="0"
        aria-label="Open gallery"
        style="position:relative;width:100%;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:#000;cursor:zoom-in;padding:0">
        @if ($hero['kind'] === 'youtube' && $hero['poster'])
        <img src="{{ $hero['poster'] }}" alt="{{ esc_attr($hero['alt'] ?: $ev['title']) }}"
          style="width:100%;height:100%;object-fit:cover;display:block">
        @elseif ($hero['kind'] === 'youtube')
        <div style="width:100%;height:100%;background:var(--bg-3);display:flex;align-items:center;justify-content:center">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true">
            <circle cx="24" cy="24" r="23" stroke="var(--line-2)" stroke-width="2"/>
            <polygon points="19,15 37,24 19,33" fill="var(--accent)"/>
          </svg>
        </div>
        @elseif ($hero['kind'] === 'video' && $hero['poster'])
        <img src="{{ $hero['poster'] }}" alt="{{ esc_attr($hero['alt'] ?: $ev['title']) }}"
          style="width:100%;height:100%;object-fit:cover;display:block">
        @elseif ($hero['kind'] === 'video')
        <div style="width:100%;height:100%;background:var(--bg-3);display:flex;align-items:center;justify-content:center">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" aria-hidden="true">
            <circle cx="24" cy="24" r="23" stroke="var(--line-2)" stroke-width="2"/>
            <polygon points="19,15 37,24 19,33" fill="var(--accent)"/>
          </svg>
        </div>
        @else
        <img src="{{ $hero['url'] }}" alt="{{ esc_attr($hero['alt'] ?: $ev['title']) }}"
          style="width:100%;height:100%;object-fit:cover;display:block">
        @endif

        @if ($hero['kind'] !== 'image')
        <div class="ev-play-badge" aria-hidden="true">
          <svg width="20" height="20" viewBox="0 0 48 48" fill="none">
            <polygon points="14,10 42,24 14,38" fill="currentColor"/>
          </svg>
        </div>
        @endif
      </button>

      {{-- Thumbnail strip (remaining items) --}}
      @if ($galleryCount > 1)
      <div class="ev-thumbs" style="display:grid;grid-template-columns:repeat(5,1fr);gap:10px">
        @foreach ($galleryItems as $i => $item)
        <button type="button"
          class="ev-gal-thumb {{ $i === 0 ? 'ev-thumb--active' : '' }}"
          data-ev-idx="{{ $i }}"
          aria-label="View media {{ $i + 1 }}"
          style="position:relative;aspect-ratio:1;border-radius:8px;overflow:hidden;border:1px solid {{ $i === 0 ? 'var(--accent)' : 'var(--line)' }};background:#000;cursor:pointer;padding:0;transition:border-color .2s">
          @if ($item['kind'] === 'youtube' && $item['poster'])
          <img src="{{ $item['poster'] }}" alt=""
            style="width:100%;height:100%;object-fit:cover;display:block">
          @elseif ($item['kind'] === 'youtube')
          <div style="width:100%;height:100%;background:var(--bg-3);display:flex;align-items:center;justify-content:center">
            <svg width="20" height="20" viewBox="0 0 48 48" fill="none" aria-hidden="true">
              <polygon points="14,10 42,24 14,38" fill="var(--accent)"/>
            </svg>
          </div>
          @elseif ($item['kind'] === 'video' && $item['poster'])
          <img src="{{ $item['poster'] }}" alt=""
            style="width:100%;height:100%;object-fit:cover;display:block">
          @elseif ($item['kind'] === 'video')
          <div style="width:100%;height:100%;background:var(--bg-3);display:flex;align-items:center;justify-content:center">
            <svg width="20" height="20" viewBox="0 0 48 48" fill="none" aria-hidden="true">
              <polygon points="14,10 42,24 14,38" fill="var(--accent)"/>
            </svg>
          </div>
          @else
          <img src="{{ $item['url'] }}" alt=""
            style="width:100%;height:100%;object-fit:cover;display:block">
          @endif

          @if ($item['kind'] !== 'image')
          <div class="ev-play-badge ev-play-badge--sm" aria-hidden="true">
            <svg width="12" height="12" viewBox="0 0 48 48" fill="none">
              <polygon points="14,10 42,24 14,38" fill="currentColor"/>
            </svg>
          </div>
          @endif
        </button>
        @endforeach
      </div>
      @endif
    </div>

    {{-- Gallery caption / count --}}
    <div style="margin-top:14px;display:flex;align-items:start;justify-content:space-between;gap:16px">
      <div style="min-height:1.2em;display:flex;flex-direction:column;gap:3px">
        <span id="ev-caption-title"
          style="font-size:13.5px;font-weight:600;color:var(--ink);display:block">
          {{ $galleryItems[0]['title'] ?? '' }}
        </span>
        <span id="ev-caption-desc"
          style="font-size:13px;color:var(--ink-3);display:block">
          {{ $galleryItems[0]['description'] ?? '' }}
        </span>
      </div>
      <span style="font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--ink-3);white-space:nowrap;padding-top:3px">
        1 / {{ $galleryCount }}
      </span>
    </div>
  </div>
</section>
@endif

{{-- Event details --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container">
    <div class="ev-detail-grid" style="display:grid;grid-template-columns:280px 1fr;gap:56px;align-items:start">

      {{-- Meta sidebar --}}
      <div style="display:flex;flex-direction:column;gap:28px">
        @if ($ev['dateLabel'])
        <div>
          <div class="eyebrow" style="margin-bottom:10px">Date</div>
          <p style="font-family:var(--font-display);font-size:18px;color:var(--ink)">{{ $ev['dateLabel'] }}</p>
        </div>
        @endif

        @if ($ev['location'])
        <div>
          <div class="eyebrow" style="margin-bottom:10px">Location</div>
          <p style="font-size:15px;color:var(--ink-2);line-height:1.5">{{ $ev['location'] }}</p>
        </div>
        @endif

        <div style="margin-top:8px">
          <a href="{{ home_url('/events') }}" class="btn">
            ← Back to events
          </a>
        </div>
      </div>

      {{-- Content --}}
      <div>
        @if ($ev['summary'])
        <p class="lead" style="font-size:17px;line-height:1.65;border-left:2px solid var(--accent);padding-left:20px;color:var(--ink)">
          {{ $ev['summary'] }}
        </p>
        @endif

        @php($content = get_post_field('post_content', $ev['id']))
        @if ($content)
        <div class="ev-content" style="margin-top:32px;color:var(--ink-2);line-height:1.75;font-size:15px">
          {!! wp_kses_post(wpautop($content)) !!}
        </div>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- Related Events --}}
@if (! empty($relatedEvents))
<section class="section" style="border-top:1px solid var(--line)">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">More events</span>
      <h2 class="h2" style="margin-top:14px">From the <em>calendar.</em></h2>
    </div>
    <div class="ev-grid stagger-children" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:48px">
      @foreach ($relatedEvents as $rel)
      <a href="{{ $rel['permalink'] }}"
        class="ev-card reveal"
        style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;text-decoration:none">
        @if ($rel['cover'])
        <div style="aspect-ratio:16/9;overflow:hidden;background:#000;flex-shrink:0">
          <img src="{{ $rel['cover'] }}" alt="{{ esc_attr($rel['title']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
            loading="lazy">
        </div>
        @endif
        <div style="padding:22px;flex:1;display:flex;flex-direction:column;gap:10px">
          <div class="tag-row">
            @if ($rel['dateLabel'])
            <span>{{ $rel['dateLabel'] }}</span>
            @endif
            @if ($rel['dateLabel'] && $rel['location'])
            <span class="sep">·</span>
            @endif
            @if ($rel['location'])
            <span style="color:var(--accent)">{{ $rel['location'] }}</span>
            @endif
          </div>
          <h3 class="h3" style="margin:0;font-size:clamp(17px,1.6vw,20px)">{{ $rel['title'] }}</h3>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Lightbox dialog --}}
@if (! empty($ev['gallery']))
<dialog id="ev-lb"
  style="position:fixed;inset:0;width:100%;height:100%;max-width:100%;max-height:100%;background:rgba(6,13,31,.96);border:0;padding:0;display:flex;align-items:center;justify-content:center;z-index:1000">
  <div style="position:relative;width:100%;height:100%;display:flex;align-items:center;justify-content:center;padding:60px 20px 80px">

    {{-- Close --}}
    <button id="ev-lb-close" type="button" aria-label="Close gallery"
      style="position:absolute;top:20px;right:20px;background:var(--bg-2);border:1px solid var(--line);border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;color:var(--ink);cursor:pointer;z-index:10">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
        <path d="M2 2l12 12M14 2L2 14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
      </svg>
    </button>

    {{-- Counter --}}
    <div id="ev-lb-counter"
      style="position:absolute;top:24px;left:50%;transform:translateX(-50%);font-family:var(--font-mono);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3)">
    </div>

    {{-- Media container --}}
    <div id="ev-lb-media"
      style="position:relative;max-width:min(1100px,92vw);max-height:80vh;display:flex;align-items:center;justify-content:center">
    </div>

    {{-- Prev --}}
    <button id="ev-lb-prev" type="button" aria-label="Previous"
      style="position:absolute;left:16px;top:50%;transform:translateY(-50%);background:var(--bg-2);border:1px solid var(--line);border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;color:var(--ink);cursor:pointer">
      <svg width="16" height="16" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M9 2L4 7l5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>

    {{-- Next --}}
    <button id="ev-lb-next" type="button" aria-label="Next"
      style="position:absolute;right:16px;top:50%;transform:translateY(-50%);background:var(--bg-2);border:1px solid var(--line);border-radius:50%;width:44px;height:44px;display:flex;align-items:center;justify-content:center;color:var(--ink);cursor:pointer">
      <svg width="16" height="16" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M5 2l5 5-5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>

    {{-- Caption --}}
    <div id="ev-lb-cap"
      style="position:absolute;bottom:24px;left:50%;transform:translateX(-50%);font-size:13px;color:var(--ink-3);text-align:center;max-width:60ch;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
    </div>
  </div>
</dialog>

{{-- Gallery data island (avoids PHP-in-JS) --}}
<script type="application/json" id="ev-gallery-data">
{!! json_encode(array_values($ev['gallery']), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>

<script>
(function () {
  var dialog    = document.getElementById('ev-lb');
  var mediaEl   = document.getElementById('ev-lb-media');
  var capEl     = document.getElementById('ev-lb-cap');
  var counterEl = document.getElementById('ev-lb-counter');
  var capTitle  = document.getElementById('ev-caption-title');
  var capDesc   = document.getElementById('ev-caption-desc');
  var items     = JSON.parse(document.getElementById('ev-gallery-data').textContent);
  var thumbs    = document.querySelectorAll('.ev-gal-thumb');
  var cur       = 0;

  function mediaHtml(item) {
    if (item.kind === 'youtube') {
      return '<div style="position:relative;width:min(900px,90vw);aspect-ratio:16/9">'
        + '<iframe src="' + item.embed + '?autoplay=1" allow="autoplay;encrypted-media" allowfullscreen'
        + ' style="position:absolute;inset:0;width:100%;height:100%;border:0;border-radius:10px"></iframe>'
        + '</div>';
    }
    if (item.kind === 'video') {
      return '<video controls autoplay playsinline preload="metadata"'
        + (item.poster ? ' poster="' + item.poster + '"' : '')
        + ' style="max-width:min(900px,90vw);max-height:78vh;border-radius:10px;background:#000">'
        + '<source src="' + item.url + '" type="' + item.mime + '"></video>';
    }
    return '<img src="' + item.url + '" alt="' + (item.alt || '') + '"'
      + ' style="max-width:min(1000px,90vw);max-height:78vh;border-radius:10px;object-fit:contain;display:block">';
  }

  function setThumbActive(idx) {
    thumbs.forEach(function (t, i) {
      t.style.borderColor = i === idx ? 'var(--accent)' : 'var(--line)';
      t.classList.toggle('ev-thumb--active', i === idx);
    });
  }

  function render(idx) {
    cur = (idx + items.length) % items.length;
    var item = items[cur];
    mediaEl.innerHTML = mediaHtml(item);
    counterEl.textContent = (cur + 1) + ' / ' + items.length;
    capEl.textContent = item.title && item.description
      ? item.title + ' — ' + item.description
      : (item.title || item.description || '');
    if (capTitle) capTitle.textContent = item.title || '';
    if (capDesc)  capDesc.textContent  = item.description || '';
    setThumbActive(cur);
  }

  function openAt(idx) {
    render(idx);
    dialog.showModal();
    dialog.focus();
  }

  function close() {
    mediaEl.innerHTML = '';
    dialog.close();
  }

  thumbs.forEach(function (el) {
    el.addEventListener('click', function () {
      openAt(parseInt(el.dataset.evIdx, 10));
    });
  });

  document.getElementById('ev-lb-prev').addEventListener('click', function () { render(cur - 1); });
  document.getElementById('ev-lb-next').addEventListener('click', function () { render(cur + 1); });
  document.getElementById('ev-lb-close').addEventListener('click', close);

  dialog.addEventListener('click', function (e) {
    if (e.target === dialog) close();
  });

  dialog.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft')  render(cur - 1);
    if (e.key === 'ArrowRight') render(cur + 1);
    if (e.key === 'Escape')     close();
  });
}());
</script>
@endif

<style>
  @media (max-width: 860px) {
    .ev-detail-grid { grid-template-columns: 1fr !important }
  }
  @media (max-width: 640px) {
    .ev-thumbs { grid-template-columns: repeat(4, 1fr) !important }
  }
  @media (max-width: 420px) {
    .ev-thumbs { grid-template-columns: repeat(3, 1fr) !important }
  }
  @media (max-width: 720px) {
    .ev-grid { grid-template-columns: repeat(2, 1fr) !important }
  }
  @media (max-width: 480px) {
    .ev-grid { grid-template-columns: 1fr !important }
  }
  .ev-card:hover { border-color: var(--line-2) !important }
  .ev-card:hover img { transform: scale(1.04) }
  .ev-play-badge {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(6,13,31,.45);
    color: var(--accent);
  }
  .ev-play-badge--sm svg { width: 16px; height: 16px }
  .ev-gal-hero { display: block }
  dialog::backdrop { background: rgba(6,13,31,.92) }
  .ev-content h2, .ev-content h3, .ev-content h4 {
    font-family: var(--font-display);
    color: var(--ink);
    margin-top: 28px;
    margin-bottom: 10px;
  }
  .ev-content a { color: var(--accent); text-decoration: underline !important }
  .ev-content ul, .ev-content ol { padding-left: 20px; margin-top: 12px }
  .ev-content li { margin-bottom: 6px }
</style>

@endif
@endsection
