@extends('layouts.app')

@section('content')
@php($p = $blogPost ?? null)

@if (! $p)
<section class="section" style="padding-top:140px">
  <div class="container">
    <h1 class="display">Post not found.</h1>
    <p class="lead" style="margin-top:18px">
      <a href="{{ $blogUrl }}" class="btn">← Back to blog</a>
    </p>
  </div>
</section>
@else

{{-- Hero --}}
<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-260px;top:-40px;opacity:.18" />

    <a href="{{ $blogUrl }}"
      style="display:inline-flex;align-items:center;gap:8px;font-family:var(--font-mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3);margin-bottom:36px">
      <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M12 7H2M6 3L2 7l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      All posts
    </a>

    <div class="blog-detail__meta" style="margin-bottom:18px">
      @foreach ($p['categories'] as $cat)
      <a href="{{ $cat['url'] }}" class="blog-detail__cat">{{ $cat['name'] }}</a>
      @if (! $loop->last)
      <span class="blog-detail__sep">·</span>
      @endif
      @endforeach

      @if (! empty($p['categories']))
      <span class="blog-detail__sep">·</span>
      @endif

      <time datetime="{{ $p['date'] }}">{{ $p['dateFormatted'] }}</time>
      <span class="blog-detail__sep">·</span>
      <span>{{ $p['readTime'] }} min read</span>
    </div>

    <h1 class="display" style="max-width:24ch">{{ $p['title'] }}</h1>

    @if ($p['excerpt'])
    <p class="lead" style="margin-top:22px;max-width:64ch;color:var(--ink-2)">{{ $p['excerpt'] }}</p>
    @endif
  </div>
</section>

{{-- Featured image --}}
@if ($p['thumbnail'])
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="width:100%;aspect-ratio:16/9;border-radius:14px;overflow:hidden;border:1px solid var(--line);background:var(--bg-3)">
      <img src="{{ $p['thumbnail'] }}" alt="{{ esc_attr($p['title']) }}"
        style="width:100%;height:100%;object-fit:cover;display:block">
    </div>
  </div>
</section>
@endif

{{-- Article body --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container">
    <div class="post-body-grid">

      <article class="post-content">
        @php(the_content())
      </article>

      <aside class="post-sidebar">
        @if (! empty($p['categories']))
        <div style="margin-bottom:32px">
          <div style="font-family:var(--font-mono);font-size:10px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3);margin-bottom:12px">Categories</div>
          <div style="display:flex;flex-wrap:wrap;gap:8px">
            @foreach ($p['categories'] as $cat)
            <a href="{{ $cat['url'] }}"
              style="font-family:var(--font-mono);font-size:11px;letter-spacing:.1em;padding:5px 12px;border-radius:999px;border:1px solid var(--line);color:var(--accent);text-decoration:none;transition:border-color .2s">
              {{ $cat['name'] }}
            </a>
            @endforeach
          </div>
        </div>
        @endif

        <div>
          <a href="{{ $blogUrl }}" class="btn">
            <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M12 7H2M6 3L2 7l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back to blog
          </a>
        </div>
      </aside>

    </div>
  </div>
</section>

{{-- Related posts --}}
@if (! empty($relatedPosts))
<section class="section" style="border-top:1px solid var(--line)">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Keep reading</span>
      <h2 class="h2" style="margin-top:14px">More from the blog</h2>
    </div>

    <div class="related-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;margin-top:48px">
      @foreach ($relatedPosts as $rel)
      <a href="{{ $rel['permalink'] }}"
        class="related-card"
        style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;text-decoration:none">

        @if ($rel['thumbnail'])
        <div style="aspect-ratio:16/9;overflow:hidden;background:var(--bg-3);flex-shrink:0">
          <img src="{{ $rel['thumbnail'] }}" alt="{{ esc_attr($rel['title']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
            loading="lazy">
        </div>
        @else
        <div style="aspect-ratio:16/9;background:var(--bg-3);flex-shrink:0"></div>
        @endif

        <div style="padding:22px;flex:1;display:flex;flex-direction:column;gap:10px">
          <div class="blog-detail__meta">
            @foreach ($rel['categories'] as $cat)
            <a href="{{ $cat['url'] }}" class="blog-detail__cat">{{ $cat['name'] }}</a>
            @if (! $loop->last)
            <span class="blog-detail__sep">·</span>
            @endif
            @endforeach

            @if (! empty($rel['categories']))
            <span class="blog-detail__sep">·</span>
            @endif

            <time datetime="{{ $rel['date'] }}">{{ $rel['dateFormatted'] }}</time>
          </div>

          <h3 style="font-family:var(--font-display);font-size:16px;font-weight:700;line-height:1.35;color:var(--ink);margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
            {{ $rel['title'] }}
          </h3>

          <span class="btn btn-ghost" style="margin-top:auto;padding-left:0;font-size:13px">
            Read article
            <svg class="arr" width="13" height="13" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
        </div>

      </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<style>
  .post-body-grid {
    display: grid;
    grid-template-columns: 1fr 240px;
    gap: 56px;
    align-items: start;
  }

  .post-sidebar {
    position: sticky;
    top: 100px;
  }

  .post-content {
    color: var(--ink-2);
    font-size: 16px;
    line-height: 1.8;
    max-width: 72ch;
  }

  .post-content h2,
  .post-content h3,
  .post-content h4 {
    font-family: var(--font-display);
    color: var(--ink);
    margin-top: 36px;
    margin-bottom: 12px;
  }

  .post-content h2 { font-size: clamp(20px, 2vw, 26px); }
  .post-content h3 { font-size: clamp(17px, 1.6vw, 21px); }

  .post-content p { margin-bottom: 20px; }

  .post-content a {
    color: var(--accent);
    text-decoration: underline;
  }

  .post-content ul,
  .post-content ol {
    padding-left: 22px;
    margin-bottom: 20px;
  }

  .post-content li { margin-bottom: 6px; }

  .post-content blockquote {
    border-left: 2px solid var(--accent);
    padding-left: 20px;
    margin: 28px 0;
    font-family: var(--font-display);
    font-style: italic;
    font-size: clamp(17px, 1.6vw, 21px);
    color: var(--ink);
    line-height: 1.5;
  }

  .post-content img {
    width: 100%;
    border-radius: 10px;
    margin: 20px 0;
  }

  .post-content pre {
    background: var(--bg-3);
    border: 1px solid var(--line);
    border-radius: 8px;
    padding: 16px 20px;
    overflow-x: auto;
    font-size: 13.5px;
    margin-bottom: 20px;
  }

  .related-card:hover { border-color: var(--line-2) !important; }
  .related-card:hover img { transform: scale(1.04); }

  @media (max-width: 860px) {
    .post-body-grid {
      grid-template-columns: 1fr;
    }
    .post-sidebar {
      position: static;
      order: -1;
    }
    .related-grid {
      grid-template-columns: repeat(2, 1fr) !important;
    }
  }

  @media (max-width: 540px) {
    .related-grid {
      grid-template-columns: 1fr !important;
    }
  }
</style>

@endif
@endsection
