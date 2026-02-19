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
<section class="team-section section-full pt-100 pb-100">
    <div class="container">
        {{-- Section Header --}}
        @if(isset($teamSection->data_values->heading))
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="badge bg--base-400 text--white px-4 py-2 rounded-pill mb-3">
                    Our Team
                </span>
                
                <h2 class="section-title mb-3">
                    {{ __($teamSection->data_values->heading) }}
                </h2>
                
                @if(isset($teamSection->data_values->subheading))
                <p class="section-subtitle lead text--body mx-auto" style="max-width: 600px;">
                    {{ __($teamSection->data_values->subheading) }}
                </p>
                @endif
            </div>
        </div>
        @endif

        {{-- Category Filters --}}
        @if(($teamSection->data_values->show_filters ?? false) && $categories->count() > 1)
        <div class="row justify-content-center mt-5">
            <div class="col-lg-10">
                <div class="team-filters d-flex justify-content-center flex-wrap gap-3">
                    <button class="filter-btn cmn--btn btn--outline-base rounded-pill px-4 py-2 active" 
                            data-category="all">
                        All Members
                    </button>
                    
                    @foreach($categories as $category)
                    <button class="filter-btn cmn--btn btn--outline-base rounded-pill px-4 py-2" 
                            data-category="category-{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Team Grid --}}
        <div class="row g-4 team-grid mt-4" id="teamContainer">
            @forelse($members as $member)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 team-member" 
                 data-categories="category-{{ $member->category_id }}">
                @include('components.team.member-card', ['member' => $member])
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="las la-users las-3x text--body mb-3"></i>
                    <h4 class="text--body">No team members found</h4>
                </div>
            </div>
            @endforelse
        </div>

        {{-- View All Button --}}
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="{{ route('team.index') }}" 
                   class="cmn--btn btn--base rounded-pill px-5 py-3">
                    View All Team Members
                    <i class="las la-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
.team-section {
    background: linear-gradient(135deg, hsl(var(--white)) 0%, hsl(var(--light)) 100%);
}

.section-title {
    color: hsl(var(--heading));
    font-size: clamp(1.875rem, 5vw, 3.25rem);
    font-weight: 700;
    line-height: 1.2;
}

.section-subtitle {
    font-size: 1.1rem;
}

.team-filters {
    margin-bottom: 30px;
}

.filter-btn {
    transition: all 0.3s ease;
    border-width: 2px;
    font-weight: 600;
}

.filter-btn.active {
    background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%);
    color: hsl(var(--white));
    border-color: transparent;
    box-shadow: 0 5px 15px hsl(var(--base)/0.3);
}

.filter-btn:hover:not(.active) {
    border-color: hsl(var(--base));
    color: hsl(var(--base));
    transform: translateY(-2px);
}

.btn--base {
    background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn--base:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px hsl(var(--base)/0.4);
}

.btn--base i {
    transition: transform 0.3s ease;
}

.btn--base:hover i {
    transform: translateX(5px);
}

.team-member {
    transition: all 0.3s ease;
}

.team-member[style*="display: none"] {
    display: none !important;
}

@media (max-width: 768px) {
    .team-section {
        padding-top: 60px;
        padding-bottom: 60px;
    }
}
</style>

@if(($teamSection->data_values->show_filters ?? false) && $categories->count() > 1)
<script>
(function($) {
    "use strict";
    
    $(document).ready(function() {
        $('.filter-btn').on('click', function() {
            // Remove active class from all buttons
            $('.filter-btn').removeClass('active');
            
            // Add active class to clicked button
            $(this).addClass('active');
            
            var category = $(this).data('category');
            
            // Filter team members with animation
            $('.team-member').each(function() {
                var member = $(this);
                if (category === 'all' || member.data('categories') === category) {
                    member.fadeIn(300).css({
                        'opacity': '1',
                        'transform': 'scale(1)'
                    });
                } else {
                    member.css({
                        'opacity': '0',
                        'transform': 'scale(0.8)'
                    }).fadeOut(300);
                }
            });
        });
    });
})(jQuery);
</script>
@endif
@endif