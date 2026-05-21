@props(['photos' => [], 'eyebrow' => '', 'headline' => ''])

@if (!empty($photos))
<div class="og" data-og>

  {{-- Main preview --}}
  <div class="og__stage">
    <img
      class="og__main"
      src="{{ $photos[0]['url'] }}"
      alt="{{ $photos[0]['alt'] }}"
      loading="eager"
    >
  </div>

  {{-- Thumbnail rail — only shown when there is more than one photo --}}
  @if (count($photos) > 1)
  <div class="og__rail" role="list" aria-label="Photo thumbnails">
    @foreach ($photos as $index => $photo)
    <button
      class="og__thumb {{ $loop->first ? 'is-active' : '' }}"
      type="button"
      data-og-thumb="{{ $index }}"
      data-og-src="{{ $photo['url'] }}"
      data-og-alt="{{ $photo['alt'] }}"
      aria-label="{{ $photo['alt'] ?: 'Photo ' . ($index + 1) }}"
      aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
      role="listitem"
    >
      <img src="{{ $photo['url'] }}" alt="" aria-hidden="true" loading="lazy">
    </button>
    @endforeach
  </div>
  @endif

</div>
@endif
