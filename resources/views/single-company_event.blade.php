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
        <path d="M12 7H2M6 3L2 7l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
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

    <h2 class="h2">{{ $ev['title'] }}</h2>
    @if ($ev['summary'])
    <p class="lead" style="margin-top:22px;max-width:100ch">{{ $ev['summary'] }}</p>
    @endif
  </div>
</section>

{{-- Media Gallery --}}
@if (! empty($ev['gallery']))
<section class="section" style="padding-top:0">
  <div class="container">

    @php($galleryItems = $ev['gallery'])
    @php($galleryCount = count($galleryItems))

    {{-- Gallery grid: slider + thumbnails --}}
    <div class="ev-sl-gallery" style="display:grid;gap:10px">

      {{-- Slide viewport (first item on load) --}}
      @php($hero = $galleryItems[0])
      <div style="position:relative;width:100%;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:#000">
        <div id="ev-slide-media" style="position:relative;width:100%;height:100%">
          @if ($hero['kind'] === 'image')
          <img src="{{ $hero['url'] }}" alt="{{ esc_attr($hero['alt'] ?: $ev['title']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block">
          @else
          <button type="button" class="ev-slide-play" aria-label="Play video"
            style="position:relative;display:block;width:100%;height:100%;padding:0;border:0;cursor:pointer;background:#000">
            @if ($hero['poster'])
            <img src="{{ $hero['poster'] }}" alt="{{ esc_attr($hero['alt'] ?: $ev['title']) }}"
              style="width:100%;height:100%;object-fit:cover;display:block">
            @else
            <div style="width:100%;height:100%;background:var(--bg-3)"></div>
            @endif
            <span class="ev-play-badge" aria-hidden="true">
              <svg width="28" height="28" viewBox="0 0 48 48" fill="none">
                <circle cx="24" cy="24" r="23" stroke="var(--line-2)" stroke-width="2" />
                <polygon points="19,15 37,24 19,33" fill="var(--accent)" />
              </svg>
            </span>
          </button>
          @endif
        </div>

        @if ($galleryCount > 1)
        <div id="ev-slide-counter"
          style="position:absolute;top:16px;left:50%;transform:translateX(-50%);font-family:var(--font-mono);font-size:11px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3);background:rgba(6,13,31,.55);padding:4px 10px;border-radius:20px;pointer-events:none">
          1 / {{ $galleryCount }}
        </div>

        <button id="ev-slide-prev" type="button" aria-label="Previous"
          style="position:absolute;left:12px;top:50%;transform:translateY(-50%);background:var(--bg-2);border:1px solid var(--line);border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;color:var(--ink);cursor:pointer">
          <svg width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M9 2L4 7l5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>

        <button id="ev-slide-next" type="button" aria-label="Next"
          style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:var(--bg-2);border:1px solid var(--line);border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;color:var(--ink);cursor:pointer">
          <svg width="15" height="15" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M5 2l5 5-5 5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
        @endif
      </div>

      {{-- Thumbnail strip (remaining items) --}}
      @if ($galleryCount > 1)
      <div class="ev-thumbs grid grid-cols-3 lg:grid-cols-8 gap-3">
        @foreach ($galleryItems as $i => $item)
        <button type="button"
          class="ev-gal-thumb {{ $i === 0 ? 'ev-thumb--active' : '' }}"
          data-ev-idx="{{ $i }}"
          aria-label="View media {{ $i + 1 }}"
          style="position:relative;aspect-ratio:4/2.5;border-radius:8px;overflow:hidden;border:1px solid {{ $i === 0 ? 'var(--accent)' : 'var(--line)' }};background:#000;cursor:pointer;padding:0;transition:border-color .2s">
          @if ($item['kind'] === 'youtube' && $item['poster'])
          <img src="{{ $item['poster'] }}" alt=""
            style="width:100%;height:100%;object-fit:cover;display:block">
          @elseif ($item['kind'] === 'youtube')
          <div style="width:100%;height:100%;background:var(--bg-3);display:flex;align-items:center;justify-content:center">
            <svg width="20" height="20" viewBox="0 0 48 48" fill="none" aria-hidden="true">
              <polygon points="14,10 42,24 14,38" fill="var(--accent)" />
            </svg>
          </div>
          @elseif ($item['kind'] === 'video' && $item['poster'])
          <img src="{{ $item['poster'] }}" alt=""
            style="width:100%;height:100%;object-fit:cover;display:block">
          @elseif ($item['kind'] === 'video')
          <div style="width:100%;height:100%;background:var(--bg-3);display:flex;align-items:center;justify-content:center">
            <svg width="20" height="20" viewBox="0 0 48 48" fill="none" aria-hidden="true">
              <polygon points="14,10 42,24 14,38" fill="var(--accent)" />
            </svg>
          </div>
          @else
          <img src="{{ $item['url'] }}" alt=""
            style="width:100%;height:100%;object-fit:cover;display:block">
          @endif

          @if ($item['kind'] !== 'image')
          <div class="ev-play-badge ev-play-badge--sm" aria-hidden="true">
            <svg width="12" height="12" viewBox="0 0 48 48" fill="none">
              <polygon points="14,10 42,24 14,38" fill="currentColor" />
            </svg>
          </div>
          @endif
        </button>
        @endforeach
      </div>
      @endif
    </div>

    {{-- Gallery caption --}}
    <div style="margin-top:14px;min-height:1.2em;display:flex;flex-direction:column;gap:3px">
      <span id="ev-caption-title"
        style="font-size:13.5px;font-weight:600;color:var(--ink);display:block">
        {{ $galleryItems[0]['title'] ?? '' }}
      </span>
      <span id="ev-caption-desc"
        style="font-size:13px;color:var(--ink-3);display:block">
        {{ $galleryItems[0]['description'] ?? '' }}
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

{{-- Gallery data island (avoids PHP-in-JS) --}}
@if (! empty($ev['gallery']))
<script type="application/json" id="ev-gallery-data">{!! json_encode(array_values($ev['gallery']), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>

<script>
  (function() {
    var slideEl = document.getElementById('ev-slide-media');
    var counterEl = document.getElementById('ev-slide-counter');
    var capTitle = document.getElementById('ev-caption-title');
    var capDesc = document.getElementById('ev-caption-desc');
    var prevBtn = document.getElementById('ev-slide-prev');
    var nextBtn = document.getElementById('ev-slide-next');
    var items = JSON.parse(document.getElementById('ev-gallery-data').textContent);
    var thumbs = document.querySelectorAll('.ev-gal-thumb');
    var cur = 0;

    function posterHtml(item) {
      var poster = item.poster ?
        '<img src="' + item.poster + '" alt="' + (item.alt || '') + '" style="width:100%;height:100%;object-fit:cover;display:block">' :
        '<div style="width:100%;height:100%;background:var(--bg-3)"></div>';
      return '<button type="button" class="ev-slide-play" aria-label="Play video"' +
        ' style="position:relative;display:block;width:100%;height:100%;padding:0;border:0;cursor:pointer;background:#000">' +
        poster +
        '<span class="ev-play-badge" aria-hidden="true">' +
        '<svg width="28" height="28" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="23" stroke="var(--line-2)" stroke-width="2"/><polygon points="19,15 37,24 19,33" fill="var(--accent)"/></svg>' +
        '</span></button>';
    }

    function playerHtml(item) {
      if (item.kind === 'youtube') {
        return '<iframe src="' + item.embed + '?autoplay=1" allow="autoplay;encrypted-media" allowfullscreen' +
          ' style="width:100%;height:100%;border:0"></iframe>';
      }
      return '<video controls autoplay playsinline preload="metadata"' +
        (item.poster ? ' poster="' + item.poster + '"' : '') +
        ' style="width:100%;height:100%;object-fit:contain;background:#000">' +
        '<source src="' + item.url + '" type="' + item.mime + '"></video>';
    }

    function setThumbActive(idx) {
      thumbs.forEach(function(t, i) {
        t.style.borderColor = i === idx ? 'var(--accent)' : 'var(--line)';
        t.classList.toggle('ev-thumb--active', i === idx);
      });
    }

    function render(idx) {
      cur = (idx + items.length) % items.length;
      var item = items[cur];
      slideEl.innerHTML = item.kind === 'image' ?
        '<img src="' + item.url + '" alt="' + (item.alt || '') + '" style="width:100%;height:100%;object-fit:cover;display:block">' :
        posterHtml(item);
      if (counterEl) counterEl.textContent = (cur + 1) + ' / ' + items.length;
      if (capTitle) capTitle.textContent = item.title || '';
      if (capDesc) capDesc.textContent = item.description || '';
      setThumbActive(cur);
    }

    slideEl.addEventListener('click', function(e) {
      if (!e.target.closest('.ev-slide-play')) return;
      slideEl.innerHTML = playerHtml(items[cur]);
    });

    thumbs.forEach(function(el) {
      el.addEventListener('click', function() {
        render(parseInt(el.dataset.evIdx, 10));
      });
    });

    if (prevBtn) prevBtn.addEventListener('click', function() {
      render(cur - 1);
    });
    if (nextBtn) nextBtn.addEventListener('click', function() {
      render(cur + 1);
    });
  }());
</script>
@endif

<style>
  @media (max-width: 860px) {
    .ev-detail-grid {
      grid-template-columns: 1fr !important
    }
  }

  @media (max-width: 640px) {
    .ev-thumbs {
      grid-template-columns: repeat(4, 1fr) !important
    }
  }

  @media (max-width: 420px) {
    .ev-thumbs {
      grid-template-columns: repeat(3, 1fr) !important
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

  .ev-play-badge {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(6, 13, 31, .45);
    color: var(--accent);
  }

  .ev-play-badge--sm svg {
    width: 16px;
    height: 16px
  }

  .ev-content h2,
  .ev-content h3,
  .ev-content h4 {
    font-family: var(--font-display);
    color: var(--ink);
    margin-top: 28px;
    margin-bottom: 10px;
  }

  .ev-content a {
    color: var(--accent);
    text-decoration: underline !important
  }

  .ev-content ul,
  .ev-content ol {
    padding-left: 20px;
    margin-top: 12px
  }

  .ev-content li {
    margin-bottom: 6px
  }
</style>

@endif
@endsection