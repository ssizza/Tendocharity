@php
use Illuminate\Support\Str;
$description = json_decode($event->description);
$shortDesc = $description->short_description ?? $event->title;
@endphp

<div class="event-card">
    <div class="event-card__img">
        <img src="{{ asset('assets/images/events/' . $event->image) }}" alt="{{ $event->title }}">
        @if($event->status === 'ongoing')
        <span class="event-card__badge event-card__badge--ongoing">Ongoing</span>
        @elseif($event->status === 'upcoming')
        <span class="event-card__badge">Upcoming</span>
        @endif
    </div>
    <div class="event-card__content">
        <h4 class="event-card__title">
            <a href="{{ route('event.details', ['id' => $event->id, 'slug' => Str::slug($event->title)]) }}">{{ $event->title }}</a>
        </h4>
        <ul class="event-card__meta">
            <li><i class="las la-calendar"></i> {{ $event->startDate->format('M d, Y') }}</li>
            <li><i class="las la-clock"></i> {{ $event->startDate->format('h:i A') }}</li>
            <li><i class="las la-map-marker"></i> {{ $event->location }}</li>
        </ul>
        <p class="event-card__desc">
            {{ Str::limit(strip_tags($shortDesc), 100) }}
        </p>
        <div class="d-flex gap-2">
            <a href="{{ route('event.details', ['id' => $event->id, 'slug' => Str::slug($event->title)]) }}" class="btn btn--base btn--sm">View Details</a>
            @if($event->isOpenForBooking())
            <a href="{{ route('event.details', ['id' => $event->id, 'slug' => Str::slug($event->title)]) }}#booking" class="btn btn--primary btn--sm">Book Now</a>
            @endif
        </div>
    </div>
</div>