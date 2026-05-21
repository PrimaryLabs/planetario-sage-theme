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

{{-- Post grid --}}
<section class="section" style="padding-top:88px">
  <div class="container">

    @if (empty($blogPosts))
    <div style="text-align:center;padding:80px 0;color:var(--ink-2)">
      <p style="font-size:17px">No posts yet — check back soon.</p>
    </div>
    @else

    <div class="blog-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:32px">
      @foreach ($blogPosts as $post)
      <article class="blog-card reveal" style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;transition-delay:{{ $loop->index * 0.06 }}s">

        @if ($post['thumbnail'])
        <a href="{{ $post['permalink'] }}" tabindex="-1" aria-hidden="true" style="display:block;aspect-ratio:16/9;overflow:hidden;background:var(--bg-3)">
          <img src="{{ $post['thumbnail'] }}"
            alt="{{ esc_attr($post['title']) }}"
            loading="lazy"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease">
        </a>
        @endif

        <div style="padding:28px;display:flex;flex-direction:column;gap:12px;flex:1">

          <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            @foreach ($post['categories'] as $cat)
            <a href="{{ $cat['url'] }}"
              style="font-family:var(--font-mono);font-size:10px;letter-spacing:.18em;text-transform:uppercase;color:var(--accent);text-decoration:none">
              {{ $cat['name'] }}
            </a>
            @endforeach

            @if (!empty($post['categories']))
            <span style="color:var(--line);font-size:11px">·</span>
            @endif

            <time datetime="{{ $post['date'] }}"
              style="font-family:var(--font-mono);font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--ink-3)">
              {{ $post['dateFormatted'] }}
            </time>
          </div>

          <h2 style="font-family:var(--font-display);font-size:clamp(18px,1.6vw,22px);font-weight:600;line-height:1.3;margin:0">
            <a href="{{ $post['permalink'] }}" style="color:inherit;text-decoration:none">
              {{ $post['title'] }}
            </a>
          </h2>

          @if ($post['excerpt'])
          <p style="color:var(--ink-2);font-size:14.5px;line-height:1.65;margin:0;flex:1">
            {{ $post['excerpt'] }}
          </p>
          @endif

          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:8px">
            <span style="font-family:var(--font-mono);font-size:10px;letter-spacing:.12em;text-transform:uppercase;color:var(--ink-3)">
              {{ $post['readTime'] }} min read
            </span>
            <a href="{{ $post['permalink'] }}" class="btn" style="font-size:13px;padding:8px 14px;gap:6px">
              Read
              <svg class="arr" width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </a>
          </div>

        </div>
      </article>
      @endforeach
    </div>

    @if ($blogHasMore)
    <nav class="blog-pagination" aria-label="Blog pages"
      style="display:flex;justify-content:center;gap:8px;margin-top:56px;flex-wrap:wrap">
      @foreach ($blogPagination as $pageLink)
      {!! $pageLink !!}
      @endforeach
    </nav>
    @endif

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
  @media (max-width: 900px) {
    .blog-grid {
      grid-template-columns: repeat(2, 1fr) !important;
    }
  }

  @media (max-width: 560px) {
    .blog-grid {
      grid-template-columns: 1fr !important;
    }
  }

  .blog-card:hover img {
    transform: scale(1.04);
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
</style>

@endsection
