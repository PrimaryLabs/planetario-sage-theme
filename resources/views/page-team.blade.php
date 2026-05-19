@extends('layouts.app')

@section('content')
{{-- Intro — Tier 2 compact hero --}}
<section class="page-hero page-hero--compact">
  <x-orbit-deco style="right:-220px;top:-20px;opacity:.25" />
  <div class="container flex flex-col items-center justify-center text-center">
    @if ($pageIntro['eyebrow'])
    <span class="eyebrow-center">{{ $pageIntro['eyebrow'] }}</span>
    @endif

    <h1 class="display" style="margin-top:18px;">
      {{ $pageIntro['headlineLead'] }}
      @if ($pageIntro['headlineEm'])
      <em>{{ $pageIntro['headlineEm'] }}</em>
      @endif
      @if ($pageIntro['headlineTrail'])
      {{ $pageIntro['headlineTrail'] }}
      @endif
    </h1>

    @if ($pageIntro['lead'])
    <p class="lead" style="margin-top:24px;max-width:62ch">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

{{-- Board of Directors --}}
@if (! empty($boardOfDirectors))
<section class="section" style="padding-top:88px">
  <div class="container" style="text-align:center">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Leadership</span>
        <h2 class="h2" style="margin-top:14px">
          Board of <em>Directors.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($boardOfDirectors as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3">
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
        <span class="eyebrow">Salesfloor</span>
        <h2 class="h2" style="margin-top:14px">
          <em>Brokers.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($brokers as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3">
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
        <span class="eyebrow">Bohol</span>
        <h2 class="h2" style="margin-top:14px">
          Bohol <em>Managers.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($boholManagers as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3">
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
        <span class="eyebrow">Cebu</span>
        <h2 class="h2" style="margin-top:14px">
          Cebu <em>Managers.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($cebuManagers as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3">
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
        <span class="eyebrow">Bohol</span>
        <h2 class="h2" style="margin-top:14px">
          Bohol <em>Staff.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($boholStaffs as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3">
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
        <span class="eyebrow">Cebu</span>
        <h2 class="h2" style="margin-top:14px">
          Cebu <em>Staff.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center" style="margin-top:36px">
      @foreach ($cebuStaffs as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3">
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
        <span class="eyebrow">{{ $pageClosing['eyebrow'] }}</span>
        @endif
        <h2 class="h2" style="margin-top:14px">
          {{ $pageClosing['headlineLead'] }}
          @if ($pageClosing['headlineEm'])
          <em>{{ $pageClosing['headlineEm'] }}</em>
          @endif
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        @if ($pageClosing['body'])
        <p class="lead">{{ $pageClosing['body'] }}</p>
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