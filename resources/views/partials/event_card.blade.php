{{-- /home/rodhni/tendocharity/resources/views/partials/event_card.blade.php --}}
<div class="card border-0 shadow-sm h-100">
    <div class="position-relative">
        <img src="{{ asset('assets/images/events/' . ($event->image ?? 'default.jpg')) }}" 
             alt="{{ $event->title }}" 
             class="card-img-top" 
             style="height: 200px; object-fit: cover;">
        <span class="position-absolute top-0 end-0 m-3 badge bg-{{ 
            $event->status === 'upcoming' ? 'primary' : 
            ($event->status === 'ongoing' ? 'success' : 'secondary') 
        }} rounded-pill">
            {{ ucfirst($event->status) }}
        </span>
        @if($event->featured ?? false)
        <span class="position-absolute top-0 start-0 m-3 badge bg-warning rounded-pill">
            Featured
        </span>
        @endif
    </div>
    
    <div class="card-body">
        <div class="d-flex align-items-center gap-2 text-muted small mb-2">
            <i class="las la-calendar"></i>
            <span>{{ $event->startDate->format('M d, Y') }}</span>
            <i class="las la-clock ms-2"></i>
            <span>{{ $event->startDate->format('h:i A') }}</span>
        </div>
        
        <h5 class="card-title mb-3">
            <a href="{{ route('event.details', ['id' => $event->id, 'slug' => Str::slug($event->title)]) }}" 
               class="text-decoration-none text-dark">
                {{ $event->title }}
            </a>
        </h5>
        
        <div class="d-flex align-items-center gap-2 text-muted small mb-3">
            <i class="las la-map-marker"></i>
            <span>{{ $event->location ?? ($event->type === 'virtual' ? 'Virtual Event' : 'Location TBD') }}</span>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <div class="small">
                <strong>{{ $event->applicants_count ?? 0 }}</strong> registered
            </div>
            <a href="{{ route('event.details', ['id' => $event->id, 'slug' => Str::slug($event->title)]) }}" 
               class="btn btn-sm btn-outline-primary">
                View Details <i class="las la-arrow-right"></i>
            </a>
        </div>
    </div>
</div>