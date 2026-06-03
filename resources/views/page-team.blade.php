@extends('layouts.app')

@section('content')
{{-- Intro — Tier 2 compact hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-20px;opacity:.25" />
  <div class="container flex flex-col items-center justify-center text-center">
    @if ($pageIntro['eyebrow'])
    <span class="eyebrow-center"
      data-edit-field="page_intro_eyebrow"
      data-edit-type="text"
      data-edit-label="Intro Eyebrow">{{ $pageIntro['eyebrow'] }}</span>
    @endif

    <h1 class="display" style="margin-top:18px;">
      <span
        data-edit-field="page_intro_headline_lead"
        data-edit-type="text"
        data-edit-label="Intro Headline Lead">{{ $pageIntro['headlineLead'] }}</span>
      @if ($pageIntro['headlineEm'])
      <em
        data-edit-field="page_intro_headline_emphasis"
        data-edit-type="text"
        data-edit-label="Intro Headline Emphasis">{{ $pageIntro['headlineEm'] }}</em>
      @endif
      @if ($pageIntro['headlineTrail'])
      <span
        data-edit-field="page_intro_headline_trail"
        data-edit-type="text"
        data-edit-label="Intro Headline Trail">{{ $pageIntro['headlineTrail'] }}</span>
      @endif
    </h1>

    @if ($pageIntro['lead'])
    <p class="lead" style="margin-top:24px;max-width:62ch"
      data-edit-field="page_intro_lead"
      data-edit-type="textarea"
      data-edit-label="Intro Lead">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

{{-- Board of Directors --}}
@if (! empty($boardOfDirectors))
<section class="section" style="padding-top:88px">
  <div class="container" style="text-align:center">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow"
          data-edit-field="team_board_eyebrow"
          data-edit-type="text"
          data-edit-label="Board — Eyebrow">{{ $sectionLabels['board']['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="team_board_headline_lead"
            data-edit-type="text"
            data-edit-label="Board — Headline Lead">{{ $sectionLabels['board']['lead'] }}</span>
          <em
            data-edit-field="team_board_headline_em"
            data-edit-type="text"
            data-edit-label="Board — Headline Emphasis">{{ $sectionLabels['board']['em'] }}</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      @foreach ($boardOfDirectors as $i => $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3 reveal" style="{{ $i > 0 ? 'transition-delay:.'.(($i+1)*1).'s' : '' }}">
        <div class="media">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
            alt="{{ esc_attr($member['name']) }}"
            loading="lazy">
        </div>
        <div class="body flex flex-col relative items-center justify-center">
          <div class="name font-bold text-xl tracking-wide">{{ $member['name'] }}</div>
          <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
          <div class="role tracking-wide font-medium! text-sm">{{ $member['role'] }}</div>
          @if (! empty($member['bio']))
          <p class="bio border-t-0 absolute top-11 text-xs">{{ $member['bio'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Brokers --}}
@if (! empty($brokers))
<section class="section" style="background:var(--bg-2)">
  <div class="container" style="text-align:center">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow"
          data-edit-field="team_brokers_eyebrow"
          data-edit-type="text"
          data-edit-label="Brokers — Eyebrow">{{ $sectionLabels['brokers']['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <em
            data-edit-field="team_brokers_headline_em"
            data-edit-type="text"
            data-edit-label="Brokers — Headline Emphasis">{{ $sectionLabels['brokers']['em'] }}</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      @foreach ($brokers as $i => $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3 reveal" style="{{ $i > 0 ? 'transition-delay:.'.(($i+1)*1).'s' : '' }}">
        <div class="media">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
            alt="{{ esc_attr($member['name']) }}"
            loading="lazy">
        </div>
        <div class="body flex flex-col relative items-center justify-center">
          <div class="name font-bold text-xl tracking-wide">{{ $member['name'] }}</div>
          <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
          <div class="role tracking-wide font-medium! text-xs">{{ $member['role'] }}</div>
          @if (! empty($member['bio']))
          <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Bohol Managers --}}
@if (! empty($boholManagers))
<section class="section">
  <div class="container" style="text-align:center">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow"
          data-edit-field="team_bohol_mgr_eyebrow"
          data-edit-type="text"
          data-edit-label="Bohol Managers — Eyebrow">{{ $sectionLabels['boholMgr']['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="team_bohol_mgr_lead"
            data-edit-type="text"
            data-edit-label="Bohol Managers — Headline Lead">{{ $sectionLabels['boholMgr']['lead'] }}</span>
          <em
            data-edit-field="team_bohol_mgr_headline_em"
            data-edit-type="text"
            data-edit-label="Bohol Managers — Headline Emphasis">{{ $sectionLabels['boholMgr']['em'] }}</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      @foreach ($boholManagers as $i => $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3 reveal">
        <div class="media">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
            alt="{{ esc_attr($member['name']) }}"
            loading="lazy">
        </div>
        <div class="body flex flex-col relative items-center justify-center">
          <div class="name font-bold text-xl tracking-wide">{{ $member['name'] }}</div>
          <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
          <div class="role tracking-wide font-medium! text-sm">{{ $member['role'] }}</div>
          @if (! empty($member['bio']))
          <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Cebu Managers --}}
@if (! empty($cebuManagers))
<section class="section" style="background:var(--bg-2)">
  <div class="container" style="text-align:center">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow"
          data-edit-field="team_cebu_mgr_eyebrow"
          data-edit-type="text"
          data-edit-label="Cebu Managers — Eyebrow">{{ $sectionLabels['cebuMgr']['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="team_cebu_mgr_lead"
            data-edit-type="text"
            data-edit-label="Cebu Managers — Headline Lead">{{ $sectionLabels['cebuMgr']['lead'] }}</span>
          <em
            data-edit-field="team_cebu_mgr_headline_em"
            data-edit-type="text"
            data-edit-label="Cebu Managers — Headline Emphasis">{{ $sectionLabels['cebuMgr']['em'] }}</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      @foreach ($cebuManagers as $i => $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3 reveal">
        <div class="media">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
            alt="{{ esc_attr($member['name']) }}"
            loading="lazy">
        </div>
        <div class="body flex flex-col relative items-center justify-center">
          <div class="name font-bold text-xl tracking-wide">{{ $member['name'] }}</div>
          <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
          <div class="role tracking-wide font-medium! text-sm">{{ $member['role'] }}</div>
          @if (! empty($member['bio']))
          <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Bohol Staffs --}}
@if (! empty($boholStaffs))
<section class="section">
  <div class="container" style="text-align:center">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow"
          data-edit-field="team_bohol_staff_eyebrow"
          data-edit-type="text"
          data-edit-label="Bohol Staff — Eyebrow">{{ $sectionLabels['boholStaff']['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="team_bohol_staff_lead"
            data-edit-type="text"
            data-edit-label="Bohol Staff — Headline Lead">{{ $sectionLabels['boholStaff']['lead'] }}</span>
          <em
            data-edit-field="team_bohol_staff_headline_em"
            data-edit-type="text"
            data-edit-label="Bohol Staff — Headline Emphasis">{{ $sectionLabels['boholStaff']['em'] }}</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      @foreach ($boholStaffs as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3 reveal">
        <div class="media">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
            alt="{{ esc_attr($member['name']) }}"
            loading="lazy">
        </div>
        <div class="body flex flex-col relative items-center justify-center">
          <div class="name font-bold text-xl tracking-wide">{{ $member['name'] }}</div>
          <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
          <div class="role tracking-wide font-medium! text-xs">{{ $member['role'] }}</div>
          @if (! empty($member['bio']))
          <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- Cebu Staffs --}}
@if (! empty($cebuStaffs))
<section class="section" style="background:var(--bg-2)">
  <div class="container" style="text-align:center">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow"
          data-edit-field="team_cebu_staff_eyebrow"
          data-edit-type="text"
          data-edit-label="Cebu Staff — Eyebrow">{{ $sectionLabels['cebuStaff']['eyebrow'] }}</span>
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="team_cebu_staff_lead"
            data-edit-type="text"
            data-edit-label="Cebu Staff — Headline Lead">{{ $sectionLabels['cebuStaff']['lead'] }}</span>
          <em
            data-edit-field="team_cebu_staff_headline_em"
            data-edit-type="text"
            data-edit-label="Cebu Staff — Headline Emphasis">{{ $sectionLabels['cebuStaff']['em'] }}</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px"
      data-edit-admin="edit.php?post_type=team_member"
      title="Click to manage team members in WP Admin">
      @foreach ($cebuStaffs as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3 reveal">
        <div class="media">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
            alt="{{ esc_attr($member['name']) }}"
            loading="lazy">
        </div>
        <div class="body flex flex-col relative items-center justify-center">
          <div class="name font-bold text-xl tracking-wide">{{ $member['name'] }}</div>
          <span class="w-1/2 h-[0.5px] bg-ink/20"></span>
          <div class="role tracking-wide font-medium! text-xs">{{ $member['role'] }}</div>
          @if (! empty($member['bio']))
          <p class="bio border-t-0 absolute top-14">{{ $member['bio'] }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:60px;align-items:center" class="join-grid">
      <div class="reveal">
        @if ($pageClosing['eyebrow'])
        <span class="eyebrow"
          data-edit-field="page_closing_eyebrow"
          data-edit-type="text"
          data-edit-label="Closing Eyebrow">{{ $pageClosing['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          <span
            data-edit-field="page_closing_headline_lead"
            data-edit-type="text"
            data-edit-label="Closing Headline Lead">{{ $pageClosing['headlineLead'] }}</span>
          @if ($pageClosing['headlineEm'])
          <em
            data-edit-field="page_closing_headline_emphasis"
            data-edit-type="text"
            data-edit-label="Closing Headline Emphasis">{{ $pageClosing['headlineEm'] }}</em>
          @endif
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        @if ($pageClosing['body'])
        <p class="lead"
          data-edit-field="page_closing_body"
          data-edit-type="textarea"
          data-edit-label="Closing Body">{{ $pageClosing['body'] }}</p>
        @endif
        @if ($pageClosing['primaryLabel'])
        <div style="margin-top:24px">
          <a href="{{ $pageClosing['primaryUrl'] }}" class="btn btn-primary">
            {{ $pageClosing['primaryLabel'] }}
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
        </div>
        @endif
      </div>
    </div>
  </div>
  <style>
    @media (max-width:860px) {
      .join-grid {
        grid-template-columns: 1fr !important;
        gap: 24px !important
      }
    }
  </style>
</section>

@endsection