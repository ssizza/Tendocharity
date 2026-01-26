@if(isset($ongoingEvents) && $ongoingEvents->count() > 0)
<div class="mb-5">
    <h3 class="mb-4">Ongoing Events</h3>
    <div class="row g-4">
        @foreach($ongoingEvents as $event)
        <div class="col-lg-4 col-md-6">
            @include('partials.event_card', ['event' => $event])
        </div>
        @endforeach
    </div>
</div>
@endif

@if(isset($events) && $events->count() > 0)
<div class="row g-4">
    @foreach($events as $event)
    <div class="col-lg-4 col-md-6">
        @include('partials.event_card', ['event' => $event])
    </div>
    @endforeach
</div>

@if($events->hasPages())
<div class="mt-5">
    {{ $events->links() }}
</div>
@endif

@else
<div class="text-center py-5">
    <i class="las la-calendar-times display-1 text-muted"></i>
    <h4 class="mt-3">No events scheduled</h4>
    <p class="text-muted">Check back soon for upcoming events!</p>
</div>
@endif