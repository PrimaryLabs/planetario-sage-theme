@extends('layouts.app')

@section('content')
@php
use App\Data\StaticData;

$team     = $team ?? StaticData::team();
$founders = $founders ?? array_values(array_filter($team, fn($m) => ($m['tier'] ?? '') === 'founder'));
$groups   = $teamGroups ?? [
  ['key' => 'managers', 'label' => 'Managers', 'eyebrow' => 'Leadership', 'members' => array_values(array_filter($team, fn($m) => ($m['tier'] ?? '') === 'manager'))],
  ['key' => 'brokers',  'label' => 'Brokers',  'eyebrow' => 'Salesfloor', 'members' => array_values(array_filter($team, fn($m) => ($m['tier'] ?? '') === 'broker'))],
  ['key' => 'staffs',   'label' => 'Staff',    'eyebrow' => 'Support',    'members' => array_values(array_filter($team, fn($m) => ($m['tier'] ?? '') === 'staff'))],
];
@endphp

<section class="section" style="padding-top:140px">
  <div class="container" style="position:relative">
    <x-orbit-deco style="right:-220px;top:-20px;opacity:.25" />
    @if ($pageIntro['eyebrow'])<span class="eyebrow">{{ $pageIntro['eyebrow'] }}</span>@endif
    <h1 class="display" style="margin-top:18px;max-width:18ch">
      {{ $pageIntro['headlineLead'] }}@if ($pageIntro['headlineEm']) <em>{{ $pageIntro['headlineEm'] }}</em>@endif@if ($pageIntro['headlineTrail']) {{ $pageIntro['headlineTrail'] }}@endif
    </h1>
    @if ($pageIntro['lead'])
      <p class="lead" style="margin-top:24px;max-width:62ch">{{ $pageIntro['lead'] }}</p>
    @endif
  </div>
</section>

{{-- Founders --}}
<section class="section" style="padding-top:24px">
  <div class="container">
    <div class="team-section-head">
      <span class="eyebrow">Board of Directors</span>
      <h2 class="h2" style="margin-top:10px">The people who started <em>the firm.</em></h2>
    </div>

    <div class="stagger-children team-grid team-grid--founders" style="margin-top:36px">
      @foreach ($founders as $member)
      <div class="team-card team-card--featured">
        <div class="media">
          <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/500?u=' . urlencode($member['name'])) }}"
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

{{-- The bench: each sub-group has its own region tabs --}}
<section class="section" style="padding-top:24px">
  <div class="container">
    <div class="team-section-head ">
      <span class="eyebrow">The bench</span>
      <h2 class="h2" style="margin-top:10px">Managers, brokers, <em>and the people who file your papers.</em></h2>
    </div>

    @foreach ($groups as $group)
    <div class="team-group" data-team-group>
      <div class="team-group-head">
        <div class="team-group-head-meta">
          <span class="team-group-eyebrow">{{ $group['eyebrow'] }}</span>
          <div class="flex gap-4">
            <h3 class="team-group-title uppercase font-bold">{{ $group['label'] }}</h3>
            <span class="team-group-count" data-team-count>{{ count($group['members']) }}</span>
          </div>
        </div>
        <div class="chips" role="tablist" aria-label="Filter {{ strtolower($group['label']) }} by region">
          <button type="button" class="chip active" data-region="all" role="tab" aria-selected="true">All</button>
          <button type="button" class="chip" data-region="bohol" role="tab" aria-selected="false">Bohol</button>
          <button type="button" class="chip" data-region="cebu" role="tab" aria-selected="false">Cebu</button>
        </div>
      </div>

      <div class="stagger-children team-grid">
        @foreach ($group['members'] as $member)
        <div class="team-card" data-region="{{ $member['region'] }}">
          <div class="media">
            <img src="{{ $member['photo'] ?? ('https://i.pravatar.cc/400?u=' . urlencode($member['name'])) }}"
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

      <p class="team-group-empty" data-team-empty hidden>
        No {{ strtolower($group['label']) }} based in this region yet.
      </p>
    </div>
    @endforeach
  </div>
</section>

<section class="section" style="background:var(--bg-2);border-top:1px solid var(--line)">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:60px;align-items:center" class="join-grid">
      <div class="reveal">
        @if ($pageClosing['eyebrow'])<span class="eyebrow">{{ $pageClosing['eyebrow'] }}</span>@endif
        <h2 class="h2" style="margin-top:14px">
          {{ $pageClosing['headlineLead'] }}@if ($pageClosing['headlineEm']) <em>{{ $pageClosing['headlineEm'] }}</em>@endif
        </h2>
      </div>
      <div class="reveal" style="transition-delay:.1s">
        @if ($pageClosing['body'])<p class="lead">{{ $pageClosing['body'] }}</p>@endif
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

<script>
  (() => {
    document.querySelectorAll('[data-team-group]').forEach((group) => {
      const chips = group.querySelectorAll('.chip[data-region]');
      const cards = group.querySelectorAll('.team-card[data-region]');
      const countEl = group.querySelector('[data-team-count]');
      const emptyEl = group.querySelector('[data-team-empty]');

      const applyFilter = (region) => {
        let visible = 0;
        cards.forEach((card) => {
          const match = region === 'all' || card.dataset.region === region;
          card.hidden = !match;
          if (match) visible++;
        });
        if (countEl) countEl.textContent = visible;
        if (emptyEl) emptyEl.hidden = visible !== 0;
      };

      chips.forEach((chip) => {
        chip.addEventListener('click', () => {
          chips.forEach((c) => {
            c.classList.toggle('active', c === chip);
            c.setAttribute('aria-selected', c === chip ? 'true' : 'false');
          });
          applyFilter(chip.dataset.region);
        });
      });
    });
  })();
</script>
@endsection