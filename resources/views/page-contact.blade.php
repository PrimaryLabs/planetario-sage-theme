@extends('layouts.app')

@section('content')

{{-- Intro — Tier 1 full-bleed hero --}}
@php
$contactHeroImage = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1600&h=900&fit=crop&q=80';
@endphp
<section class="page-hero">
  <div class="page-hero__bg">
    <img src="{{ $contactHeroImage }}" alt="" aria-hidden="true">
  </div>
  <div class="page-hero__overlay"></div>
  <x-orbit-deco style="right:-260px;top:-40px;opacity:.25" />

  <div class="container page-hero__inner">
    @if ($pageIntro['eyebrow'])
    <span class="eyebrow"
      data-edit-field="page_intro_eyebrow"
      data-edit-type="text"
      data-edit-label="Hero Eyebrow">{{ $pageIntro['eyebrow'] }}</span>
    @endif

    <h1 class="display" style="margin-top:18px;max-width:18ch">
      <span
        data-edit-field="page_intro_headline_lead"
        data-edit-type="text"
        data-edit-label="Headline Lead">{{ $pageIntro['headlineLead'] }}</span>
      @if ($pageIntro['headlineEm'])
      <em
        data-edit-field="page_intro_headline_emphasis"
        data-edit-type="text"
        data-edit-label="Headline Emphasis">{{ $pageIntro['headlineEm'] }}</em>
      @endif
      @if ($pageIntro['headlineTrail'])
      <span
        data-edit-field="page_intro_headline_trail"
        data-edit-type="text"
        data-edit-label="Headline Trail">{{ $pageIntro['headlineTrail'] }}</span>
      @endif
    </h1>

    @if ($pageIntro['lead'])
    <p class="lead" style="margin-top:22px;max-width:60ch"
      data-edit-field="page_intro_lead"
      data-edit-type="textarea"
      data-edit-label="Hero Lead">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>


<section class="section" style="padding-top:24px">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:60px" class="contact-grid">

      {{-- Contact info sidebar --}}
      <aside style="background:var(--bg-2);border:1px solid var(--line);border-radius:14px;padding:36px;position:relative;overflow:hidden">
        <span class="eyebrow"
          data-edit-field="contact_sidebar_eyebrow"
          data-edit-type="text"
          data-edit-label="Sidebar Eyebrow">{{ $contactSections['sidebarEyebrow'] }}</span>

        @if ($site['contact']['addressLine1'] || $site['contact']['addressLine2'])
        <div style="margin-top:32px">
          <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">Office</div>
          <div style="font-family:var(--font-display);font-size:20px;margin-top:6px;line-height:1.35">
            <span
              data-edit-field="contact_address_line_1"
              data-edit-type="text"
              data-edit-post="option"
              data-edit-label="Address Line 1">{{ $site['contact']['addressLine1'] }}</span>
            @if ($site['contact']['addressLine2'])
            <br>
            <span
              data-edit-field="contact_address_line_2"
              data-edit-type="text"
              data-edit-post="option"
              data-edit-label="Address Line 2">{{ $site['contact']['addressLine2'] }}</span>
            @endif
          </div>
        </div>
        @endif

        @if ($site['contact']['phone'])
        <div style="margin-top:28px">
          <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">Phone</div>
          <a href="tel:{{ $site['contact']['phoneLink'] }}" style="font-family:var(--font-display);font-size:22px;margin-top:6px;display:inline-block;color:var(--accent)">
            <span
              data-edit-field="contact_phone"
              data-edit-type="text"
              data-edit-post="option"
              data-edit-label="Phone (display text)">{{ $site['contact']['phone'] }}</span>
          </a>
          @php $isAdmin = function_exists('is_user_logged_in') && \is_user_logged_in() && \current_user_can('edit_posts'); @endphp
          @if ($isAdmin)
          <div style="margin-top:4px;font-size:11px;color:var(--ink-3)">
            Tel: link:&nbsp;<span
              data-edit-field="contact_phone_link"
              data-edit-type="text"
              data-edit-post="option"
              data-edit-label="Phone tel: link (digits only, no spaces)"
              style="font-family:var(--font-mono)">{{ $site['contact']['phoneLink'] }}</span>
          </div>
          @endif
        </div>
        @endif

        @if ($site['contact']['email'])
        <div style="margin-top:28px">
          <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">Email</div>
          <a href="mailto:{{ $site['contact']['email'] }}"
            data-edit-field="contact_email"
            data-edit-type="text"
            data-edit-post="option"
            data-edit-href-prefix="mailto:"
            data-edit-label="Email address"
            style="font-family:var(--font-display);font-size:16px;margin-top:6px;display:inline-block;color:var(--accent);word-break:break-all">{{ $site['contact']['email'] }}</a>
        </div>
        @endif

        @if ($site['contact']['hoursWeekday'] || $site['contact']['hoursWeekend'])
        <div style="margin-top:28px">
          <div style="font-family:var(--font-mono);font-size:10.5px;letter-spacing:.18em;text-transform:uppercase;color:var(--ink-3)">Office hours</div>
          <div style="margin-top:6px;color:var(--ink-2);font-size:14.5px;line-height:1.7">
            <span
              data-edit-field="contact_hours_weekday"
              data-edit-type="text"
              data-edit-post="option"
              data-edit-label="Office Hours — Weekday">{{ $site['contact']['hoursWeekday'] }}</span>
            @if ($site['contact']['hoursWeekday'] && $site['contact']['hoursWeekend'])
            <br>
            @endif
            <span
              data-edit-field="contact_hours_weekend"
              data-edit-type="text"
              data-edit-post="option"
              data-edit-label="Office Hours — Weekend">{{ $site['contact']['hoursWeekend'] }}</span>
          </div>
        </div>
        @endif

        <div class="hr-orbit"><span class="dot"></span></div>

        @if ($site['contact']['note'])
        <p class="muted" style="font-size:13.5px;line-height:1.6"
          data-edit-field="contact_note"
          data-edit-type="textarea"
          data-edit-post="option"
          data-edit-label="Sidebar Note">{{ $site['contact']['note'] }}</p>
        @endif
      </aside>

      {{-- Contact form --}}
      <div>
        @if (isset($_GET['sent']) && $_GET['sent'] === '1')
        <div style="border:1px solid var(--line-2);border-radius:14px;padding:48px;background:var(--bg-2);text-align:center">
          <div style="width:56px;height:56px;border-radius:50%;border:1px solid var(--accent);display:inline-grid;place-items:center;color:var(--accent)">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
              <path d="M2 7.5l3 3 7-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
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
        @php
        $contact_action = admin_url('admin-post.php');
        @endphp
        <form method="POST" action="{{ $contact_action }}" style="display:grid;gap:28px;grid-template-columns:1fr 1fr"
          id="contact-form" class="contact-form" novalidate>
          {!! wp_nonce_field('planetario_contact', '_wpnonce', true, false) !!}
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
                <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
              </svg>
            </button>
          </div>
        </form>
        @endif
      </div>

    </div>
  </div>
  <style>
    @media (max-width:980px) {
      .contact-grid {
        grid-template-columns: 1fr !important;
        gap: 32px !important
      }
    }

    @media (max-width:560px) {
      .contact-form {
        grid-template-columns: 1fr !important
      }

      .contact-form>* {
        grid-column: span 1 !important
      }
    }
  </style>
</section>

<section class="section" style="padding-top:64px">
  <div class="container">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:24px;flex-wrap:wrap;margin-bottom:24px">
      <div>
        <span class="eyebrow"
          data-edit-field="contact_map_eyebrow"
          data-edit-type="text"
          data-edit-label="Map Eyebrow">{{ $contactSections['map']['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px;max-width:22ch">
          <span
            data-edit-field="contact_map_heading_lead"
            data-edit-type="text"
            data-edit-label="Map Heading Lead">{{ $contactSections['map']['headingLead'] }}</span>
          <em
            data-edit-field="contact_map_heading_em"
            data-edit-type="text"
            data-edit-label="Map Heading Emphasis">{{ $contactSections['map']['headingEm'] }}</em>
          <span
            data-edit-field="contact_map_heading_trail"
            data-edit-type="text"
            data-edit-label="Map Heading Trail">{{ $contactSections['map']['headingTrail'] }}</span>
        </h2>
      </div>
      <a class="btn btn-ghost" href="https://www.google.com/maps/dir/?api=1&destination=9.6500419,123.853422" target="_blank" rel="noopener noreferrer" style="padding-left: 24px; padding-right: 24px;">
        Get directions
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
    </div>

    <div style="border:1px solid var(--line);border-radius:14px;overflow:hidden;background:var(--bg-2);aspect-ratio:16/7;min-height:360px">

      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d867.2313810006589!2d123.85299659679596!3d9.650150520001345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33aa4c4d46953b65%3A0x2505987c8ce7172c!2sJaz-M%20Bldg.!5e0!3m2!1sen!2sph!4v1779539068766!5m2!1sen!2sph" width="100%"
        height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>

<script>
  (function() {
    const form = document.getElementById('contact-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
      let valid = true;

      function check(id, fieldId, test) {
        const input = document.getElementById(id);
        const field = document.getElementById(fieldId);
        const errorDiv = field.querySelector('.err');

        if (!test(input.value)) {
          errorDiv.style.display = 'block';
          valid = false;
        } else if (errorDiv) {
          errorDiv.style.display = 'none';
        }
      }

      check('cf-name', 'field-name', val => val.trim().length > 0);
      check('cf-email', 'field-email', val => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val));
      check('cf-message', 'field-message', val => val.trim().length >= 12);

      if (!valid) {
        e.preventDefault();
      }
    });
  })();
</script>
@endsection
