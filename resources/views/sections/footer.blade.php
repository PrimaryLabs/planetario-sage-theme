@php

$logo = get_custom_logo();
$isAdmin = \is_user_logged_in() && \current_user_can('edit_posts');

$brandName = $site['brand']['name'];
$brandInitial = mb_substr($brandName, 0, 1);
$brandFirstName = explode(' ', $brandName)[0];
$addressLine1 = $site['contact']['addressLine1'];
$addressLine2 = $site['contact']['addressLine2'];
$sigilLeft = $site['footer']['sigilLeft'];
$sigilRight = $site['footer']['sigilRight'];
@endphp

<footer class="footer">
  <div class="container">

    <div class="footer-grid">

      {{-- Brand column --}}
      <div>
        <div class="flex items-center gap-4">
          <div class="brand-mark"
            @if ($isAdmin)
            data-edit-admin="admin.php?page=planetario-site-identity"
            data-edit-label="Update Logo"
            @endif>
            @if ($logo)
            {!! $logo !!}
            @else
            <span style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--accent);line-height:1">{{ $brandInitial }}</span>
            @endif
          </div>
          <a href="{{ home_url('/') }}" class="brand-text">
            <span class="name uppercase tracking-widest"
              @if ($isAdmin)
              data-edit-field="brand_name"
              data-edit-post="option"
              data-edit-type="text"
              data-edit-label="Brand Name (full)"
              @endif>
              {{ $brandFirstName }}
            </span>
            <span class="sub"
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
        @if ($site['brand']['short'])
        <p class="muted" style="margin-top:22px;max-width:38ch;font-size:14px;line-height:1.65"
          @if ($isAdmin)
          data-edit-field="brand_short"
          data-edit-post="option"
          data-edit-type="textarea"
          data-edit-label="Brand Short Description"
          @endif>
          {{ $site['brand']['short'] }}
        </p>
        @endif
        @if ($site['brand']['tagline'])
        <p class="tagline-mark" style="margin-top:18px">"<span
          @if ($isAdmin)
          data-edit-field="brand_tagline"
          data-edit-post="option"
          data-edit-type="text"
          data-edit-label="Brand Tagline / Quote"
          @endif
        >{{ $site['brand']['tagline'] }}</span>"</p>
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
          @if ($addressLine1 || $addressLine2)
          <li style="color:var(--ink-2);font-size:14px;line-height:1.55">
            @if ($addressLine1)
            {{ $addressLine1 }}
            @endif

            @if ($addressLine1 && $addressLine2)
            <br>
            @endif

            @if ($addressLine2)
            {{ $addressLine2 }}
            @endif
          </li>
          @endif
        </ul>
      </div>

    </div>{{-- /.footer-grid --}}

    <div class="footer-bottom">
      <div>&copy; {{ date('Y') }} {{ $site['footer']['copyrightOwner'] }}</div>
      <div class="sigil-line">
        @if ($sigilLeft)
        <span>{{ $sigilLeft }}</span>
        @endif

        @if ($sigilLeft && $sigilRight)
        <span style="color:var(--line-2)">·</span>
        @endif

        @if ($sigilRight)
        <span>{{ $sigilRight }}</span>
        @endif
      </div>
    </div>

  </div>
</footer>