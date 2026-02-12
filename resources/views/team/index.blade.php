@extends('layouts.frontend')

@section('content')
{{-- Breadcrumb --}}
@include('partials.breadcrumb', [
    'pageTitle' => 'Our Team',
    'breadcrumb' => @getContent('team_breadcrumb.content', null, true)->first() ?? @getContent('breadcrumb.content', null, true)->first()
])

<div class="team-page py-60">
    <div class="container">
        @php
            try {
                $categories = \App\Models\TeamCategory::active()
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
            } catch (\Exception $e) {
                $categories = collect([]);
            }
                
            $members = \App\Models\Member::with('category')
                ->active()
                ->orderBy('created_at', 'desc')
                ->get();
        @endphp

        @if($categories->count() > 1)
        <div class="team-filters mb-40">
            <div class="d-flex justify-content-center flex-wrap gap-3">
                @foreach($categories as $category)
                <button class="team-filter-btn btn btn-outline-primary rounded-pill px-4 py-2 
                            {{ $loop->first ? 'active' : '' }}" 
                        data-category="{{ $category->id }}">
                    {{ $category->name }}
                </button>
                @endforeach
            </div>
        </div>
        @endif

        <div class="team-content">
            @if($categories->count() > 0)
                @foreach($categories as $category)
                <div class="team-category-section {{ !$loop->first ? 'd-none' : '' }}" 
                     id="category-{{ $category->id }}">
                    <div class="row g-4">
                        @php
                            $categoryMembers = $members->where('category_id', $category->id);
                        @endphp
                        
                        @forelse($categoryMembers as $member)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            @include('team.member-card', ['member' => $member])
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center py-5">
                                <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                <h4>No team members found</h4>
                                <p class="mb-0 text-muted">We're currently updating our team information for this category.</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
                @endforeach
            @else
                <div class="row g-4">
                    @forelse($members as $member)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        @include('team.member-card', ['member' => $member])
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                            <h4>No team members found</h4>
                            <p class="mb-0 text-muted">We're currently updating our team information.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.team-page {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    min-height: 60vh;
    padding: 60px 0;
}

.team-filter-btn {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6;
    font-weight: 600;
}

.team-filter-btn.active {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
}

.team-filter-btn:hover:not(.active) {
    border-color: #4facfe;
    color: #4facfe;
}

.team-category-section {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

@media (max-width: 768px) {
    .team-page {
        padding: 40px 0;
    }
    
    .team-filter-btn {
        padding: 8px 20px !important;
        font-size: 14px;
    }
}
</style>

@if(isset($categories) && $categories->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.team-filter-btn');
    const categorySections = document.querySelectorAll('.team-category-section');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get target category
            const categoryId = this.dataset.category;
            
            // Hide all sections with animation
            categorySections.forEach(section => {
                section.style.opacity = '0';
                setTimeout(() => {
                    section.classList.add('d-none');
                }, 300);
            });
            
            // Show target section with animation
            setTimeout(() => {
                const targetSection = document.getElementById('category-' + categoryId);
                targetSection.classList.remove('d-none');
                setTimeout(() => {
                    targetSection.style.opacity = '1';
                }, 50);
            }, 300);
        });
    });
});
</script>
@endif
@endsection