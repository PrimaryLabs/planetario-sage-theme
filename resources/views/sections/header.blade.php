<header class="nav" id="site-header">
  <div class="container nav-inner">

    <a href="{{ home_url('/') }}" class="brand" aria-label="{{ get_bloginfo('name') }} home">
      <div class="brand-mark">
        @php($logo = get_custom_logo())
        @if ($logo)
          {!! $logo !!}
        @else
          <span style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--accent);line-height:1">P</span>
        @endif
      </div>
      <div class="brand-text">
        <span class="name">Planetario</span>
        <span class="sub">Realty &amp; Brokerage</span>
      </div>
    </a>

    <nav class="nav-links" aria-label="Primary navigation">
      <a href="{{ home_url('/') }}"             class="nav-link {{ is_front_page() ? 'active' : '' }}">Home</a>
      <a href="{{ home_url('/properties') }}"   class="nav-link {{ is_page('properties')   ? 'active' : '' }}">Properties</a>
      <a href="{{ home_url('/about') }}"        class="nav-link {{ is_page('about')         ? 'active' : '' }}">About</a>
      <a href="{{ home_url('/team') }}"         class="nav-link {{ is_page('team')          ? 'active' : '' }}">Team</a>
      <a href="{{ home_url('/developers') }}"   class="nav-link {{ is_page('developers')    ? 'active' : '' }}">Developers</a>
      <a href="{{ home_url('/stories') }}"      class="nav-link {{ is_page('stories')       ? 'active' : '' }}">Stories</a>
      <a href="{{ home_url('/testimonials') }}" class="nav-link {{ is_page('testimonials')  ? 'active' : '' }}">Testimonials</a>
      <a href="{{ home_url('/contact') }}"      class="nav-link {{ is_page('contact')       ? 'active' : '' }}">Contact</a>
    </nav>

    <div class="nav-cta">
      <a href="{{ home_url('/contact') }}" class="btn btn-primary">
        Book a Tripping
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      <button class="btn menu-toggle" aria-label="Toggle menu" aria-expanded="false">
        <svg class="icon-menu" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
          <path d="M2 5h12M2 11h12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        </svg>
        <svg class="icon-close" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true" style="display:none">
          <path d="M3 3l10 10M13 3 3 13" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
        </svg>
      </button>
    </div>

  </div>
</header>
