@extends('layouts.app')

@section('content')
@php use App\Data\StaticData; $team = StaticData::team(); @endphp

<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-220px;top:-20px;opacity:.25" />
    <span class="eyebrow">The Planetario bench</span>
    <h1 class="display" style="margin-top:18px;max-width:18ch">
      The people you'll actually <em>shake hands with.</em>
    </h1>
    <p class="lead" style="margin-top:24px;max-width:62ch">
      Eight PRC-licensed brokers and associates between Tagbilaran and Cebu City.
      Most have lived in the Visayas their whole lives. None of them will hand your
      file to someone else once your name is on it.
    </p>
  </div>
</section>

<section class="section" style="padding-top:24px">
  <div class="container">
    <div class="stagger-children team-grid">
      @foreach ($team as $i => $member)
        <div class="team-card">
          <div class="media">
            <img src="https://i.pravatar.cc/400?u={{ urlencode($member['name']) }}"
                 alt="{{ esc_attr($member['name']) }}"
                 loading="lazy">
          </div>
          <div class="body">
            <div class="name">{{ $member['name'] }}</div>
            <div class="role">{{ $member['role'] }}</div>
            <p class="bio">{{ $member['bio'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:60px;align-items:center" class="join-grid">
      <div class="reveal">
        <span class="eyebrow">Sales partnership</span>
        <h2 class="h2" style="margin-top:14px">
          Want to join the <em>Planetario salesfloor?</em>
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        <p class="lead">
          We accredit a small, vetted network of independent sales associates across
          Bohol and Cebu. Strong training, generous splits, and the quiet credibility
          of working under a PRC-licensed brokerage.
        </p>
        <div style="margin-top:24px">
          <a href="{{ home_url('/contact') }}" class="btn btn-primary">
            Apply to partner
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>
  <style>@media (max-width:860px){.join-grid{grid-template-columns:1fr!important;gap:24px!important}}</style>
</section>
@endsection
