@extends('layouts.frontend')

@section('title', 'Fundraisers')

@section('content')
<div class="container-fluid px-0">
    
    <!-- All Fundraisers Grid -->
    <section class="py-5 bg-light">
        <div class="container">
            <!-- Filters -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <form action="{{ url('fundraisers') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select name="category" id="category" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="urgency" class="form-label">Urgency</label>
                                    <select name="urgency" id="urgency" class="form-select">
                                        <option value="">All</option>
                                        <option value="normal" {{ request('urgency') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="urgent" {{ request('urgency') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        <option value="critical" {{ request('urgency') == 'critical' ? 'selected' : '' }}>Critical</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="sort" class="form-label">Sort By</label>
                                    <select name="sort" id="sort" class="form-select">
                                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest</option>
                                        <option value="raised_amount" {{ request('sort') == 'raised_amount' ? 'selected' : '' }}>Most Funded</option>
                                        <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Priority</option>
                                        <option value="end_date" {{ request('end_date') == 'end_date' ? 'selected' : '' }}>Ending Soon</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button type="submit" class="btn btn-primary flex-grow-1">
                                            <i class="fas fa-filter me-2"></i>Apply
                                        </button>
                                        <a href="{{ url('fundraisers') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-redo"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Fundraisers Grid -->
            @if($fundraisers->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                @foreach($fundraisers as $fundraiser)
                <div class="col">
                    <div class="card fundraiser-card h-100 border-0 shadow-sm">
                        <!-- Card Header -->
                        <div class="card-header position-relative p-0 border-0">
                            @if($fundraiser->is_featured)
                            <div class="featured-badge position-absolute top-0 start-0 m-3">
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-star me-1"></i> FEATURED
                                </span>
                            </div>
                            @endif
                            
                            @if($fundraiser->urgency_level === 'critical')
                            <div class="urgency-badge position-absolute top-0 end-0 m-3">
                                <span class="badge bg-danger px-3 py-2">
                                    CRITICAL
                                </span>
                            </div>
                            @elseif($fundraiser->urgency_level === 'urgent')
                            <div class="urgency-badge position-absolute top-0 end-0 m-3">
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    URGENT
                                </span>
                            </div>
                            @endif
                            
                            <!-- Image - FIXED PATH -->
                            <div class="fundraiser-image">
                                <img src="{{ $fundraiser->featured_image ? asset($fundraiser->featured_image) : asset('assets/images/default.png') }}" 
                                     alt="{{ $fundraiser->title }}"
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;"
                                     onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                <div class="image-overlay"></div>
                                
                                <!-- Category -->
                                @if($fundraiser->category)
                                <div class="category-overlay position-absolute bottom-0 start-0 m-3">
                                    <span class="category-badge px-3 py-1">
                                        {{ $fundraiser->category->name }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2">{{ Str::limit($fundraiser->title, 50) }}</h5>
                            
                            @if($fundraiser->tagline)
                            <p class="card-text text-muted mb-3">{{ Str::limit($fundraiser->tagline, 80) }}</p>
                            @endif
                            
                            <!-- Progress -->
                            <div class="progress mb-3" style="height: 8px;">
                                @php
                                    $progress = $fundraiser->target_amount > 0 ? 
                                        ($fundraiser->raised_amount / $fundraiser->target_amount) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-progress" 
                                     role="progressbar" 
                                     style="width: {{ $progress }}%"
                                     aria-valuenow="{{ $progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <div class="text-center">
                                        <div class="stat-value fw-bold text-primary">{{ $fundraiser->currency }} {{ number_format($fundraiser->raised_amount, 0) }}</div>
                                        <div class="stat-label text-muted small">Raised</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <div class="stat-value fw-bold">{{ $fundraiser->currency }} {{ number_format($fundraiser->target_amount, 0) }}</div>
                                        <div class="stat-label text-muted small">Goal</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        @php
                                            $daysRemaining = $fundraiser->end_date ? \Carbon\Carbon::parse($fundraiser->end_date)->diffInDays(now()) : null;
                                        @endphp
                                        <div class="stat-value fw-bold">{{ $daysRemaining !== null && $daysRemaining > 0 ? $daysRemaining : 0 }}</div>
                                        <div class="stat-label text-muted small">Days</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Location & Beneficiaries -->
                            <div class="fundraiser-meta d-flex justify-content-between text-muted small">
                                <div>
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $fundraiser->location ?? ($fundraiser->location_country ?? 'Global') }}
                                </div>
                                <div>
                                    <i class="fas fa-users me-1"></i>
                                    {{ $fundraiser->beneficiaries_count }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="card-footer bg-transparent border-top-0">
                            <div class="d-grid">
                                <a href="{{ route('fundraisers.show', $fundraiser->slug) }}" class="btn btn-primary">
                                    <i class="fas fa-heart me-2"></i>Support Cause
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Fundraiser pagination">
                        <ul class="pagination justify-content-center">
                            {{ $fundraisers->links() }}
                        </ul>
                    </nav>
                </div>
            </div>
            @else
            <!-- No Fundraisers -->
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info text-center py-5">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h3>No Fundraisers Found</h3>
                        <p class="mb-0">Try adjusting your filters or check back later for new campaigns.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>

<style>
.bg-progress {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.fundraiser-card {
    transition: all 0.3s ease;
    border-radius: 15px;
    overflow: hidden;
}

.fundraiser-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}

.fundraiser-image {
    position: relative;
    overflow: hidden;
}

.fundraiser-image img {
    transition: transform 0.5s ease;
}

.fundraiser-card:hover .fundraiser-image img {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(transparent, rgba(0,0,0,0.3));
    opacity: 0.5;
}

.category-badge {
    background: rgba(255, 255, 255, 0.9);
    color: #667eea;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    backdrop-filter: blur(10px);
}

.stat-value {
    font-size: 1.1rem;
    line-height: 1.2;
}

.stat-label {
    font-size: 0.75rem;
    margin-top: 2px;
}

.card-title {
    color: #212529;
    min-height: 48px;
}

.card-text {
    font-size: 0.9rem;
    min-height: 40px;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4290 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
    border-radius: 10px;
    padding: 10px 25px;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

.pagination .page-link {
    color: #667eea;
    border: none;
    margin: 0 5px;
    border-radius: 8px !important;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

.pagination .page-link:hover {
    background: rgba(102, 126, 234, 0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form auto-submit for sort
    document.getElementById('sort').addEventListener('change', function() {
        this.form.submit();
    });
    
    // Form auto-submit for category
    document.getElementById('category').addEventListener('change', function() {
        this.form.submit();
    });
    
    // Form auto-submit for urgency
    document.getElementById('urgency').addEventListener('change', function() {
        this.form.submit();
    });
    
    // Add error handling for broken images
    document.querySelectorAll('.fundraiser-image img').forEach(function(img) {
        img.addEventListener('error', function() {
            this.src = '{{ asset("assets/images/default.png") }}';
        });
    });
});
</script>
@endsection