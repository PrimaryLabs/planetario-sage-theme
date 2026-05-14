@extends('layouts.app')

@section('content')
@php($p = $property)

@if (! $p)
  <section class="section" style="padding-top:140px">
    <div class="container">
      <h1 class="display">Property not found.</h1>
      <p class="lead" style="margin-top:18px"><a href="{{ home_url('/properties') }}" class="btn">Back to listings</a></p>
    </div>
  </section>
@else

{{-- Hero --}}
<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-260px;top:-40px;opacity:.22" />
    <div style="display:grid;grid-template-columns:1.2fr 1fr;gap:48px;align-items:start" class="prop-hero-grid">
      <div>
        <span class="eyebrow">{{ $p['region'] }} @if ($p['type']) · {{ $p['type'] }}@endif</span>
        <h1 class="display" style="margin-top:14px;max-width:18ch">{{ $p['name'] }}</h1>
        @if ($p['location'])
          <p class="lead" style="margin-top:18px">
            <svg width="14" height="14" viewBox="0 0 12 12" fill="none" aria-hidden="true" style="vertical-align:-2px;margin-right:6px">
              <path d="M6 11s4-3.6 4-6.5A4 4 0 0 0 2 4.5C2 7.4 6 11 6 11z" stroke="currentColor" stroke-width="1.2"/>
              <circle cx="6" cy="4.5" r="1.2" stroke="currentColor" stroke-width="1.2"/>
            </svg>
            {{ $p['location'] }}
          </p>
        @endif
        @if ($p['summary'])
          <p class="lead" style="margin-top:22px;max-width:60ch">{{ $p['summary'] }}</p>
        @endif

        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:32px">
          @if ($p['priceLabel'])
            <div style="background:var(--bg-2);border:1px solid var(--line);border-radius:12px;padding:18px 24px">
              <div class="eyebrow">Price</div>
              <div style="font-family:var(--font-display);font-size:28px;color:var(--accent);margin-top:4px">{{ $p['priceLabel'] }}</div>
            </div>
          @endif
          @if ($p['status'])
            <div style="background:var(--bg-2);border:1px solid var(--line);border-radius:12px;padding:18px 24px">
              <div class="eyebrow">Status</div>
              <div style="font-family:var(--font-display);font-size:22px;margin-top:4px">{{ $p['status'] }}</div>
            </div>
          @endif
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:24px">
          <a href="{{ home_url('/contact') }}?ref={{ urlencode($p['name']) }}" class="btn btn-primary">
            Inquire about this property
            <svg class="arr" width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
              <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </a>
          <a href="{{ home_url('/properties') }}" class="btn">Back to listings</a>
        </div>
      </div>

      <div>
        @if ($p['image'])
          <div style="border-radius:14px;overflow:hidden;border:1px solid var(--line);aspect-ratio:4/3">
            <img src="{{ $p['image'] }}" alt="{{ $p['name'] }}" style="width:100%;height:100%;object-fit:cover">
          </div>
        @endif
        <div class="prop-specs" style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-top:18px">
          @foreach ([
            ['lbl' => 'Beds',  'val' => $p['beds']],
            ['lbl' => 'Baths', 'val' => $p['baths']],
            ['lbl' => 'Floor', 'val' => $p['area'] ? $p['area'] . ' sqm' : null],
            ['lbl' => 'Lot',   'val' => $p['lot'] ? $p['lot'] . ' sqm' : null],
          ] as $spec)
            @if (! empty($spec['val']))
              <div style="background:var(--bg-2);border:1px solid var(--line);border-radius:10px;padding:14px;text-align:center">
                <div class="eyebrow">{{ $spec['lbl'] }}</div>
                <div style="font-family:var(--font-display);font-size:20px;margin-top:4px">{{ $spec['val'] }}</div>
              </div>
            @endif
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <style>@media (max-width:980px){.prop-hero-grid{grid-template-columns:1fr!important}}</style>
</section>

{{-- Features + Gallery --}}
@if (! empty($p['features']) || ! empty($gallery))
  <section class="section" style="padding-top:36px">
    <div class="container">
      <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:48px;align-items:start" class="prop-detail-grid">
        @if (! empty($p['features']))
          <div>
            <span class="eyebrow">Features</span>
            <ul style="list-style:none;padding:0;margin-top:18px;display:grid;gap:10px">
              @foreach ($p['features'] as $feature)
                <li style="display:flex;gap:10px;align-items:flex-start;font-size:15px;line-height:1.55;color:var(--ink-2)">
                  <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:var(--accent);margin-top:9px;flex-shrink:0"></span>
                  {{ $feature }}
                </li>
              @endforeach
            </ul>
          </div>
        @endif
        @if (! empty($gallery))
          <div>
            <span class="eyebrow">Gallery</span>
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-top:18px">
              @foreach ($gallery as $img)
                <div style="border-radius:10px;overflow:hidden;border:1px solid var(--line);aspect-ratio:4/3">
                  <img src="{{ $img['url'] }}" alt="{{ $img['alt'] }}" style="width:100%;height:100%;object-fit:cover" loading="lazy">
                </div>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </div>
    <style>@media (max-width:860px){.prop-detail-grid{grid-template-columns:1fr!important}}</style>
  </section>
@endif

{{-- Tags --}}
@if (! empty($p['tags']))
  <section class="section" style="padding-top:0">
    <div class="container">
      <div class="chips" style="margin-top:18px">
        @foreach ($p['tags'] as $tag)
          <span class="chip">{{ $tag }}</span>
        @endforeach
      </div>
    </div>
  </section>
@endif

{{-- Related --}}
@if (! empty($relatedProperties))
  <section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
    <div class="container">
      <div class="section-head-col">
        <span class="eyebrow">Similar listings</span>
        <h2 class="h2" style="margin-top:14px">More in <em>{{ $p['region'] ?: $p['type'] }}</em>.</h2>
      </div>
      <div class="stagger-children prop-grid">
        @foreach ($relatedProperties as $rp)
          <x-property-card :property="$rp" />
        @endforeach
      </div>
    </div>
  </section>
@endif

@endif
@endsection
