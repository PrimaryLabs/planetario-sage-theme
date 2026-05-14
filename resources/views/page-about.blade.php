@extends('layouts.app')

@section('content')
@php use App\Data\StaticData; $values = StaticData::values(); @endphp

{{-- Intro --}}
<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-260px;top:-40px;opacity:.3" />
    <span class="eyebrow">About Planetario</span>
    <h1 class="display" style="margin-top:18px;max-width:18ch">
      A realty house built on <em>relationships,</em> not transactions.
    </h1>
    <p class="lead" style="margin-top:28px;max-width:60ch">
      Planetario Realty &amp; Brokerage Services Inc. is a trusted Bohol-rooted
      realty company dedicated to professional, reliable, and client-focused
      property solutions. We specialize in property sales, brokerage, and
      real-estate services across the Visayas — committed to helping clients
      find the right investments while ensuring smooth and transparent transactions.
    </p>
  </div>
</section>

{{-- Landscape image --}}
<section style="padding:0 0 60px">
  <div class="container">
    <div class="reveal" style="aspect-ratio:21/9;border-radius:14px;overflow:hidden;border:1px solid var(--line)">
      <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=1600&h=686&fit=crop&q=80"
           alt="Bohol landscape"
           style="width:100%;height:100%;object-fit:cover;filter:brightness(.85)">
    </div>
  </div>
</section>

{{-- Vision / Mission --}}
<section class="section" style="padding-top:0">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px" class="vm-grid">
      <div class="reveal reveal-left">
        <span class="eyebrow">Vision</span>
        <p style="font-family:var(--font-display);font-size:clamp(20px,2vw,28px);line-height:1.35;margin-top:18px;color:var(--ink)">
          To be a <em style="color:var(--accent);font-style:italic">world-class</em> real-estate
          company delivering exceptional service to clients, salespeople, business partners, and
          team members — transforming lives by creating opportunities for growth, empowering
          communities, and fostering progress, all while contributing to a sustainable future
          for our planet.
        </p>
      </div>
      <div class="reveal reveal-right" style="transition-delay:.15s">
        <span class="eyebrow">Mission</span>
        <p style="font-family:var(--font-display);font-size:clamp(20px,2vw,28px);line-height:1.35;margin-top:18px;color:var(--ink)">
          To deliver <em style="color:var(--accent);font-style:italic">world-class</em> services
          in the realty industry — ensuring our clients' happiness and complete satisfaction.
          We continuously enhance our competitive edge through innovation, motivation, and
          training, while fostering long-term relationships built on trust and excellence.
        </p>
      </div>
    </div>
  </div>
  <style>@media (max-width:860px){.vm-grid{grid-template-columns:1fr!important;gap:40px!important}}</style>
</section>

{{-- Core Values --}}
<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">Core values</span>
        <h2 class="h2" style="margin-top:14px">Five quiet commitments. <em>One steady house.</em></h2>
      </div>
      <p class="lead reveal" style="transition-delay:.1s">
        These aren't poster words. They show up in the smallest decisions: which call we
        return first, what we tell a buyer when a listing isn't right, who we hire and
        who we don't.
      </p>
    </div>
    <div class="stagger-children value-list">
      @foreach ($values as $v)
        <div class="value-row">
          <div class="num">{{ $v['n'] }}</div>
          <div class="ttl">{{ $v['t'] }}</div>
          <div class="desc">{{ $v['d'] }}</div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Why Choose Us --}}
<section class="section">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">Why choose Planetario</span>
        <h2 class="h2" style="margin-top:14px">Five practical reasons. <em>Felt, not advertised.</em></h2>
      </div>
    </div>
    <div class="stagger-children feature-grid">
      @foreach ([
        ['num' => 'I',   'title' => 'Licensed & experienced',     'desc' => 'PRC-licensed brokers backed by a senior team with two decades of Bohol and Cebu market experience.'],
        ['num' => 'II',  'title' => 'Strong network',              'desc' => 'Direct working relationships with six developers, surveyors, notaries, and BIR offices across both provinces.'],
        ['num' => 'III', 'title' => 'Transparent process',         'desc' => 'Every figure, every fee, every step — written down. Nothing surfaces late.'],
        ['num' => 'IV',  'title' => 'Client-first approach',       'desc' => 'Patient site visits. Plain-language explanations. No pressure, no rushed closings.'],
        ['num' => 'V',   'title' => 'Vetted inventory',            'desc' => 'Every listing passes a title, build-quality, and pricing review before it reaches our site.'],
        ['num' => 'VI',  'title' => 'End-to-end documentation',    'desc' => 'BIR, RD, LGU filings handled in-house. You sign — we walk the paper from start to finish.'],
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

{{-- Closing quote --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container" style="text-align:center">
    <p class="banner-quote">
      "We work with you every step of the way — from property selection to
      closing — ensuring a <em>hassle-free</em> and <em>secure</em> transaction."
    </p>
    <div style="margin-top:16px;font-family:var(--font-mono);font-size:11px;letter-spacing:.2em;text-transform:uppercase;color:var(--ink-3)">
      — A word from our founders
    </div>
    <div style="display:flex;justify-content:center;margin-top:36px;gap:12px;flex-wrap:wrap">
      <a href="{{ home_url('/team') }}" class="btn btn-primary">
        Meet the team
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <a href="{{ home_url('/contact') }}" class="btn">
        Get in touch
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>
@endsection
