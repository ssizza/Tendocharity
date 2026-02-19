@extends('layouts.frontend')

@section('content')

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
                <button class="team-filter-btn btn btn--outline-base rounded-pill px-4 py-2 
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
                            <div class="alert alert-info text-center py-5" style="background: hsl(var(--info)/0.1); border-color: hsl(var(--info)); color: hsl(var(--heading));">
                                <i class="fas fa-users fa-3x mb-3" style="color: hsl(var(--info));"></i>
                                <h4 style="color: hsl(var(--heading));">No team members found</h4>
                                <p class="mb-0" style="color: hsl(var(--body));">We're currently updating our team information for this category.</p>
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
                        <div class="alert alert-info text-center py-5" style="background: hsl(var(--info)/0.1); border-color: hsl(var(--info)); color: hsl(var(--heading));">
                            <i class="fas fa-users fa-3x mb-3" style="color: hsl(var(--info));"></i>
                            <h4 style="color: hsl(var(--heading));">No team members found</h4>
                            <p class="mb-0" style="color: hsl(var(--body));">We're currently updating our team information.</p>
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
    background: linear-gradient(135deg, hsl(var(--white)) 0%, hsl(var(--light)) 100%);
    min-height: 60vh;
    padding: 60px 0;
}

.team-filter-btn {
    transition: all 0.3s ease;
    border: 2px solid hsl(var(--border)) !important;
    font-weight: 600;
    background: transparent !important;
    color: hsl(var(--body)) !important;
}

.team-filter-btn.active {
    background: linear-gradient(to right, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%) !important;
    color: hsl(var(--white)) !important;
    border-color: transparent !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px hsl(var(--base)/0.3);
}

.team-filter-btn:hover:not(.active) {
    border-color: hsl(var(--base)) !important;
    color: hsl(var(--base)) !important;
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
                section.style.transition = 'opacity 0.3s ease';
                setTimeout(() => {
                    section.classList.add('d-none');
                }, 300);
            });
            
            // Show target section with animation
            setTimeout(() => {
                const targetSection = document.getElementById('category-' + categoryId);
                targetSection.classList.remove('d-none');
                targetSection.style.opacity = '1';
                targetSection.style.transition = 'opacity 0.3s ease';
            }, 300);
        });
    });
});
</script>
@endif
@endsection