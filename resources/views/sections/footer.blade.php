<footer class="footer">
  <div class="container">

    <div class="footer-grid">

      {{-- Brand column --}}
      <div>
        <a href="{{ home_url('/') }}" class="brand">
          <div class="brand-mark">
            <span style="font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--accent);line-height:1">P</span>
          </div>
          <div class="brand-text">
            <span class="name">Planetario</span>
            <span class="sub">Realty &amp; Brokerage</span>
          </div>
        </a>
        <p class="muted" style="margin-top:22px;max-width:38ch;font-size:14px;line-height:1.65">
          A Bohol-rooted realty house, brokering homes and investments across the Visayas with care and clarity.
        </p>
        <p class="tagline-mark" style="margin-top:18px">"Turning Property Dreams into Reality"</p>
      </div>

      {{-- Explore --}}
      <div>
        <h4>Explore</h4>
        <ul>
          <li><a href="{{ home_url('/properties') }}">Properties</a></li>
          <li><a href="{{ home_url('/developers') }}">Developers</a></li>
          <li><a href="{{ home_url('/stories') }}">Success Stories</a></li>
          <li><a href="{{ home_url('/testimonials') }}">Testimonials</a></li>
        </ul>
      </div>

      {{-- Company --}}
      <div>
        <h4>Company</h4>
        <ul>
          <li><a href="{{ home_url('/about') }}">About Us</a></li>
          <li><a href="{{ home_url('/team') }}">Our Team</a></li>
          <li><a href="{{ home_url('/contact') }}">Contact</a></li>
        </ul>
      </div>

      {{-- Contact --}}
      <div>
        <h4>Reach Us</h4>
        <ul>
          <li><a href="tel:09102671424">0910 267 1424</a></li>
          <li><a href="mailto:planetariorealtyandbrokerage@gmail.com">planetariorealtyandbrokerage@gmail.com</a></li>
          <li style="color:var(--ink-2);font-size:14px;line-height:1.55">
            66 Remolador Ext., Brgy. Cogon,<br>Tagbilaran City, Bohol
          </li>
        </ul>
      </div>

    </div>{{-- /.footer-grid --}}

    <div class="footer-bottom">
      <div>&copy; {{ date('Y') }} Planetario Realty &amp; Brokerage Services Inc.</div>
      <div class="sigil-line">
        <span>PRC Lic. No. ████-██</span>
        <span style="color:var(--line-2)">·</span>
        <span>Tagbilaran · Cebu</span>
      </div>
    </div>

  </div>
</footer>
