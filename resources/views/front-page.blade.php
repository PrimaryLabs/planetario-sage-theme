@extends('layouts.app')

@section('content')
@php
  use App\Data\StaticData;
  $properties  = array_slice(StaticData::properties(), 0, 6);
  $services    = StaticData::services();
  $testimonials = array_slice(StaticData::testimonials(), 0, 2);
@endphp

{{-- Hero --}}
<section class="hero">
  <div class="hero-bg">
    <img src="https://picsum.photos/seed/planetario-hero/1600/900" alt="" aria-hidden="true">
  </div>
  <div class="hero-overlay"></div>
  <x-orbit-deco />

  <div class="container hero-inner">
    <span class="eyebrow">Bohol · Cebu · Visayas</span>
    <h1 class="display hero-headline">
      A home is the long answer to a <em>short prayer.</em>
    </h1>
    <p class="hero-sub">
      Planetario Realty &amp; Brokerage Services Inc. has guided Boholano families,
      OFW investors, and first-time buyers across the Visayas for nearly a decade —
      with patience, with paperwork done right, and with the kind of honesty that
      keeps clients coming back.
    </p>
    <div class="hero-actions">
      <a href="{{ home_url('/properties') }}" class="btn btn-primary">
        Browse properties
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
      <a href="{{ home_url('/contact') }}" class="btn">
        Talk to a broker
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
          <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </a>
    </div>

    <div class="hero-meta">
      <div class="item">
        <div class="num"><span data-countup="2018" data-decimals="0">2018</span></div>
        <div class="lbl">Established</div>
      </div>
      <div class="item">
        <div class="num">₱<span data-countup="2.4" data-decimals="1" data-suffix="B+">2.4B+</span></div>
        <div class="lbl">Transactions Closed</div>
      </div>
      <div class="item">
        <div class="num"><span data-countup="420" data-suffix="+">420+</span></div>
        <div class="lbl">Families Placed</div>
      </div>
      <div class="item">
        <div class="num"><span data-countup="6">6</span></div>
        <div class="lbl">Developer Partners</div>
      </div>
    </div>
  </div>
</section>

{{-- Commitment --}}
<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:60px;align-items:center" class="home-intro-grid">
      <div class="reveal reveal-left">
        <span class="eyebrow">Our commitment</span>
        <h2 class="h2" style="margin-top:14px">
          We walk with you from <em>first viewing</em> to final signature.
        </h2>
      </div>
      <div class="reveal reveal-right" style="transition-delay:.12s">
        <p class="lead">
          You don't have to worry about documentation, coordination, or negotiation.
          We handle it all — and we'll tell you to wait if the unit isn't right.
          That kind of honesty is rarer than a clean title.
        </p>
        <p class="lead" style="margin-top:18px">
          Most of our clients are referrals from clients. That's a number we work
          quietly to keep true.
        </p>
        <div style="margin-top:28px">
          <a href="{{ home_url('/about') }}" class="btn">
            Read our story
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>
  <style>@media (max-width:860px){.home-intro-grid{grid-template-columns:1fr!important;gap:24px!important}}</style>
</section>

{{-- Featured properties --}}
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">Featured listings</span>
        <h2 class="h2" style="margin-top:14px">
          A small selection from <em>this season's</em> portfolio.
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        <p class="lead">
          From Panglao beachfront villas to Cebu IT Park penthouses — handpicked
          listings that have passed our title, build, and pricing checks.
        </p>
        <div style="margin-top:18px">
          <a href="{{ home_url('/properties') }}" class="btn">
            View all {{ count(StaticData::properties()) }} listings
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </div>
    </div>

    <div class="stagger-children prop-grid">
      @foreach ($properties as $p)
        <x-property-card :property="$p" />
      @endforeach
    </div>
  </div>
</section>

{{-- Services --}}
<section class="section" style="background:var(--bg-2)">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">What we do</span>
        <h2 class="h2" style="margin-top:14px">Six services. <em>One promise.</em></h2>
      </div>
      <p class="lead reveal" style="transition-delay:.1s">
        Property goals are personal. Our services are built around the actual
        decisions you'll need to make — not the listings we want to push.
      </p>
    </div>
    <div class="stagger-children feature-grid">
      @foreach ($services as $s)
        <div class="feature">
          <div class="num">{{ $s['num'] }}</div>
          <div class="ttl">{{ $s['title'] }}</div>
          <p class="desc">{{ $s['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- Locations --}}
<section class="section">
  <div class="container">
    <div class="section-head">
      <div class="reveal">
        <span class="eyebrow">Where we work</span>
        <h2 class="h2" style="margin-top:14px">Two islands. <em>One quiet sea between them.</em></h2>
      </div>
      <p class="lead reveal" style="transition-delay:.1s">
        Planetario operates from Tagbilaran, with active brokerage across Bohol's
        coastal towns and a senior team in Cebu City.
      </p>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:28px" class="loc-grid">
      <div class="reveal reveal-left" style="position:relative;border-radius:14px;overflow:hidden;aspect-ratio:4/3;border:1px solid var(--line)">
        <img src="https://picsum.photos/seed/bohol-loc/800/600" alt="Bohol"
             style="width:100%;height:100%;object-fit:cover;filter:brightness(.7)">
        <div style="position:absolute;inset:0;padding:32px;display:flex;flex-direction:column;justify-content:flex-end;background:linear-gradient(180deg,transparent 40%,rgba(6,13,31,.9))">
          <span class="eyebrow">Bohol — home</span>
          <h3 class="h2" style="font-size:clamp(22px,2.4vw,36px);margin-top:10px">Tagbilaran, Panglao, Loboc, Carmen, Anda</h3>
          <p class="muted" style="margin-top:8px;max-width:36ch">Our headquarters and where we know every road, every notary, and every honest builder.</p>
        </div>
      </div>
      <div class="reveal reveal-right" style="position:relative;border-radius:14px;overflow:hidden;aspect-ratio:4/3;border:1px solid var(--line);transition-delay:.1s">
        <img src="https://picsum.photos/seed/cebu-loc/800/600" alt="Cebu"
             style="width:100%;height:100%;object-fit:cover;filter:brightness(.7)">
        <div style="position:absolute;inset:0;padding:32px;display:flex;flex-direction:column;justify-content:flex-end;background:linear-gradient(180deg,transparent 40%,rgba(6,13,31,.9))">
          <span class="eyebrow">Cebu — sister team</span>
          <h3 class="h2" style="font-size:clamp(22px,2.4vw,36px);margin-top:10px">Cebu City, Mactan, Talisay, Liloan</h3>
          <p class="muted" style="margin-top:8px;max-width:36ch">Urban and bayfront — condominiums, commercial floors, and gated family residences.</p>
        </div>
      </div>
    </div>
  </div>
  <style>@media (max-width:800px){.loc-grid{grid-template-columns:1fr!important}}</style>
</section>

{{-- Testimonials snippet --}}
<section class="section" style="padding-top:0">
  <div class="container">
    <div class="stagger-children" style="display:grid;grid-template-columns:1fr 1fr;gap:28px" class="t-grid">
      @foreach ($testimonials as $t)
        <div class="testimonial">
          <div class="quote-mk">"</div>
          <blockquote>{{ $t['quote'] }}</blockquote>
          <div class="who">
            <img src="https://i.pravatar.cc/96?u={{ urlencode($t['name']) }}" alt="" width="48" height="48">
            <div>
              <div class="name">{{ $t['name'] }}</div>
              <div class="role">{{ $t['role'] }}</div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
    <div style="margin-top:28px;text-align:center">
      <a href="{{ home_url('/testimonials') }}" class="btn">
        More testimonials
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
  <style>@media (max-width:800px){.t-grid{grid-template-columns:1fr!important}}</style>
</section>

{{-- CTA Banner --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
  <div class="container" style="position:relative;text-align:center">
    <x-orbit-deco style="right:auto;left:-180px;top:-50px;width:380px;height:380px;opacity:.25" />
    <p class="banner-quote">
      Tell us what your next <em>five years</em> should look like.<br>
      We'll find the address.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:36px;flex-wrap:wrap">
      <a href="{{ home_url('/contact') }}" class="btn btn-primary">
        Book a tripping
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
      <a href="{{ home_url('/properties') }}" class="btn">
        Browse listings
        <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>
@endsection
