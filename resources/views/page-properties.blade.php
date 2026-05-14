@extends('layouts.app')

@section('content')
@php
  $all = $properties ?? [];
  usort($all, fn($a, $b) => ($b['price'] ?? 0) <=> ($a['price'] ?? 0));
  $types = $propertyTypes ?? [];
  $tagChips = array_values(array_unique(array_merge(['All'], $propertyTags ?? [])));
@endphp

<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-220px;top:-40px;opacity:.22" />
    @if ($pageIntro['eyebrow'])<span class="eyebrow">{{ $pageIntro['eyebrow'] }} · {{ count($all) }} listings</span>@endif
    <h1 class="display" style="margin-top:18px;max-width:18ch">
      {{ $pageIntro['headlineLead'] }}@if ($pageIntro['headlineEm']) <em>{{ $pageIntro['headlineEm'] }}</em>@endif@if ($pageIntro['headlineTrail']) {{ $pageIntro['headlineTrail'] }}@endif
    </h1>
    @if ($pageIntro['lead'])
      <p class="lead" style="margin-top:22px;max-width:62ch">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

{{-- Filters (JS-driven) --}}
<section style="padding:0 0 12px">
  <div class="container">
    <div class="filters" id="prop-filters">
      <div class="f">
        <label for="pf-q">Search</label>
        <input id="pf-q" type="text" placeholder="Town, project, or feature">
      </div>
      <div class="f">
        <label for="pf-region">Region</label>
        <select id="pf-region">
          <option value="">All</option>
          <option>Bohol</option>
          <option>Cebu</option>
        </select>
      </div>
      <div class="f">
        <label for="pf-type">Type</label>
        <select id="pf-type">
          <option value="">All</option>
          @foreach ($types as $t)
            <option>{{ $t }}</option>
          @endforeach
        </select>
      </div>
      <div class="f">
        <label for="pf-price">Max price</label>
        <select id="pf-price">
          <option value="">Any</option>
          <option value="10000000">Under ₱10M</option>
          <option value="25000000">Under ₱25M</option>
          <option value="50000000">Under ₱50M</option>
        </select>
      </div>
      <button class="btn" id="pf-reset">Reset</button>
    </div>

    <div class="chips" style="margin-top:22px" id="tag-chips">
      @foreach ($tagChips as $tag)
        <button class="chip {{ $tag === 'All' ? 'active' : '' }}" data-tag="{{ $tag }}">{{ $tag }}</button>
      @endforeach
    </div>
  </div>
</section>

{{-- Listings grid --}}
<section class="section" style="padding-top:36px">
  <div class="container">
    <div class="tag-row" style="margin-bottom:28px" id="prop-count-row">
      <span id="prop-count">{{ count($all) }} of {{ count($all) }} listings</span>
      <span class="sep">·</span>
      <span>Updated this week</span>
      <span class="sep">·</span>
      <span>Sorted by price, descending</span>
    </div>

    <div class="prop-grid" id="prop-grid">
      @foreach ($all as $p)
        <x-property-card :property="$p"
          data-name="{{ strtolower($p['name']) }}"
          data-location="{{ strtolower($p['location']) }}"
          data-region="{{ $p['region'] }}"
          data-type="{{ $p['type'] }}"
          data-price="{{ $p['price'] }}"
          data-tags="{{ implode(',', $p['tags'] ?? []) }}" />
      @endforeach
    </div>

    <div id="prop-empty" style="display:none;padding:80px 0;text-align:center;border:1px dashed var(--line-2);border-radius:14px;background:var(--bg-2)">
      <p class="lead" style="margin:0 auto">
        No listings match those filters yet. Try widening the price range, or contact us
        directly — we often hold matching properties not yet on the public site.
      </p>
      <div style="margin-top:24px">
        <a href="{{ home_url('/contact') }}" class="btn btn-primary">
          Send us a private brief
          <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
      </div>
    </div>
  </div>
</section>

{{-- Private brief CTA --}}
<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container" style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center" class="private-grid">
    <div class="reveal">
      @if ($pageClosing['eyebrow'])<span class="eyebrow">{{ $pageClosing['eyebrow'] }}</span>@endif
      <h2 class="h2" style="margin-top:14px">
        {{ $pageClosing['headlineLead'] }}@if ($pageClosing['headlineEm']) <em>{{ $pageClosing['headlineEm'] }}</em>@endif
      </h2>
    </div>
    <div class="reveal" style="transition-delay:.1s">
      @if ($pageClosing['body'])<p class="lead">{{ $pageClosing['body'] }}</p>@endif
      @if ($pageClosing['primaryLabel'])
        <div style="margin-top:22px">
          <a href="{{ $pageClosing['primaryUrl'] }}" class="btn btn-primary">
            {{ $pageClosing['primaryLabel'] }}
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true"><path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      @endif
    </div>
  </div>
  <style>@media (max-width:860px){.private-grid{grid-template-columns:1fr!important;gap:24px!important}}</style>
</section>

<script>
(function () {
  const grid      = document.getElementById('prop-grid');
  const empty     = document.getElementById('prop-empty');
  const countEl   = document.getElementById('prop-count');
  const cards     = Array.from(grid.querySelectorAll('.prop-card'));
  const total     = cards.length;

  const qEl       = document.getElementById('pf-q');
  const regionEl  = document.getElementById('pf-region');
  const typeEl    = document.getElementById('pf-type');
  const priceEl   = document.getElementById('pf-price');
  const resetBtn  = document.getElementById('pf-reset');
  const chipBtns  = document.querySelectorAll('#tag-chips .chip');

  let activeTag = 'All';

  function filter() {
    const q       = qEl.value.toLowerCase().trim();
    const region  = regionEl.value;
    const type    = typeEl.value;
    const maxP    = priceEl.value ? parseInt(priceEl.value, 10) : null;

    let shown = 0;
    cards.forEach(card => {
      const name     = card.dataset.name     || '';
      const location = card.dataset.location || '';
      const cRegion  = card.dataset.region   || '';
      const cType    = card.dataset.type     || '';
      const cPrice   = parseInt(card.dataset.price, 10) || 0;
      const cTags    = (card.dataset.tags || '').split(',');

      const matchQ      = !q      || name.includes(q) || location.includes(q);
      const matchRegion = !region || cRegion === region;
      const matchType   = !type   || cType === type;
      const matchPrice  = !maxP   || cPrice <= maxP;
      const matchTag    = activeTag === 'All' || cTags.includes(activeTag);

      const visible = matchQ && matchRegion && matchType && matchPrice && matchTag;
      card.style.display = visible ? '' : 'none';
      if (visible) shown++;
    });

    countEl.textContent = shown + ' of ' + total + ' listings';
    empty.style.display = shown === 0 ? '' : 'none';
    grid.style.display  = shown === 0 ? 'none' : '';
  }

  [qEl, regionEl, typeEl, priceEl].forEach(el => el.addEventListener('input', filter));

  chipBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      chipBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeTag = btn.dataset.tag;
      filter();
    });
  });

  resetBtn.addEventListener('click', () => {
    qEl.value = regionEl.value = typeEl.value = priceEl.value = '';
    activeTag = 'All';
    chipBtns.forEach(b => b.classList.toggle('active', b.dataset.tag === 'All'));
    filter();
  });
})();
</script>
@endsection
