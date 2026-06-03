@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
  <div class="container flex flex-col items-center justify-center text-center">

    <a href="{{ $blogUrl }}"
      style="display:inline-flex;align-items:center;gap:8px;font-family:var(--font-mono);font-size:10.5px;letter-spacing:.16em;text-transform:uppercase;color:var(--ink-3);margin-bottom:28px">
      <svg width="12" height="12" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M12 7H2M6 3L2 7l4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      All posts
    </a>

    <span class="eyebrow-center">Category</span>

    <h1 class="display" style="margin-top:14px;max-width:22ch">{{ $categoryName }}</h1>

    @if ($categoryDescription)
    <p class="lead" style="margin-top:20px;max-width:60ch;color:var(--ink-2)">{{ $categoryDescription }}</p>
    @endif

    <p style="margin-top:14px;font-family:var(--font-mono);font-size:10.5px;letter-spacing:.14em;text-transform:uppercase;color:var(--ink-3)">
      {{ $categoryCount }} {{ $categoryCount === 1 ? 'post' : 'posts' }}
    </p>

  </div>
</section>

{{-- Post grid --}}
<section class="section" style="padding-top:72px">
  <div class="container">

    @if (empty($archivePosts))
    <div style="text-align:center;padding:80px 0;color:var(--ink-2)">
      <p style="font-size:17px">No posts in this category yet.</p>
      <a href="{{ $blogUrl }}" class="btn" style="margin-top:24px">← Back to blog</a>
    </div>
    @else

    <div class="cat-grid">
      @foreach ($archivePosts as $post)
      <a href="{{ $post['permalink'] }}"
        class="cat-card"
        style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;overflow:hidden;display:flex;flex-direction:column;text-decoration:none">

        @if ($post['thumbnail'])
        <div style="aspect-ratio:16/9;overflow:hidden;background:var(--bg-3);flex-shrink:0">
          <img src="{{ $post['thumbnail'] }}" alt="{{ esc_attr($post['title']) }}"
            style="width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease"
            loading="lazy">
        </div>
        @else
        <div style="aspect-ratio:16/9;background:var(--bg-3);flex-shrink:0"></div>
        @endif

        <div style="padding:22px;flex:1;display:flex;flex-direction:column;gap:10px">

          <div class="blog-detail__meta">
            <time datetime="{{ $post['date'] }}">{{ $post['dateFormatted'] }}</time>
            <span class="blog-detail__sep">·</span>
            <span>{{ $post['readTime'] }} min read</span>
          </div>

          <h2 style="font-family:var(--font-display);font-size:17px;font-weight:700;line-height:1.35;color:var(--ink);margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
            {{ $post['title'] }}
          </h2>

          @if ($post['bodyPreview'])
          <p style="font-size:14px;color:var(--ink-2);line-height:1.65;margin:0;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden">
            {{ $post['bodyPreview'] }}
          </p>
          @endif

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

    @if ($archiveHasMore)
    <nav class="blog-pagination" aria-label="Category pages"
      style="display:flex;justify-content:center;gap:8px;margin-top:48px;flex-wrap:wrap">
      @foreach ($archivePagination as $pageLink)
      {!! $pageLink !!}
      @endforeach
    </nav>
    @endif

    @endif

  </div>
</section>

<style>
  .cat-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
  }

  .cat-card:hover { border-color: var(--line-2) !important; }
  .cat-card:hover img { transform: scale(1.04); }

  @media (max-width: 860px) {
    .cat-grid { grid-template-columns: repeat(2, 1fr); }
  }

  @media (max-width: 540px) {
    .cat-grid { grid-template-columns: 1fr; }
  }
</style>

@endsection
