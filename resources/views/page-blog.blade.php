@extends('layouts.app')

@section('content')

{{-- Compact hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
  <div class="container flex flex-col items-center justify-center text-center">
    @if ($pageIntro['eyebrow'])
    <span class="eyebrow-center">{{ $pageIntro['eyebrow'] }}</span>
    @endif

    <h1 class="display" style="margin-top:18px;max-width:22ch">
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

{{-- Blog reader: detail + list --}}
<section class="section" style="padding-top:88px">
  <div class="container">

    @if (empty($blogPosts))
    <div style="text-align:center;padding:80px 0;color:var(--ink-2)">
      <p style="font-size:17px">No posts yet — check back soon.</p>
    </div>
    @else

    <script>window.__blogPosts = @json($blogPosts);</script>

    <div class="blog-reader">

      {{-- Detail panel (server-rendered with first post) --}}
      @php $first = $blogPosts[0]; @endphp
      <div class="blog-reader__detail">

        @if ($first['thumbnail'])
        <img id="blog-detail-img"
          src="{{ $first['thumbnail'] }}"
          alt="{{ esc_attr($first['title']) }}"
          class="blog-detail__img">
        @else
        <img id="blog-detail-img"
          src=""
          alt=""
          class="blog-detail__img"
          style="display:none">
        @endif

        <div id="blog-detail-meta" class="blog-detail__meta">
          @foreach ($first['categories'] as $cat)
          <a href="{{ $cat['url'] }}" class="blog-detail__cat">{{ $cat['name'] }}</a>
          @if (!$loop->last)
          <span class="blog-detail__sep">·</span>
          @endif
          @endforeach

          @if (!empty($first['categories']))
          <span class="blog-detail__sep">·</span>
          @endif

          <time datetime="{{ $first['date'] }}">{{ $first['dateFormatted'] }}</time>
          <span class="blog-detail__sep">·</span>
          <span>{{ $first['readTime'] }} min read</span>
        </div>

        <h2 style="font-family:var(--font-display);font-size:clamp(22px,2.4vw,34px);font-weight:700;line-height:1.25;margin:0 0 16px">
          <a id="blog-detail-title-link" href="{{ $first['permalink'] }}" style="color:inherit;text-decoration:none">
            <span id="blog-detail-title">{{ $first['title'] }}</span>
          </a>
        </h2>

        <p id="blog-detail-body" style="color:var(--ink-2);font-size:15.5px;line-height:1.75;margin:0 0 28px">
          {{ $first['bodyPreview'] ?: $first['excerpt'] }}
        </p>

        <a id="blog-detail-link" href="{{ $first['permalink'] }}" class="btn btn-primary">
          Read full article
          <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
            <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </a>

      </div>

      {{-- Post list --}}
      <div class="blog-reader__list">
        @foreach ($blogPosts as $post)
        <div class="blog-list-item{{ $loop->first ? ' is-active' : '' }}"
          data-id="{{ $post['id'] }}"
          role="button"
          tabindex="0"
          aria-pressed="{{ $loop->first ? 'true' : 'false' }}">

          @if ($post['thumbnail'])
          <img src="{{ $post['thumbnail'] }}"
            alt=""
            loading="lazy"
            style="width:80px;height:60px;object-fit:cover;border-radius:8px;display:block;flex-shrink:0">
          @else
          <div style="width:80px;height:60px;border-radius:8px;background:var(--bg-3);flex-shrink:0"></div>
          @endif

          <div style="min-width:0">
            <h3 style="font-family:var(--font-display);font-size:14px;font-weight:600;line-height:1.35;margin:0 0 6px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">
              {{ $post['title'] }}
            </h3>
            <time datetime="{{ $post['date'] }}"
              style="font-family:var(--font-mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-3)">
              {{ $post['dateFormatted'] }}
            </time>
          </div>

        </div>
        @endforeach

        @if ($blogHasMore)
        <nav class="blog-pagination" aria-label="Blog pages"
          style="display:flex;justify-content:center;gap:8px;margin-top:20px;flex-wrap:wrap;padding:0 4px">
          @foreach ($blogPagination as $pageLink)
          {!! $pageLink !!}
          @endforeach
        </nav>
        @endif
      </div>

    </div>

    @endif

  </div>
</section>

{{-- Closing CTA --}}
@if ($pageClosing['primaryLabel'] || $pageClosing['headlineLead'])
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
@endif

<style>
  .blog-reader {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 56px;
    align-items: start;
  }

  .blog-reader__detail {
    position: sticky;
    top: 100px;
  }

  .blog-detail__img {
    width: 100%;
    aspect-ratio: 16 / 9;
    object-fit: cover;
    border-radius: 14px;
    display: block;
    margin-bottom: 24px;
  }

  .blog-detail__meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 6px;
    margin-bottom: 16px;
    font-family: var(--font-mono);
    font-size: 10px;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--ink-3);
  }

  .blog-detail__cat {
    color: var(--accent);
    text-decoration: none;
  }

  .blog-detail__sep {
    color: var(--line);
  }

  .blog-reader__list {
    max-height: 680px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 4px;
    scrollbar-width: thin;
    scrollbar-color: var(--line) transparent;
  }

  .blog-list-item {
    display: grid;
    grid-template-columns: 80px 1fr;
    gap: 14px;
    padding: 14px 16px;
    border-radius: 10px;
    border-left: 3px solid transparent;
    cursor: pointer;
    transition: background .2s, border-color .2s;
    align-items: center;
  }

  .blog-list-item:hover {
    background: var(--bg-2);
  }

  .blog-list-item.is-active {
    background: var(--bg-2);
    border-left-color: var(--accent);
  }

  .blog-list-item:focus-visible {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
  }

  .blog-pagination a,
  .blog-pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 10px;
    border-radius: 8px;
    border: 1px solid var(--line);
    font-family: var(--font-mono);
    font-size: 12px;
    letter-spacing: .08em;
    color: var(--ink-2);
    text-decoration: none;
    background: var(--bg);
    transition: border-color .2s, color .2s, background .2s;
  }

  .blog-pagination a:hover {
    border-color: var(--accent);
    color: var(--accent);
  }

  .blog-pagination .current {
    background: var(--accent);
    border-color: var(--accent);
    color: #fff;
  }

  @media (max-width: 900px) {
    .blog-reader {
      grid-template-columns: 1fr;
    }

    .blog-reader__detail {
      position: static;
    }

    .blog-reader__list {
      max-height: 400px;
    }
  }
</style>

@endsection
