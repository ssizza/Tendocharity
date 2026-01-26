@extends('layouts.frontend')

@section('title', 'Active Campaigns - Charity Organization')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto text-center">
            <h1 class="display-4 fw-bold mb-3">Support Our Campaigns</h1>
            <p class="lead text-muted">Join us in making a difference through these impactful campaigns</p>
        </div>
    </div>

    <!-- Urgent Campaigns -->
    @if($urgentCampaigns->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <div class="alert alert-danger border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div>
                        <h4 class="alert-heading mb-1">Urgent Appeals</h4>
                        <p class="mb-0">These campaigns need immediate support</p>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($urgentCampaigns as $campaign)
                <div class="col-lg-4">
                    <div class="card border-danger shadow-sm h-100">
                        <div class="position-relative">
                            <img src="{{ $campaign->image_url }}" class="card-img-top" alt="{{ $campaign->title }}" style="height: 200px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-danger">
                                    <i class="fas fa-bolt me-1"></i> URGENT
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $campaign->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($campaign->tagline, 100) }}</p>
                            
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-danger" role="progressbar" 
                                     style="width: {{ $campaign->funding_percentage }}%">
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <small class="text-muted">Raised</small>
                                    <div class="fw-bold text-danger">{{ $campaign->formatted_raised }}</div>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Goal</small>
                                    <div class="fw-bold">{{ $campaign->formatted_goal }}</div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i> {{ $campaign->donors_count }} donors
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i> {{ $campaign->days_remaining }} days left
                                </small>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-danger w-100">
                                <i class="fas fa-hand-holding-heart me-2"></i> Support Now
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- All Campaigns -->
    <div class="row">
        <div class="col-12 mb-4">
            <h3 class="fw-bold">All Campaigns</h3>
        </div>
        
        @forelse($campaigns as $campaign)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow">
                <div class="position-relative">
                    <img src="{{ $campaign->image_url }}" class="card-img-top" alt="{{ $campaign->title }}" style="height: 180px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($campaign->is_urgent)
                        <span class="badge bg-danger">URGENT</span>
                        @else
                        <span class="badge bg-primary">{{ $campaign->urgency_level }}</span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    <small class="text-primary d-block mb-2">
                        <i class="fas fa-hands-helping me-1"></i> {{ $campaign->service->title }}
                    </small>
                    
                    <h5 class="card-title fw-bold">{{ Str::limit($campaign->title, 60) }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($campaign->tagline, 100) }}</p>
                    
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $campaign->funding_percentage }}%">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <small class="text-muted">Raised</small>
                            <div class="fw-bold">{{ $campaign->formatted_raised }}</div>
                        </div>
                        <div class="text-end">
                            <small class="text-muted">Goal</small>
                            <div class="fw-bold">{{ $campaign->formatted_goal }}</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i> {{ $campaign->donors_count }} supporters
                        </small>
                        <small class="text-muted">
                            @if($campaign->days_remaining !== null)
                            <i class="fas fa-clock me-1"></i> {{ $campaign->days_remaining }} days
                            @endif
                        </small>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent border-top-0">
                    <a href="{{ route('campaigns.show', $campaign->slug) }}" class="btn btn-outline-primary w-100">
                        View Details <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-hands-helping fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No campaigns available</h4>
                <p>Check back soon for new campaigns</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($campaigns->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            {{ $campaigns->links() }}
        </div>
    </div>
    @endif

    @auth
    <div class="text-center mt-5">
        <a href="{{ route('campaigns.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus me-2"></i> Create New Campaign
        </a>
    </div>
    @endauth
</div>
@endsection