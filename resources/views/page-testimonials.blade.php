@extends('layouts.app')

@section('content')
@php use App\Data\StaticData; $testimonials = $testimonials ?? StaticData::testimonials(); @endphp

<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
    <span class="eyebrow">In our clients' words</span>
    <h1 class="display" style="margin-top:18px;max-width:22ch">
      We don't ask for these. <em>They arrive anyway.</em>
    </h1>
    <p class="lead" style="margin-top:22px;max-width:62ch">
      What follows is unedited — pulled from emails, voice notes, and handwritten
      cards that have come through our Tagbilaran office over the years. Names
      shared with permission.
    </p>
  </div>
</section>

<section class="section" style="padding-top:24px">
  <div class="container">
    <div class="stagger-children testi-grid"
         style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px">
      @foreach ($testimonials as $t)
        <div class="testimonial">
          <div class="quote-mk">"</div>
          <blockquote>{{ $t['quote'] }}</blockquote>
          <div class="who">
            <img src="{{ $t['avatar'] ?? ('https://i.pravatar.cc/96?u=' . urlencode($t['name'])) }}"
                 alt="" width="48" height="48">
            <div>
              <div class="name">{{ $t['name'] }}</div>
              <div class="role">{{ $t['role'] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <style>
      @media (max-width:1000px){.testi-grid{grid-template-columns:1fr 1fr!important}}
      @media (max-width:640px){.testi-grid{grid-template-columns:1fr!important}}
    </style>
  </div>
</section>

{{-- Numbers strip --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">The quiet numbers</span>
        <h2 class="h2" style="margin-top:14px">
          The things our clients <em>don't usually mention</em> in their testimonials.
        </h2>
      </div>
    </div>
    <div class="stagger-children metrics-grid"
         style="display:grid;grid-template-columns:repeat(4,1fr);gap:24px">
      @foreach ([
        ['v' => 98,  'suf' => '%',   'l' => 'of buyers refer at least one friend'],
        ['v' => 4,   'suf' => ' hrs','l' => 'average first-reply time'],
        ['v' => 0,   'suf' => '',    'l' => 'closings reopened due to documentation'],
        ['v' => 11,  'suf' => ' mo', 'l' => 'longest case we saw through'],
      ] as $m)
        <div style="border-top:1px solid var(--line-2);padding-top:22px">
          <div style="font-family:var(--font-display);font-size:clamp(36px,4.6vw,64px);color:var(--accent);line-height:1">
            <span data-countup="{{ $m['v'] }}" data-suffix="{{ $m['suf'] }}">{{ $m['v'] }}{{ $m['suf'] }}</span>
          </div>
          <div style="margin-top:12px;color:var(--ink-2);font-size:14px;max-width:22ch;line-height:1.45">{{ $m['l'] }}</div>
        </div>
      @endforeach
    </div>
    <style>@media (max-width:900px){.metrics-grid{grid-template-columns:1fr 1fr!important}}</style>
  </div>
</section>

<section class="section" style="text-align:center">
  <div class="container">
    <a href="{{ home_url('/stories') }}" class="btn">
      Read the full success stories
      <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
        <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </a>
  </div>
</section>
@endsection
