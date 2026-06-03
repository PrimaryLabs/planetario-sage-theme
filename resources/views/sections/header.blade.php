@php
$logo = get_custom_logo();
$isAdmin = \is_user_logged_in() && \current_user_can('edit_posts');

$navLinksBefore = [
['label' => 'Home', 'url' => home_url('/'), 'class' => 'nav-link' . (is_front_page() ? ' active' : '')],
['label' => 'Properties', 'url' => home_url('/properties'), 'class' => 'nav-link' . (is_page('properties') ? ' active' : '')],
['label' => 'About', 'url' => home_url('/about'), 'class' => 'nav-link' . (is_page('about') ? ' active' : '')],
['label' => 'Team', 'url' => home_url('/team'), 'class' => 'nav-link' . (is_page('team') ? ' active' : '')],
['label' => 'Developers', 'url' => home_url('/developers'), 'class' => 'nav-link' . (is_page('developers') ? ' active' : '')],
];

$navLinksAfter = [
['label' => 'Testimonials', 'url' => home_url('/testimonials'), 'class' => 'nav-link' . (is_page('testimonials') ? ' active' : '')],
];

$storiesDropdown = [
['label' => 'Stories', 'url' => home_url('/stories'), 'active' => is_page('stories')],
['label' => 'Events', 'url' => home_url('/events'), 'active' => is_page('events')],
['label' => 'Blog', 'url' => home_url('/blog'), 'active' => is_page('blog')],
];

$storiesActive = is_page('stories') || is_page('events') || is_page('blog');
@endphp

<header class="nav" id="site-header">
  <div class="container nav-inner">

    <div class="brand" aria-label="{{ get_bloginfo('name') }} home">
      <div class="brand-mark"
        @if ($isAdmin)
        data-edit-admin="admin.php?page=planetario-site-identity"
        data-edit-label="Update Logo"
        @endif>
        @if ($logo)
        {!! $logo !!}
        @else
        <span style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--accent);line-height:1">P</span>
        @endif
      </div>
      <a href="{{ home_url('/') }}" class="brand-text">
        <span class="name uppercase text-base tracking-wider"
          @if ($isAdmin)
          data-edit-field="brand_name"
          data-edit-post="option"
          data-edit-type="text"
          data-edit-label="Brand Name"
          @endif>
          {{ $site['brand']['name'] }}
        </span>
        <span class="sub tracking-widest"
          @if ($isAdmin)
          data-edit-field="brand_legal"
          data-edit-post="option"
          data-edit-type="text"
          data-edit-label="Brand Sub-title"
          @endif>
          {{ $site['brand']['legal'] }}
        </span>
      </a>
    </div>

    <nav class="nav-links" aria-label="Primary navigation">
      @foreach ($navLinksBefore as $link)
      <a href="{{ $link['url'] }}" class="{{ $link['class'] }}">
        {{ $link['label'] }}
      </a>
      @endforeach

      <div class="nav-dropdown-wrap">
        <button
          class="nav-link nav-link--has-dropdown{{ $storiesActive ? ' active' : '' }}"
          aria-haspopup="true"
          aria-expanded="false">
          Stories
          <svg class="dd-chevron" width="10" height="10" viewBox="0 0 10 10" fill="none" aria-hidden="true">
            <path d="M2 3.5l3 3 3-3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </button>
        <div class="nav-dropdown" role="menu">
          @foreach ($storiesDropdown as $item)
          <a
            href="{{ $item['url'] }}"
            class="nav-dropdown__item{{ $item['active'] ? ' active' : '' }}"
            role="menuitem">
            {{ $item['label'] }}
          </a>
          @endforeach
        </div>
      </div>

      @foreach ($navLinksAfter as $link)
      <a href="{{ $link['url'] }}" class="{{ $link['class'] }}">
        {{ $link['label'] }}
      </a>
      @endforeach
    </nav>

    <div class="nav-cta">

      <a href="{{ home_url('/contact') }}" class="btn btn-primary">
        Contact Us
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      <button class="btn menu-toggle" aria-label="Toggle menu" aria-expanded="false">
        <svg class="icon-menu" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
          <path d="M2 4h12M2 8h12M2 12h12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" />
        </svg>
        <svg class="icon-close" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" style="display:none">
          <path d="M3 3l10 10M13 3 3 13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" />
        </svg>
      </button>
    </div>

  </div>
</header>