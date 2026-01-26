@extends('layouts.frontend')

@section('content')
<div class="pt-120 pb-120">
    <div class="container">
        <!-- Event Banner -->
        <div class="event-banner mb-4">
            <img src="{{ asset('assets/images/events/' . $event->image) }}" alt="{{ $event->title }}" class="w-100 rounded">
            @if($event->status === 'upcoming')
            <span class="event-status-badge">Upcoming</span>
            @elseif($event->status === 'ongoing')
            <span class="event-status-badge ongoing">Ongoing</span>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Event Details -->
                <div class="event-details card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h1 class="event-title mb-4">{{ $event->title }}</h1>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="las la-calendar text-primary fs-4"></i>
                                    <div>
                                        <h6 class="mb-0">Date & Time</h6>
                                        <p class="mb-0">
                                            {{ $event->startDate->format('l, F d, Y') }}<br>
                                            {{ $event->startDate->format('h:i A') }} - {{ $event->endDate->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="las la-map-marker text-primary fs-4"></i>
                                    <div>
                                        <h6 class="mb-0">Location</h6>
                                        <p class="mb-0">{{ $event->location }}</p>
                                        <small class="text-muted">{{ $event->type === 'virtual' ? 'Virtual Event' : 'In-person Event' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="event-description mb-4">
                            <h4 class="mb-3">About This Event</h4>
                            <div class="content">
                                @php
                                    $description = json_decode($event->description);
                                @endphp
                                @if(isset($description->short_description))
                                    <p class="lead">{{ $description->short_description }}</p>
                                @endif
                                
                                @if(isset($description->full_description))
                                    {!! $description->full_description !!}
                                @else
                                    <p>{{ $event->title }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 flex-wrap mb-4">
                            @if($isOpenForBooking)
                            <a href="#booking" class="btn btn--primary">
                                <i class="las la-ticket-alt"></i> Book Your Spot
                            </a>
                            @endif
                            
                            <a href="{{ route('event.calendar', $event->id) }}" class="btn btn--outline" target="_blank">
                                <i class="las la-calendar-plus"></i> Add to Google Calendar
                            </a>
                            
                            <button class="btn btn--outline" onclick="shareEvent()">
                                <i class="las la-share-alt"></i> Share Event
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Event Gallery -->
                @if($gallery->count() > 0)
                <div class="event-gallery card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">Event Gallery</h4>
                        <div class="row g-3">
                            @foreach($gallery as $image)
                            <div class="col-md-4 col-sm-6">
                                <a href="{{ asset('assets/images/events/gallery/' . $image->image_url) }}" class="gallery-item" data-lightbox="gallery">
                                    <img src="{{ asset('assets/images/events/gallery/' . $image->image_url) }}" alt="{{ $image->alt ?? 'Event Image' }}" class="img-fluid rounded">
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Booking Form -->
                @if($isOpenForBooking)
                <div class="booking-form card border-0 shadow-sm" id="booking">
                    <div class="card-body">
                        <h4 class="mb-4">Book Your Spot</h4>
                        <p class="text-muted mb-4">Fill out the form below to register for this event. We'll send you a confirmation email with all the details.</p>
                        
                        <form method="POST" action="{{ route('event.book', $event->id) }}" class="disableSubmission">
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label required">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label required">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label required">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone', auth()->user()->mobile ?? '') }}" required>
                                        @error('phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to receive updates about this event via email
                                        </label>
                                    </div>
                                    
                                    <button type="submit" class="btn btn--primary">
                                        <i class="las la-paper-plane"></i> Submit Registration
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="las la-info-circle"></i> 
                    @if($event->status === 'completed')
                        This event has already been completed. Thank you to everyone who participated!
                    @elseif($event->status === 'cancelled')
                        This event has been cancelled.
                    @elseif($event->status === 'ongoing')
                        This event is currently ongoing. Registration may be closed.
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Event Stats -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Event Information</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Status</span>
                                <span class="badge bg-{{ $event->status === 'upcoming' ? 'primary' : ($event->status === 'ongoing' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Type</span>
                                <span>{{ ucfirst($event->type) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Registrations</span>
                                <span>{{ $applicantsCount }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Date</span>
                                <span>{{ $event->startDate->format('M d, Y') }}</span>
                            </li>
                            <li class="list-group-item">
                                <small class="text-muted">
                                    <i class="las la-clock"></i> 
                                    {{ $event->startDate->diffForHumans() }}
                                </small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Share Event -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Share This Event</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="shareFacebook()">
                                <i class="lab la-facebook-f"></i> Facebook
                            </button>
                            <button class="btn btn-sm btn-outline-info flex-fill" onclick="shareTwitter()">
                                <i class="lab la-twitter"></i> Twitter
                            </button>
                            <button class="btn btn-sm btn-outline-danger flex-fill" onclick="shareEmail()">
                                <i class="las la-envelope"></i> Email
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Related Events -->
                @if($relatedEvents->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Upcoming Events</h5>
                        <div class="related-events">
                            @foreach($relatedEvents as $relatedEvent)
                            <div class="related-event-item mb-3">
                                <div class="d-flex gap-3">
                                    <img src="{{ asset('assets/images/events/' . $relatedEvent->image) }}" 
                                         alt="{{ $relatedEvent->title }}" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('event.details', ['id' => $relatedEvent->id, 'slug' => Str::slug($relatedEvent->title)]) }}" class="text-dark">
                                                {{ Str::limit($relatedEvent->title, 40) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="las la-calendar"></i> 
                                            {{ $relatedEvent->startDate->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.event-banner {
    position: relative;
}

.event-status-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--base-color);
    color: #fff;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    z-index: 1;
}

.event-status-badge.ongoing {
    background: #28a745;
}

.event-title {
    color: #333;
    font-weight: 700;
    font-size: 2rem;
}

.gallery-item {
    display: block;
    overflow: hidden;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.gallery-item:hover {
    transform: scale(1.05);
}

.gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.related-event-item {
    padding: 10px;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.related-event-item:hover {
    background: #f8f9fa;
}

.related-event-item h6 a {
    text-decoration: none;
    color: #333;
}

.related-event-item h6 a:hover {
    color: var(--base-color);
}

.required::after {
    content: " *";
    color: #dc3545;
}

.btn--outline {
    background: transparent;
    border: 2px solid var(--base-color);
    color: var(--base-color);
}

.btn--outline:hover {
    background: var(--base-color);
    color: #fff;
}

.btn--primary {
    background: var(--base-color);
    border: 2px solid var(--base-color);
    color: #fff;
}

.btn--primary:hover {
    background: darken(var(--base-color), 10%);
    border-color: darken(var(--base-color), 10%);
}
</style>
@endpush

@push('script')
<script>
function shareEvent() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $event->title }}',
            text: 'Join me at this event!',
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copied to clipboard!');
    }
}

function shareFacebook() {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank');
}

function shareTwitter() {
    window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('{{ $event->title }}') + '&url=' + encodeURIComponent(window.location.href), '_blank');
}

function shareEmail() {
    window.location.href = 'mailto:?subject=' + encodeURIComponent('{{ $event->title }}') + '&body=' + encodeURIComponent('Check out this event: ' + window.location.href);
}
</script>
@endpush