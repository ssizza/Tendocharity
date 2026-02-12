@extends('layouts.frontend')

@section('content')
{{-- Breadcrumb --}}
@include('partials.breadcrumb', [
    'pageTitle' => $category->name . ' Team',
    'breadcrumb' => @getContent('team_category_breadcrumb.content', null, true)->first() ?? @getContent('breadcrumb.content', null, true)->first()
])

<div class="team-category-page py-60">
    <div class="container">
        {{-- Category Header --}}
        <div class="category-header text-center mb-50">
            <span class="badge bg-primary-subtle text-primary px-4 py-2 rounded-pill mb-3">
                {{ $category->name }}
            </span>
            <h1 class="display-4 fw-bold mb-3">{{ $category->name }}</h1>
            @if($category->description)
            <p class="lead text-muted mx-auto" style="max-width: 700px;">
                {{ $category->description }}
            </p>
            @endif
        </div>
        
        {{-- Category Navigation --}}
        @if($categories->count() > 1)
        <div class="category-nav mb-40">
            <div class="d-flex justify-content-center flex-wrap gap-3">
                @foreach($categories as $cat)
                <a href="{{ route('team.category', $cat->slug) }}" 
                   class="category-nav-link btn {{ $cat->id == $category->id ? 'btn-primary' : 'btn-outline-primary' }} rounded-pill px-4 py-2">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif
        
        {{-- Team Members Grid --}}
        @if($members->count() > 0)
        <div class="row g-4">
            @foreach($members as $member)
            <div class="col-xl-3 col-lg-4 col-md-6">
                @include('components.team.member-card', ['member' => $member])
            </div>
            @endforeach
        </div>
        @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-users fa-3x mb-3 text-muted"></i>
            <h4>No team members found</h4>
            <p class="mb-0 text-muted">We're currently updating our team information for this category.</p>
        </div>
        @endif
    </div>
</div>

<style>
.team-category-page {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.category-header .display-4 {
    color: #333;
    font-weight: 700;
}

.category-nav-link {
    transition: all 0.3s ease;
    font-weight: 500;
}

.category-nav-link.btn-primary {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    border-color: transparent;
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
}

.category-nav-link.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
}

.category-nav-link.btn-outline-primary:hover {
    background: #4facfe;
    color: white;
    border-color: #4facfe;
}

@media (max-width: 768px) {
    .team-category-page {
        padding: 40px 0;
    }
    
    .category-header .display-4 {
        font-size: 2rem;
    }
}
</style>
@endsection