@extends('layouts.app')

@section('content')

<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-260px;top:-40px;opacity:.25" />
    <span class="eyebrow">Contact us</span>
    <h1 class="display" style="margin-top:18px;max-width:18ch">
      Tell us what you're <em>looking for.</em>
    </h1>
    <p class="lead" style="margin-top:22px;max-width:60ch">
      A real broker reads every message that comes through this form. Most replies
      go out within four working hours, often the same morning.
    </p>
  </div>
</section>

<section class="section" style="padding-top:24px">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:60px" class="contact-grid">

      {{-- Contact info sidebar --}}
      <aside style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;padding:36px;position:relative;overflow:hidden">
        <span class="eyebrow">Reach us directly</span>

        @if ($site['contact']['addressLine1'] || $site['contact']['addressLine2'])
          <div style="margin-top:32px">
            <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">Office</div>
            <div style="font-family:var(--font-display);font-size:20px;margin-top:6px;line-height:1.35">
              {{ $site['contact']['addressLine1'] }}@if ($site['contact']['addressLine2'])<br>{{ $site['contact']['addressLine2'] }}@endif
            </div>
          </div>
        @endif

        @if ($site['contact']['phone'])
          <div style="margin-top:28px">
            <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">Phone</div>
            <a href="tel:{{ $site['contact']['phoneLink'] }}" style="font-family:var(--font-display);font-size:22px;margin-top:6px;display:inline-block;color:var(--accent)">
              {{ $site['contact']['phone'] }}
            </a>
          </div>
        @endif

        @if ($site['contact']['email'])
          <div style="margin-top:28px">
            <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">Email</div>
            <a href="mailto:{{ $site['contact']['email'] }}"
               style="font-family:var(--font-display);font-size:16px;margin-top:6px;display:inline-block;color:var(--accent);word-break:break-all">
              {{ $site['contact']['email'] }}
            </a>
          </div>
        @endif

        @if ($site['contact']['hoursWeekday'] || $site['contact']['hoursWeekend'])
          <div style="margin-top:28px">
            <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">Office hours</div>
            <div style="margin-top:6px;color:var(--ink-2);font-size:14.5px;line-height:1.7">
              {{ $site['contact']['hoursWeekday'] }}@if ($site['contact']['hoursWeekday'] && $site['contact']['hoursWeekend'])<br>@endif{{ $site['contact']['hoursWeekend'] }}
            </div>
          </div>
        @endif

        <div class="hr-orbit"><span class="dot"></span></div>

        @if ($site['contact']['note'])
          <p class="muted" style="font-size:13.5px;line-height:1.6">{{ $site['contact']['note'] }}</p>
        @endif
      </aside>

      {{-- Contact form --}}
      <div>
        @if (isset($_GET['sent']) && $_GET['sent'] === '1')
          <div style="border:1px solid var(--line-2);border-radius:14px;padding:48px;background:var(--bg-2);text-align:center">
            <div style="width:56px;height:56px;border-radius:50%;border:1px solid var(--accent);display:inline-grid;place-items:center;color:var(--accent)">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M2 7.5l3 3 7-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <h3 class="h2" style="margin-top:22px;font-size:clamp(26px,3vw,38px)">
              Message <em>received.</em>
            </h3>
            <p class="lead" style="margin:16px auto 0;text-align:center">
              A senior broker will read this and reply within four working hours.
              If urgent, please call us at <a href="tel:{{ $site['contact']['phoneLink'] }}" style="color:var(--accent)">{{ $site['contact']['phone'] }}</a>.
            </p>
          </div>
        @else
          @php($contact_action = admin_url('admin-post.php'))
          <form method="POST" action="{{ $contact_action }}" style="display:grid;gap:28px;grid-template-columns:1fr 1fr"
                id="contact-form" class="contact-form" novalidate>
            @php(wp_nonce_field('planetario_contact', '_wpnonce'))
            <input type="hidden" name="action" value="planetario_contact">

            <div class="field" style="grid-column:span 2" id="field-name">
              <label for="cf-name">Your name</label>
              <input id="cf-name" name="name" type="text" placeholder="Maria Reyes" autocomplete="name">
              <div class="err" style="display:none">Required</div>
            </div>

            <div class="field" id="field-email">
              <label for="cf-email">Email</label>
              <input id="cf-email" name="email" type="email" placeholder="maria@example.com" autocomplete="email">
              <div class="err" style="display:none">Valid email required</div>
            </div>

            <div class="field">
              <label for="cf-phone">Phone (optional)</label>
              <input id="cf-phone" name="phone" type="tel" placeholder="+63 ..." autocomplete="tel">
            </div>

            <div class="field" style="grid-column:span 2">
              <label for="cf-interest">I'm interested in</label>
              <select id="cf-interest" name="interest">
                <option>Property inquiry</option>
                <option>Listing my property</option>
                <option>Investment consultation</option>
                <option>Sales partnership / brokerage</option>
                <option>Developer partnership</option>
                <option>Something else</option>
              </select>
            </div>

            <div class="field" style="grid-column:span 2" id="field-message">
              <label for="cf-message">Message</label>
              <textarea id="cf-message" name="message" rows="5"
                placeholder="Tell us about the property, the timeline, or the question — whatever is most useful."></textarea>
              <div class="err" style="display:none">Please tell us a bit more (at least 12 characters)</div>
            </div>

            <div style="grid-column:span 2;display:flex;gap:16px;align-items:center;justify-content:space-between;flex-wrap:wrap">
              <p class="muted" style="font-size:12.5px">We don't share your details. Period.</p>
              <button type="submit" class="btn btn-primary">
                Send message
                <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                  <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </button>
            </div>
          </form>
        @endif
      </div>

    </div>
  </div>
  <style>
    @media (max-width:980px){.contact-grid{grid-template-columns:1fr!important;gap:32px!important}}
    @media (max-width:560px){.contact-form{grid-template-columns:1fr!important}.contact-form>*{grid-column:span 1!important}}
  </style>
</section>

<script>
(function () {
  const form = document.getElementById('contact-form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    let valid = true;

    function check(id, fieldId, test) {
      const input = document.getElementById(id);
      const field = document.getElementById(fieldId);
      if (!field) return;
      const ok = test(input ? input.value : '');
      field.classList.toggle('error', !ok);
      field.querySelector('.err').style.display = ok ? 'none' : '';
      if (!ok) valid = false;
    }

    check('cf-name',    'field-name',    v => v.trim().length > 0);
    check('cf-email',   'field-email',   v => /^[^@]+@[^@]+\.[^@]+$/.test(v.trim()));
    check('cf-message', 'field-message', v => v.trim().length >= 12);

    if (!valid) e.preventDefault();
  });
})();
</script>
@endsection
