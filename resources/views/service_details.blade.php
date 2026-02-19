@extends('layouts.frontend')

@section('content')
<div class="container pt-60 pb-60">

    <!-- Service Header -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold mb-3">{{ $service->title }}</h1>
            <div class="d-flex align-items-center mb-4 flex-wrap gap-3">
                <span class="badge badge--success">{{ $service->campaigns_count }} @lang('Active Campaigns')</span>
                <small class="text--body">
                    <i class="lar la-clock me-1"></i> @lang('Last updated'): {{ showDateTime($service->updated_at, 'M d, Y') }}
                </small>
            </div>
        </div>
    </div>

    <!-- Service Image and Basic Info -->
    <div class="row mb-5 gy-4">
        <div class="col-lg-8">
            <div class="custom--card">
                <img src="{{ $service->image_url }}" class="w-100" alt="{{ $service->title }}" style="height: 400px; object-fit: cover; border-radius: 5px;">
            </div>
        </div>
        <div class="col-lg-4">
            <div class="custom--card h-100">
                <div class="card-body">
                    <h5 class="card-title pb-3 border-bottom">@lang('Quick Overview')</h5>
                    
                    <div class="mb-4">
                        <h6 class="text--base mb-2">
                            <i class="las la-crosshairs me-2"></i> @lang('Our Mission')
                        </h6>
                        <p class="card-text text--body">{{ $service->mission }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text--base mb-2">
                            <i class="las la-eye me-2"></i> @lang('Our Vision')
                        </h6>
                        <p class="card-text text--body">{{ $service->vision }}</p>
                    </div>
                    
                    @if($service->impact_summary)
                    <div class="mb-4">
                        <h6 class="text--base mb-2">
                            <i class="las la-chart-line me-2"></i> @lang('Impact Summary')
                        </h6>
                        <p class="card-text text--body">{{ $service->impact_summary }}</p>
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
            <div class="custom--card">
                <div class="card-body">
                    <h3 class="card-title mb-4">@lang('About This Service')</h3>
                    <div class="service-description text--body">
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
            <h3 class="title mb-4">@lang('Related Campaigns')</h3>
            <div class="row gy-4">
                @foreach($service->campaigns as $campaign)
                <div class="col-md-4">
                    <div class="custom--card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{ $campaign->title }}</h5>
                            <p class="card-text text--body mt-2">{{ Str::limit($campaign->description, 100) }}</p>
                            <a href="#" class="cmn--btn btn--outline btn--sm mt-3 d-inline-block">@lang('View Campaign')</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Stories and Testimonials -->
    <div class="row gy-4">
        <!-- Stories -->
        @if($service->stories->isNotEmpty())
        <div class="col-lg-6">
            <div class="custom--card h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">@lang('Success Stories')</h3>
                    <div class="accordion" id="storiesAccordion">
                        @foreach($service->stories as $index => $story)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $index }}">
                                    {{ $story->title }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $index }}" data-bs-parent="#storiesAccordion">
                                <div class="accordion-body text--body">
                                    {!! $story->content !!}
                                    @if($story->author_name)
                                    <div class="mt-3 text-end">
                                        <small class="text--body">
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
        <div class="col-lg-6">
            <div class="custom--card h-100">
                <div class="card-body">
                    <h3 class="card-title mb-4">@lang('Testimonials')</h3>
                    <div id="testimonialsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($service->testimonials as $index => $testimonial)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="testimonial-content">
                                    <div class="mb-3">
                                        <i class="las la-quote-left la-3x text--base opacity-50"></i>
                                    </div>
                                    <p class="mb-4 text--body">{!! $testimonial->content !!}</p>
                                    @if($testimonial->author_name)
                                    <div>
                                        <h6 class="mb-0 title">{{ $testimonial->author_name }}</h6>
                                        @if($testimonial->author_position)
                                        <small class="text--body">{{ $testimonial->author_position }}</small>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if($service->testimonials->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true" style="filter: invert(1);"></span>
                            <span class="visually-hidden">@lang('Previous')</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(1);"></span>
                            <span class="visually-hidden">@lang('Next')</span>
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
        color: hsl(var(--heading));
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
    
    /* Accordion styling to match theme */
    .accordion-item {
        border: 1px solid hsl(var(--border));
        margin-bottom: 10px;
        border-radius: 5px;
    }
    
    .accordion-button {
        color: hsl(var(--heading));
        background-color: hsl(var(--light));
    }
    
    .accordion-button:not(.collapsed) {
        color: hsl(var(--base));
        background-color: hsl(var(--base)/0.05);
    }
    
    .accordion-button:focus {
        box-shadow: 0 0 0 0.2rem hsl(var(--base)/0.25);
    }
    
    .accordion-button::after {
        background-image: none;
        content: "\f107";
        font-family: 'Line Awesome Free';
        font-weight: 900;
        width: auto;
        height: auto;
        transform: rotate(0deg);
    }
    
    .accordion-button:not(.collapsed)::after {
        background-image: none;
        transform: rotate(180deg);
    }
</style>
@endpush