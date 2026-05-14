<footer class="footer">
  <div class="container">

    <div class="footer-grid">

      {{-- Brand column --}}
      <div>
        <a href="{{ home_url('/') }}" class="brand">
          <div class="brand-mark">
            <span style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--accent);line-height:1">{{ mb_substr($site['brand']['name'], 0, 1) }}</span>
          </div>
          <div class="brand-text">
            <span class="name">{{ explode(' ', $site['brand']['name'])[0] }}</span>
            <span class="sub">Realty &amp; Brokerage</span>
          </div>
        </a>
        @if ($site['brand']['short'])
          <p class="muted" style="margin-top:22px;max-width:38ch;font-size:14px;line-height:1.65">
            {{ $site['brand']['short'] }}
          </p>
        @endif
        @if ($site['brand']['tagline'])
          <p class="tagline-mark" style="margin-top:18px">"{{ $site['brand']['tagline'] }}"</p>
        @endif
        @if (! empty($site['socials']))
          <div style="margin-top:22px;display:flex;gap:12px">
            @foreach ($site['socials'] as $platform => $url)
              <a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($platform) }}" style="color:var(--ink-2);font-size:13px;text-transform:capitalize">{{ $platform }}</a>
            @endforeach
          </div>
        @endif
      </div>

      {{-- Explore --}}
      <div>
        <h4>Explore</h4>
        <ul>
          @foreach ($site['footer']['explore'] as $link)
            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
          @endforeach
        </ul>
      </div>

      {{-- Company --}}
      <div>
        <h4>Company</h4>
        <ul>
          @foreach ($site['footer']['company'] as $link)
            <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
          @endforeach
        </ul>
      </div>

      {{-- Contact --}}
      <div>
        <h4>Reach Us</h4>
        <ul>
          @if ($site['contact']['phone'])
            <li><a href="tel:{{ $site['contact']['phoneLink'] }}">{{ $site['contact']['phone'] }}</a></li>
          @endif
          @if ($site['contact']['email'])
            <li><a href="mailto:{{ $site['contact']['email'] }}">{{ $site['contact']['email'] }}</a></li>
          @endif
          @if ($site['contact']['addressLine1'] || $site['contact']['addressLine2'])
            <li style="color:var(--ink-2);font-size:14px;line-height:1.55">
              {{ $site['contact']['addressLine1'] }}@if ($site['contact']['addressLine2'])<br>{{ $site['contact']['addressLine2'] }}@endif
            </li>
          @endif
        </ul>
      </div>

    </div>{{-- /.footer-grid --}}

    <div class="footer-bottom">
      <div>&copy; {{ date('Y') }} {{ $site['footer']['copyrightOwner'] }}</div>
      <div class="sigil-line">
        @if ($site['footer']['sigilLeft'])<span>{{ $site['footer']['sigilLeft'] }}</span>@endif
        @if ($site['footer']['sigilLeft'] && $site['footer']['sigilRight'])<span style="color:var(--line-2)">·</span>@endif
        @if ($site['footer']['sigilRight'])<span>{{ $site['footer']['sigilRight'] }}</span>@endif
      </div>
    </div>

  </div>
</footer>
