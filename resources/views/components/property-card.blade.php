@props(['property'])
@php($p = $property)
<a href="{{ home_url('/properties/' . $p['id']) }}" class="prop-card reveal">
  <div class="media">
    @if (!empty($p['image']))
      <img src="{{ $p['image'] }}" alt="{{ $p['name'] }}" loading="lazy">
    @else
      <div style="width:100%;height:100%;background:var(--bg-3);display:grid;place-items:center">
        <span style="font-family:var(--font-display);font-size:18px;color:var(--ink-3)">{{ $p['name'][0] }}</span>
      </div>
    @endif
    <span class="tag">{{ $p['status'] }}</span>
  </div>
  <div class="body">
    <div class="loc">
      <svg width="11" height="11" viewBox="0 0 12 12" fill="none" aria-hidden="true">
        <path d="M6 11s4-3.6 4-6.5A4 4 0 0 0 2 4.5C2 7.4 6 11 6 11z" stroke="currentColor" stroke-width="1.2"/>
        <circle cx="6" cy="4.5" r="1.2" stroke="currentColor" stroke-width="1.2"/>
      </svg>
      {{ $p['location'] }}
    </div>
    <h3 class="ttl">{{ $p['name'] }}</h3>
    <div class="specs">
      @if (!empty($p['beds']))
        <span>
          <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M2 12V5m12 7V8a2 2 0 0 0-2-2H6m-4 6h12m-12 0v1m12-1v1" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/><circle cx="5" cy="8" r="1.2" stroke="currentColor" stroke-width="1.2"/></svg>
          {{ $p['beds'] }} bd
        </span>
      @endif
      @if (!empty($p['baths']))
        <span>
          <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M2 9h12M3 9v3a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V9M5 9V5a1.5 1.5 0 0 1 3 0v.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>
          {{ $p['baths'] }} ba
        </span>
      @endif
      @if (!empty($p['area']))
        <span>
          <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><rect x="2.5" y="2.5" width="11" height="11" stroke="currentColor" stroke-width="1.2"/><path d="M2.5 6.5h11M6.5 2.5v11" stroke="currentColor" stroke-width="1.2" opacity=".5"/></svg>
          {{ $p['area'] }} sqm
        </span>
      @elseif (!empty($p['lot']))
        <span>
          <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><rect x="2.5" y="2.5" width="11" height="11" stroke="currentColor" stroke-width="1.2"/><path d="M2.5 6.5h11M6.5 2.5v11" stroke="currentColor" stroke-width="1.2" opacity=".5"/></svg>
          {{ $p['lot'] }} sqm lot
        </span>
      @endif
    </div>
    <div class="price">{{ $p['priceLabel'] }}<small>{{ $p['type'] }}</small></div>
  </div>
</a>
