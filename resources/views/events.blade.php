@extends('layouts.frontend')

@section('content')
<div class="pt-120 pb-80">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header mb-5">
            <h1 class="page-title">{{ $pageTitle }}</h1>
            @if($sections && isset($sections->secs->header->subheading))
                <p class="page-desc">{{ $sections->secs->header->subheading }}</p>
            @endif
        </div>

        <!-- Event Navigation -->
        <div class="event-navigation mb-5">
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('event.index') }}" class="btn btn-{{ request()->routeIs('event.index') ? 'primary' : 'outline-primary' }}">
                    All Events
                </a>
                <a href="{{ route('event.upcoming') }}" class="btn btn-{{ request()->routeIs('event.upcoming') ? 'primary' : 'outline-primary' }}">
                    Upcoming
                </a>
                <a href="{{ route('event.ongoing') }}" class="btn btn-{{ request()->routeIs('event.ongoing') ? 'primary' : 'outline-primary' }}">
                    Ongoing
                </a>
                <a href="{{ route('event.completed') }}" class="btn btn-{{ request()->routeIs('event.completed') ? 'primary' : 'outline-primary' }}">
                    Past Events
                </a>
                <a href="{{ route('event.virtual') }}" class="btn btn-{{ request()->routeIs('event.virtual') ? 'primary' : 'outline-primary' }}">
                    Virtual
                </a>
                <a href="{{ route('event.physical') }}" class="btn btn-{{ request()->routeIs('event.physical') ? 'primary' : 'outline-primary' }}">
                    In-person
                </a>
            </div>
        </div>

        <!-- Display events based on current route -->
        @if(isset($events) && $events->count() > 0)
            <!-- Paginated Events View -->
            <div class="row g-4">
                @forelse($events as $event)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    @include('partials.event_card', ['event' => $event])
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="las la-calendar-times fs-1 text-muted"></i>
                        <h4 class="mt-3">No events found</h4>
                        <p class="text-muted">Check back later for events.</p>
                    </div>
                </div>
                @endforelse
            </div>
            
            @if($events->hasPages())
            <div class="mt-5">
                {{ $events->links() }}
            </div>
            @endif
            
        @elseif(isset($upcomingEvents) || isset($ongoingEvents) || isset($recentCompleted))
            <!-- Dashboard View (Main events page) -->
            
            @if(isset($ongoingEvents) && $ongoingEvents->count() > 0)
            <div class="section-header mb-4">
                <h3 class="section-title">Currently Ongoing</h3>
                <a href="{{ route('event.ongoing') }}" class="section-link">View All</a>
            </div>
            <div class="row g-4 mb-5">
                @foreach($ongoingEvents as $event)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    @include('partials.event_card', ['event' => $event])
                </div>
                @endforeach
            </div>
            @endif

            @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
            <div class="section-header mb-4">
                <h3 class="section-title">Upcoming Events</h3>
                <a href="{{ route('event.upcoming') }}" class="section-link">View All</a>
            </div>
            <div class="row g-4 mb-5">
                @foreach($upcomingEvents as $event)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    @include('partials.event_card', ['event' => $event])
                </div>
                @endforeach
            </div>
            @endif

            @if(isset($recentCompleted) && $recentCompleted->count() > 0)
            <div class="section-header mb-4">
                <h3 class="section-title">Recent Events</h3>
                <a href="{{ route('event.completed') }}" class="section-link">View All</a>
            </div>
            <div class="row g-4">
                @foreach($recentCompleted as $event)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    @include('partials.event_card', ['event' => $event])
                </div>
                @endforeach
            </div>
            @endif

            @if((!isset($ongoingEvents) || $ongoingEvents->count() == 0) && 
                (!isset($upcomingEvents) || $upcomingEvents->count() == 0) && 
                (!isset($recentCompleted) || $recentCompleted->count() == 0))
            <div class="text-center py-5">
                <i class="las la-calendar-times fs-1 text-muted"></i>
                <h4 class="mt-3">No events found</h4>
                <p class="text-muted">Check back later for upcoming events.</p>
            </div>
            @endif
            
        @else
            <!-- No events found -->
            <div class="text-center py-5">
                <i class="las la-calendar-times fs-1 text-muted"></i>
                <h4 class="mt-3">No events found</h4>
                <p class="text-muted">Check back later for upcoming events.</p>
            </div>
        @endif
    </div>
</div>
@endsection

@push('style')
<style>
.page-header {
    text-align: center;
    margin-bottom: 3rem;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1rem;
}

.page-desc {
    font-size: 1.1rem;
    color: #666;
    max-width: 600px;
    margin: 0 auto;
}

.event-navigation {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.section-link {
    color: var(--base-color);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
}

.section-link:hover {
    text-decoration: underline;
}

.btn-outline-primary {
    color: var(--base-color);
    border-color: var(--base-color);
    background: transparent;
}

.btn-outline-primary:hover {
    background-color: var(--base-color);
    border-color: var(--base-color);
    color: white;
}

.btn-primary {
    background-color: var(--base-color);
    border-color: var(--base-color);
}
</style>
@endpush