@extends('layouts.app')

@section('content')

{{-- Intro — Tier 1 full-bleed hero --}}
<section class="page-hero">
  @if ($aboutIntro['imageUrl'])
  <div class="page-hero__bg">
    <img src="{{ $aboutIntro['imageUrl'] }}" alt="{{ $aboutIntro['imageAlt'] }}" aria-hidden="true">
  </div>
  @endif
  <div class="page-hero__overlay"></div>
  <x-orbit-deco style="right:-260px;top:-40px;opacity:.3" />

  <div class="container page-hero__inner">
    @if ($aboutIntro['eyebrow'])
    <span class="eyebrow-center">{{ $aboutIntro['eyebrow'] }}</span>
    @endif
    <h1 class="display" style="margin-top:18px;max-width:18ch">
      {{ $aboutIntro['headlineLead'] }}
      @if ($aboutIntro['headlineEm'])
      <em>{{ $aboutIntro['headlineEm'] }}</em>
      @endif
      @if ($aboutIntro['headlineTrail'])
      {{ $aboutIntro['headlineTrail'] }}
      @endif
    </h1>
    @if ($aboutIntro['lead'])
    <p class="lead" style="margin-top:28px;max-width:60ch">{{ $aboutIntro['lead'] }}</p>
    @endif
  </div>
</section>

{{-- Vision / Mission --}}
<section class="section" style="padding-top:36px;">
  <div class="container">
    <div class="vm-stage">
      <article class="vm-card vm-card--vision reveal">
        <div class="vm-card__bg" aria-hidden="true"></div>
        <div class="vm-card__inner">
          <span class="vm-card__eyebrow">Our</span>
          <h3 class="vm-card__title">Vision</h3>
          <div class="vm-card__body">
            {!! $aboutVm['vision'] !!}
          </div>
        </div>
        <svg class="vm-card__icon vm-card__icon--vision" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" />
          <circle cx="12" cy="12" r="3" />
        </svg>
      </article>
      <article class="vm-card vm-card--mission reveal">
        <div class="vm-card__bg" aria-hidden="true"></div>
        <div class="vm-card__inner">
          <span class="vm-card__eyebrow">Our</span>
          <h3 class="vm-card__title">Mission</h3>
          <div class="vm-card__body">
            {!! $aboutVm['mission'] !!}
          </div>
        </div>
        <svg class="vm-card__icon vm-card__icon--mission" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
          <circle cx="12" cy="12" r="9" />
          <circle cx="12" cy="12" r="5" />
          <circle cx="12" cy="12" r="1.4" fill="currentColor" />
          <path d="M12 3V1.5M21 12h1.5M18.5 5.5L22 2" />
        </svg>
      </article>
    </div>
  </div>
</section>

{{-- Board of Directors --}}
@if (! empty($aboutBoard))
<section class="section">
  <div class="container" style="text-align:center">
    <div class="section-head-col">
      <div class="reveal flex flex-col items-center">
        <span class="eyebrow">Leadership</span>
        <h2 class="h2" style="margin-top:14px">
          Board of <em>Directors.</em>
        </h2>
      </div>
    </div>
    <div class="flex flex-wrap gap-6 items-center justify-center stagger-children" style="margin-top:36px">
      @foreach ($aboutBoard as $member)
      <div class="team-card team-card--featured max-w-xs md:w-1/2 lg:w-1/3 reveal">
        <div class="media">
          <img src="{{ $member['photo'] }}"
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

{{-- Core Values --}}
<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        @if ($aboutValues['eyebrow'])<span class="eyebrow">{{ $aboutValues['eyebrow'] }}</span>@endif
        <h2 class="h2" style="margin-top:14px">
          {{ $aboutValues['headlineLead'] }}
          @if ($aboutValues['headlineEm']) <em>{{ $aboutValues['headlineEm'] }}</em>@endif
        </h2>
      </div>
      @if ($aboutValues['intro'])
      <p class="lead reveal" style="transition-delay:.1s">{{ $aboutValues['intro'] }}</p>
      @endif
    </div>
    <div class="stagger-children value-list">
      @foreach ($aboutValues['items'] as $v)
      <div class="value-row">
        <div class="num">{{ $v['number'] }}</div>
        <div class="ttl">{{ $v['title'] }}</div>
        <div class="desc">{{ $v['description'] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Why Choose Us --}}
<section class="section">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal text-center">
        @if ($aboutWhy['eyebrow'])<span class="eyebrow">{{ $aboutWhy['eyebrow'] }}</span>@endif
        <h2 class="h2" style="margin-top:14px">
          {{ $aboutWhy['headlineLead'] }}
          @if ($aboutWhy['headlineEm']) <br /><em>{{ $aboutWhy['headlineEm'] }}</em>@endif
        </h2>
      </div>
    </div>
    <div class="stagger-children feature-grid">
      @foreach ($aboutWhy['items'] as $s)
      <div class="feature">
        <div class="num">{{ $s['number'] }}</div>
        <div class="ttl">{{ $s['title'] }}</div>
        <p class="desc">{{ $s['description'] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Office Photos --}}
@if (!empty($aboutOffice['photos']))
<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head-col">
      <div class="reveal text-center">
        @if ($aboutOffice['eyebrow'])
        <span class="eyebrow reveal">{{ $aboutOffice['eyebrow'] }}</span>
        @endif
        @if ($aboutOffice['headline'])
        <h2 class="h2" style="margin-top:14px">{{ $aboutOffice['headline'] }}</h2>
        @endif
      </div>
    </div>
    <x-office-gallery :photos="$aboutOffice['photos']" />
  </div>
</section>
@endif

{{-- Closing quote --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container stagger-children" style="text-align:center">
    <div class="banner-quote reveal">{!! $aboutClosing['quote'] !!}</div>
    @if ($aboutClosing['attribution'])
    <div class="reveal" style="margin-top:16px;font-family:var(--font-mono);font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:var(--ink-3)">
      {{ $aboutClosing['attribution'] }}
    </div>
    @endif
    <div class=" reveal stagger-children" style="display:flex;justify-content:center;margin-top:36px;gap:12px;flex-wrap:wrap">
      @if ($aboutClosing['primaryLabel'])
      <a href="{{ $aboutClosing['primaryUrl'] }}" class="btn btn-primary  reveal">
        {{ $aboutClosing['primaryLabel'] }}
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      @endif
      @if ($aboutClosing['secondaryLabel'])
      <a href="{{ $aboutClosing['secondaryUrl'] }}" class="btn  reveal">
        {{ $aboutClosing['secondaryLabel'] }}
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </a>
      @endif
    </div>
  </div>
</section>
@endsection