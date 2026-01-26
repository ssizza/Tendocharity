@extends('layouts.frontend')


@section('title', $service->title . ' - Charity Organization')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 fw-bold mb-3">{{ $service->title }}</h1>
            <p class="lead">{{ $service->mission }}</p>
        </div>
    </div>

    <!-- Service Details -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <img src="{{ $service->image_url }}" class="card-img-top" alt="{{ $service->title }}" style="max-height: 400px; object-fit: cover;">
                <div class="card-body">
                    <h3 class="card-title fw-bold">About This Service</h3>
                    <div class="content mb-4">
                        {!! $service->description !!}
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded">
                                <h5 class="fw-bold text-primary">
                                    <i class="fas fa-bullseye me-2"></i> Our Vision
                                </h5>
                                <p class="mb-0">{{ $service->vision }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 bg-light rounded">
                                <h5 class="fw-bold text-primary">
                                    <i class="fas fa-trophy me-2"></i> Impact
                                </h5>
                                <p class="mb-0">{{ $service->impact_summary }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Active Campaigns -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-fire me-2"></i> Active Campaigns
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($service->campaigns as $campaign)
                    <div class="campaign-item mb-3 pb-3 border-bottom">
                        <h6 class="fw-bold">{{ $campaign->title }}</h6>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $campaign->funding_percentage }}%">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">{{ $campaign->funding_percentage }}% funded</span>
                            <span class="text-primary">{{ $campaign->formatted_raised }} raised</span>
                        </div>
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-sm btn-outline-primary mt-2 w-100">
                            Support Now
                        </a>
                    </div>
                    @empty
                    <p class="text-muted text-center">No active campaigns</p>
                    @endforelse
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="fw-bold mb-4">Service Impact</h5>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="display-6 fw-bold text-primary">{{ $service->campaigns->count() }}</div>
                            <small class="text-muted">Campaigns</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="display-6 fw-bold text-primary">
                                {{ $service->campaigns->sum('beneficiaries_count') }}
                            </div>
                            <small class="text-muted">Beneficiaries</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stories & Case Studies -->
    @if($service->stories->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Success Stories & Case Studies</h3>
            <div class="row g-4">
                @foreach($service->stories as $story)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if($story->image)
                        <img src="{{ $story->story_image_url }}" class="card-img-top" alt="{{ $story->title }}" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <span class="badge bg-info mb-2">{{ ucfirst($story->type) }}</span>
                            <h5 class="card-title fw-bold">{{ $story->title }}</h5>
                            <p class="card-text">{{ Str::limit($story->content, 150) }}</p>
                            @if($story->author_name)
                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i> {{ $story->author_name }}
                                    @if($story->author_position)
                                    <br><small>{{ $story->author_position }}</small>
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
    @endif

    @auth
    <div class="text-center mt-5">
        <a href="{{ route('campaigns.create') }}?service={{ $service->id }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i> Create Campaign in this Service
        </a>
    </div>
    @endauth
</div>
@endsection