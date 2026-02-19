@extends('layouts.frontend')

@section('title', 'Fundraisers')

@section('content')
<div class="container-fluid px-0">
    
    <!-- All Fundraisers Grid -->
    <section class="py-5" style="background-color: hsl(var(--light));">
        <div class="container">
            <!-- Filters -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card border-0" style="background-color: hsl(var(--white)); box-shadow: 0 0.125rem 0.375rem hsl(var(--dark)/0.05); border-radius: 0.5rem;">
                        <div class="card-body p-4">
                            <form action="{{ url('fundraisers') }}" method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <label for="category" class="form-label" style="color: hsl(var(--body)); font-weight: 500;">Category</label>
                                    <select name="category" id="category" class="form-select" style="border: 1px solid hsl(var(--border)); border-radius: 0.5rem; height: 45px;">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="urgency" class="form-label" style="color: hsl(var(--body)); font-weight: 500;">Urgency</label>
                                    <select name="urgency" id="urgency" class="form-select" style="border: 1px solid hsl(var(--border)); border-radius: 0.5rem; height: 45px;">
                                        <option value="">All</option>
                                        <option value="normal" {{ request('urgency') == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="urgent" {{ request('urgency') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        <option value="critical" {{ request('urgency') == 'critical' ? 'selected' : '' }}>Critical</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="sort" class="form-label" style="color: hsl(var(--body)); font-weight: 500;">Sort By</label>
                                    <select name="sort" id="sort" class="form-select" style="border: 1px solid hsl(var(--border)); border-radius: 0.5rem; height: 45px;">
                                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest</option>
                                        <option value="raised_amount" {{ request('sort') == 'raised_amount' ? 'selected' : '' }}>Most Funded</option>
                                        <option value="priority" {{ request('sort') == 'priority' ? 'selected' : '' }}>Priority</option>
                                        <option value="end_date" {{ request('end_date') == 'end_date' ? 'selected' : '' }}>Ending Soon</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="d-flex gap-2 w-100">
                                        <button type="submit" class="btn flex-grow-1" style="background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-700)) 100%); border: none; border-radius: 0.5rem; padding: 10px 20px; color: hsl(var(--white)); transition: all 0.3s ease;">
                                            <i class="fas fa-filter me-2"></i>Apply
                                        </button>
                                        <a href="{{ url('fundraisers') }}" class="btn" style="border: 1px solid hsl(var(--border)); border-radius: 0.5rem; padding: 10px 20px; color: hsl(var(--body)); background: transparent;">
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
                    <div class="card h-100 border-0" style="background-color: hsl(var(--white)); border-radius: 1rem; overflow: hidden; transition: all 0.3s ease;">
                        <!-- Card Header -->
                        <div class="card-header position-relative p-0 border-0" style="background: transparent;">
                            @if($fundraiser->is_featured)
                            <div class="featured-badge position-absolute top-0 start-0 m-3" style="z-index: 2;">
                                <span class="badge px-3 py-2" style="background-color: hsl(var(--warning)); color: hsl(var(--dark)); border-radius: 2rem;">
                                    <i class="fas fa-star me-1"></i> FEATURED
                                </span>
                            </div>
                            @endif
                            
                            @if($fundraiser->urgency_level === 'critical')
                            <div class="urgency-badge position-absolute top-0 end-0 m-3" style="z-index: 2;">
                                <span class="badge px-3 py-2" style="background-color: hsl(var(--danger)); color: hsl(var(--white)); border-radius: 2rem;">
                                    CRITICAL
                                </span>
                            </div>
                            @elseif($fundraiser->urgency_level === 'urgent')
                            <div class="urgency-badge position-absolute top-0 end-0 m-3" style="z-index: 2;">
                                <span class="badge px-3 py-2" style="background-color: hsl(var(--warning)); color: hsl(var(--dark)); border-radius: 2rem;">
                                    URGENT
                                </span>
                            </div>
                            @endif
                            
                            <!-- Image -->
                            <div class="position-relative" style="overflow: hidden;">
                                <img src="{{ $fundraiser->featured_image ? asset($fundraiser->featured_image) : asset('assets/images/default.png') }}" 
                                     alt="{{ $fundraiser->title }}"
                                     class="w-100" 
                                     style="height: 200px; object-fit: cover; transition: transform 0.5s ease;"
                                     onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(transparent, hsl(var(--dark)/0.3)); opacity: 0.5;"></div>
                                
                                <!-- Category -->
                                @if($fundraiser->category)
                                <div class="position-absolute bottom-0 start-0 m-3" style="z-index: 2;">
                                    <span class="px-3 py-1" style="background: hsl(var(--white)/0.9); color: hsl(var(--base)); border-radius: 2rem; font-weight: 600; font-size: 0.8rem; backdrop-filter: blur(10px);">
                                        {{ $fundraiser->category->name }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body">
                            <h5 class="fw-bold mb-2" style="color: hsl(var(--heading)); min-height: 48px;">{{ Str::limit($fundraiser->title, 50) }}</h5>
                            
                            @if($fundraiser->tagline)
                            <p class="mb-3" style="color: hsl(var(--body)); font-size: 0.9rem; min-height: 40px;">{{ Str::limit($fundraiser->tagline, 80) }}</p>
                            @endif
                            
                            <!-- Progress -->
                            <div class="progress mb-3" style="height: 8px; background-color: hsl(var(--border));">
                                @php
                                    $progress = $fundraiser->target_amount > 0 ? 
                                        ($fundraiser->raised_amount / $fundraiser->target_amount) * 100 : 0;
                                @endphp
                                <div class="progress-bar" 
                                     role="progressbar" 
                                     style="width: {{ $progress }}%; background: linear-gradient(90deg, hsl(var(--base)) 0%, hsl(var(--base-700)) 100%);"
                                     aria-valuenow="{{ $progress }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100"></div>
                            </div>
                            
                            <!-- Stats -->
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <div class="text-center">
                                        <div class="fw-bold" style="color: hsl(var(--base)); font-size: 1.1rem; line-height: 1.2;">{{ $fundraiser->currency }} {{ number_format($fundraiser->raised_amount, 0) }}</div>
                                        <div class="small" style="color: hsl(var(--body)); font-size: 0.75rem;">Raised</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        <div class="fw-bold" style="color: hsl(var(--heading)); font-size: 1.1rem; line-height: 1.2;">{{ $fundraiser->currency }} {{ number_format($fundraiser->target_amount, 0) }}</div>
                                        <div class="small" style="color: hsl(var(--body)); font-size: 0.75rem;">Goal</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-center">
                                        @php
                                            $daysRemaining = $fundraiser->end_date ? \Carbon\Carbon::parse($fundraiser->end_date)->diffInDays(now()) : null;
                                        @endphp
                                        <div class="fw-bold" style="color: hsl(var(--heading)); font-size: 1.1rem; line-height: 1.2;">{{ $daysRemaining !== null && $daysRemaining > 0 ? $daysRemaining : 0 }}</div>
                                        <div class="small" style="color: hsl(var(--body)); font-size: 0.75rem;">Days</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Location & Beneficiaries -->
                            <div class="d-flex justify-content-between small" style="color: hsl(var(--body));">
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
                        <div class="card-footer bg-transparent border-top-0 pt-0">
                            <div class="d-grid">
                                <a href="{{ route('fundraisers.show', $fundraiser->slug) }}" class="btn" style="background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-700)) 100%); border: none; border-radius: 0.5rem; padding: 10px 20px; color: hsl(var(--white)); transition: all 0.3s ease;">
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
                        <ul class="pagination justify-content-center" style="gap: 0.5rem;">
                            {{ $fundraisers->links('pagination::bootstrap-4') }}
                        </ul>
                    </nav>
                </div>
            </div>
            @else
            <!-- No Fundraisers -->
            <div class="row">
                <div class="col-12">
                    <div class="alert text-center py-5" style="background-color: hsl(var(--info)/0.1); color: hsl(var(--body)); border: 1px solid hsl(var(--info)/0.2); border-radius: 0.5rem;">
                        <i class="fas fa-search fa-3x mb-3" style="color: hsl(var(--info));"></i>
                        <h3 style="color: hsl(var(--heading));">No Fundraisers Found</h3>
                        <p class="mb-0" style="color: hsl(var(--body));">Try adjusting your filters or check back later for new campaigns.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
</div>

<style>
.fundraiser-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 0.75rem 1.5rem hsl(var(--dark)/0.1) !important;
}
.card:hover img {
    transform: scale(1.05);
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.25rem 0.75rem hsl(var(--base)/0.4);
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

@push('styles')
<style>
.pagination .page-link {
    color: hsl(var(--base));
    border: 1px solid hsl(var(--border));
    margin: 0 5px;
    border-radius: 8px !important;
    background-color: hsl(var(--white));
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-700)) 100%);
    border-color: transparent;
    color: hsl(var(--white));
}

.pagination .page-link:hover {
    background: hsl(var(--base)/0.1);
    border-color: hsl(var(--base));
    color: hsl(var(--base));
}

.pagination .page-item.disabled .page-link {
    background-color: hsl(var(--border));
    border-color: hsl(var(--border));
    color: hsl(var(--body));
}
</style>
@endpush
@endsection