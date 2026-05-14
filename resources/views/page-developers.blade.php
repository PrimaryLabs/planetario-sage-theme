@extends('layouts.app')

@section('content')
@php use App\Data\StaticData; $devs = StaticData::developers(); @endphp

<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
    <span class="eyebrow">Developer partners</span>
    <h1 class="display" style="margin-top:18px;max-width:20ch">
      The builders we <em>quietly stake our name on.</em>
    </h1>
    <p class="lead" style="margin-top:22px;max-width:64ch">
      We don't sell every developer in the Visayas — only the ones whose
      specifications, craft, and after-sales we can vouch for. Each name
      below has been a working partner of Planetario for at least three years.
    </p>
  </div>
</section>

<section class="section" style="padding-top:24px">
  <div class="container">
    <div class="stagger-children dev-list">
      @foreach ($devs as $d)
        <div class="dev">
          <div>
            <div class="name">{{ $d['name'] }}</div>
            <div class="meta">
              <span>{{ implode(' · ', $d['locations']) }}</span>
              <span style="color:var(--line-2)">·</span>
              <span style="color:var(--ink-3)">{{ $d['portfolio'] }}</span>
            </div>
            <p class="desc">{{ $d['desc'] }}</p>
          </div>
          <div class="sigil">{{ $d['sigil'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">How we work with developers</span>
        <h2 class="h2" style="margin-top:14px">A small list. <em>Carefully kept.</em></h2>
      </div>
      <p class="lead reveal" style="transition-delay:.1s">
        Our partnership is a stake on both sides. Here is what working with Planetario
        typically looks like — for the builder, and for the buyer.
      </p>
    </div>

    <div class="stagger-children feature-grid">
      @foreach ([
        ['num' => '01', 'title' => 'Site visit & build audit',  'desc' => 'Before any unit reaches our listings, our senior brokers walk the site and inspect at least three completed projects from that developer.'],
        ['num' => '02', 'title' => 'Pricing benchmark',         'desc' => 'We benchmark every developer offering against comparable open-market inventory so our buyers see fair, current numbers.'],
        ['num' => '03', 'title' => 'Exclusive corridors',       'desc' => 'For several partners we hold first-look or exclusive selling rights in specific towns — Panglao, Anda, Carmen, and parts of Mactan.'],
        ['num' => '04', 'title' => 'Co-launch marketing',       'desc' => 'We co-fund the launch campaign, photography, and qualified-buyer events for selected developer phases.'],
        ['num' => '05', 'title' => 'Buyer protection',          'desc' => 'We never market a phase whose pre-selling pricing or turnover dates we don\'t believe the developer can honor.'],
        ['num' => '06', 'title' => 'Long view',                 'desc' => 'Our partnerships are measured in decades, not units. Every developer here has been with us for at least three years.'],
      ] as $s)
        <div class="feature">
          <div class="num">{{ $s['num'] }}</div>
          <div class="ttl">{{ $s['title'] }}</div>
          <p class="desc">{{ $s['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:60px;align-items:center" class="join-grid">
      <div class="reveal">
        <span class="eyebrow">For developers</span>
        <h2 class="h2" style="margin-top:14px">
          Are you a Visayan developer with a <em>project worth selling well?</em>
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        <p class="lead">
          We accept a small number of new developer mandates each year. If your phase
          is in Bohol, Cebu, Negros, or Siquijor — and your build and after-sales are
          something you'd let your own family buy into — we'd like to hear from you.
        </p>
        <div style="margin-top:24px">
          <a href="{{ home_url('/contact') }}" class="btn btn-primary">
            Submit your project
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>
  <style>@media (max-width:860px){.join-grid{grid-template-columns:1fr!important;gap:24px!important}}</style>
</section>
@endsection
