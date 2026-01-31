@extends('layouts.frontend')

@section('content')
<div class="container py-5">

    <!-- Service Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">{{ $service->title }}</h1>
            <div class="d-flex align-items-center mb-4">
                <span class="badge bg-success me-3">{{ $service->campaigns_count }} Active Campaigns</span>
                <small class="text-muted">
                    <i class="far fa-clock me-1"></i> Last updated: {{ showDateTime($service->updated_at, 'M d, Y') }}
                </small>
            </div>
        </div>
    </div>

    <!-- Service Image and Basic Info -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <img src="{{ $service->image_url }}" class="card-img-top" alt="{{ $service->title }}" style="height: 400px; object-fit: cover;">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-3">Quick Overview</h5>
                    
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-crosshairs me-2"></i> Our Mission
                        </h6>
                        <p class="card-text">{{ $service->mission }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-eye me-2"></i> Our Vision
                        </h6>
                        <p class="card-text">{{ $service->vision }}</p>
                    </div>
                    
                    @if($service->impact_summary)
                    <div class="mb-4">
                        <h6 class="text-primary mb-2">
                            <i class="fas fa-chart-line me-2"></i> Impact Summary
                        </h6>
                        <p class="card-text">{{ $service->impact_summary }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Service Description -->
    @if($service->description)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h3 class="card-title mb-4">About This Service</h3>
                    <div class="service-description">
                        {!! $service->description !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Related Campaigns -->
    @if($service->campaigns->isNotEmpty())
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="mb-4">Related Campaigns</h3>
            <div class="row">
                @foreach($service->campaigns as $campaign)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">{{ $campaign->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($campaign->description, 100) }}</p>
                            <a href="#" class="btn btn-outline-primary btn-sm">View Campaign</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Stories and Testimonials -->
    <div class="row">
        <!-- Stories -->
        @if($service->stories->isNotEmpty())
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">Success Stories</h3>
                    <div class="accordion" id="storiesAccordion">
                        @foreach($service->stories as $index => $story)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                    {{ $story->title }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#storiesAccordion">
                                <div class="accordion-body">
                                    {!! $story->content !!}
                                    @if($story->author_name)
                                    <div class="mt-3 text-end">
                                        <small class="text-muted">
                                            — {{ $story->author_name }}
                                            @if($story->author_position)
                                            , {{ $story->author_position }}
                                            @endif
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Testimonials -->
        @if($service->testimonials->isNotEmpty())
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">Testimonials</h3>
                    <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($service->testimonials as $index => $testimonial)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="testimonial-content p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-quote-left fa-2x text-primary opacity-25"></i>
                                    </div>
                                    <p class="mb-4">{!! $testimonial->content !!}</p>
                                    @if($testimonial->author_name)
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">{{ $testimonial->author_name }}</h6>
                                            @if($testimonial->author_position)
                                            <small class="text-muted">{{ $testimonial->author_position }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($service->testimonials->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('style')
<style>
    .service-description {
        line-height: 1.8;
    }
    
    .service-description h1,
    .service-description h2,
    .service-description h3,
    .service-description h4 {
        color: #333;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .service-description p {
        margin-bottom: 1rem;
    }
    
    .testimonial-content {
        min-height: 200px;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 5%;
    }
</style>
@endpush