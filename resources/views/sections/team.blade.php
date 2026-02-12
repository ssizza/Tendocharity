@php
    $teamSection = @getContent('team.content', null, true)->first();
    
    // Get team members
    $members = \App\Models\Member::with('category')
        ->active()
        ->orderBy('created_at', 'desc')
        ->limit($teamSection->data_values->items_per_page ?? 6)
        ->get();
        
    // Get categories for filtering - check if table exists first
    try {
        $categories = \App\Models\TeamCategory::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    } catch (\Exception $e) {
        $categories = collect([]);
    }
@endphp

@if($teamSection && $members->count() > 0)
<section class="team-section section-full py-80">
    <div class="container">
        {{-- Section Header --}}
        @if(isset($teamSection->data_values->heading))
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-60">
                <span class="badge bg-primary-subtle text-primary px-4 py-2 rounded-pill mb-3">
                    Our Team
                </span>
                
                <h2 class="team-title display-4 fw-bold mb-3">
                    {{ __($teamSection->data_values->heading) }}
                </h2>
                
                @if(isset($teamSection->data_values->subheading))
                <p class="team-subtitle lead text-muted mb-0">
                    {{ __($teamSection->data_values->subheading) }}
                </p>
                @endif
            </div>
        </div>
        @endif

        {{-- Category Filters --}}
        @if(($teamSection->data_values->show_filters ?? false) && $categories->count() > 1)
        <div class="row justify-content-center mb-50">
            <div class="col-lg-10">
                <div class="team-filters d-flex justify-content-center flex-wrap gap-3">
                    <button class="filter-btn btn btn-outline-primary rounded-pill px-4 py-2 active" 
                            data-category="all">
                        All Members
                    </button>
                    
                    @foreach($categories as $category)
                    <button class="filter-btn btn btn-outline-primary rounded-pill px-4 py-2" 
                            data-category="category-{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Team Grid --}}
        <div class="row g-4 team-grid" id="teamContainer">
            @forelse($members as $member)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 team-member" 
                 data-categories="category-{{ $member->category_id }}">
                @include('components.team.member-card', ['member' => $member])
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No team members found</h4>
                </div>
            </div>
            @endforelse
        </div>

        {{-- View All Button --}}
        <div class="row mt-50">
            <div class="col-12 text-center">
                <a href="{{ route('team.index') }}" 
                   class="btn btn-primary btn-lg rounded-pill px-5 py-3">
                    View All Team Members
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.team-section {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 80px 0;
}

.team-title {
    color: #333;
    line-height: 1.2;
}

.team-subtitle {
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

.filter-btn {
    transition: all 0.3s ease;
    border-width: 2px;
    font-weight: 600;
}

.filter-btn.active {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    color: white;
    border-color: transparent;
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
}

.filter-btn:hover:not(.active) {
    border-color: #4facfe;
    color: #4facfe;
    transform: translateY(-2px);
}

.btn-primary {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(79, 172, 254, 0.4);
}

.btn-primary i {
    transition: transform 0.3s ease;
}

.btn-primary:hover i {
    transform: translateX(5px);
}

@media (max-width: 768px) {
    .team-section {
        padding: 60px 0;
    }
    
    .team-title {
        font-size: 2rem;
    }
}
</style>

@if(($teamSection->data_values->show_filters ?? false) && $categories->count() > 1)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const teamMembers = document.querySelectorAll('.team-member');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const category = this.dataset.category;
            
            // Filter team members with animation
            teamMembers.forEach(member => {
                if (category === 'all' || member.dataset.categories === category) {
                    member.style.display = 'block';
                    setTimeout(() => {
                        member.style.opacity = '1';
                        member.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    member.style.opacity = '0';
                    member.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        member.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
});
</script>
@endif
@endif