@extends('layouts.frontend')


@section('content')
<div class="container py-5">
    <div class="row">
        @foreach($services as $service)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <div class="position-relative">
                    <img src="{{ $service->image_url }}" class="card-img-top" alt="{{ $service->title }}" style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-primary">{{ $service->campaigns_count }} Campaigns</span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $service->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($service->mission, 100) }}</p>
                    <div class="mb-3">
                        <small class="text-primary">
                            <i class="fas fa-bullseye me-1"></i> {{ Str::limit($service->vision, 80) }}
                        </small>
                    </div>
                    <a href="{{ route('service.details', $service->slug) }}" class="btn btn-outline-primary">
                        Learn More <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-chart-line me-1"></i> Impact: {{ $service->impact_summary ? Str::limit($service->impact_summary, 40) : 'Making Progress' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection