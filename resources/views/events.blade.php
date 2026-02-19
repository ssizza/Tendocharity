@extends('layouts.frontend')

@section('content')
<div class="pt-120 pb-80">
    <div class="container">
        <!-- Page Header -->
        <div class="text-center mb-5">
            <h1 class="mb-3" style="color: hsl(var(--heading)); font-size: 2.5rem;">{{ $pageTitle }}</h1>
            @if($sections && isset($sections->secs->header->subheading))
                <p style="color: hsl(var(--body)); max-width: 600px; margin: 0 auto;">{{ $sections->secs->header->subheading }}</p>
            @endif
        </div>

        <!-- Event Navigation -->
        <div class="mb-5 p-4 rounded" style="background-color: hsl(var(--light-600));">
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <!-- Uncomment the "All Events" button if you want to show all events on a single page -->
                <!--
                <a href="{{ route('event.index') }}" 
                   class="btn btn-{{ request()->routeIs('event.index') ? '' : 'outline-' }}primary">
                    All Events
                </a>
                -->
                <a href="{{ route('event.upcoming') }}" 
                   class="btn btn-{{ request()->routeIs('event.upcoming') ? '' : 'outline-' }}primary">
                    Upcoming
                </a>
                <a href="{{ route('event.ongoing') }}" 
                   class="btn btn-{{ request()->routeIs('event.ongoing') ? '' : 'outline-' }}primary">
                    Ongoing
                </a>
                <a href="{{ route('event.completed') }}" 
                   class="btn btn-{{ request()->routeIs('event.completed') ? '' : 'outline-' }}primary">
                    Past Events
                </a>
                <a href="{{ route('event.virtual') }}" 
                   class="btn btn-{{ request()->routeIs('event.virtual') ? '' : 'outline-' }}primary">
                    Virtual
                </a>
                <a href="{{ route('event.physical') }}" 
                   class="btn btn-{{ request()->routeIs('event.physical') ? '' : 'outline-' }}primary">
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
                        <i class="las la-calendar-times fs-1" style="color: hsl(var(--body))"></i>
                        <h4 class="mt-3" style="color: hsl(var(--heading))">No events found</h4>
                        <p style="color: hsl(var(--body))">Check back later for events.</p>
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
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4 pb-3" 
                 style="border-bottom: 2px solid hsl(var(--border))">
                <h3 style="color: hsl(var(--heading)); margin: 0;">Currently Ongoing</h3>
                <a href="{{ route('event.ongoing') }}" 
                   style="color: hsl(var(--base)); text-decoration: none; font-size: 0.9rem;">
                    View All <i class="las la-arrow-right"></i>
                </a>
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
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4 pb-3" 
                 style="border-bottom: 2px solid hsl(var(--border))">
                <h3 style="color: hsl(var(--heading)); margin: 0;">Upcoming Events</h3>
                <a href="{{ route('event.upcoming') }}" 
                   style="color: hsl(var(--base)); text-decoration: none; font-size: 0.9rem;">
                    View All <i class="las la-arrow-right"></i>
                </a>
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
            <div class="d-flex justify-content-between align-items-center mt-4 mb-4 pb-3" 
                 style="border-bottom: 2px solid hsl(var(--border))">
                <h3 style="color: hsl(var(--heading)); margin: 0;">Recent Events</h3>
                <a href="{{ route('event.completed') }}" 
                   style="color: hsl(var(--base)); text-decoration: none; font-size: 0.9rem;">
                    View All <i class="las la-arrow-right"></i>
                </a>
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
                <i class="las la-calendar-times fs-1" style="color: hsl(var(--body))"></i>
                <h4 class="mt-3" style="color: hsl(var(--heading))">No events found</h4>
                <p style="color: hsl(var(--body))">Check back later for upcoming events.</p>
            </div>
            @endif
            
        @else
            <!-- No events found -->
            <div class="text-center py-5">
                <i class="las la-calendar-times fs-1" style="color: hsl(var(--body))"></i>
                <h4 class="mt-3" style="color: hsl(var(--heading))">No events found</h4>
                <p style="color: hsl(var(--body))">Check back later for upcoming events.</p>
            </div>
        @endif
    </div>
</div>
@endsection