@php
    use App\Models\Fundraiser;
    use App\Models\Category;

    $fundraisers = Fundraiser::with(['category', 'service'])
        ->where('status', 'active')
        ->orderBy('priority', 'desc')
        ->orderBy('created_at', 'desc')
        ->paginate(12);

    $categories = Category::where('status', 'active')->get();
@endphp

<section class="fundraisers-grid py-5">
    <div class="container">

        <!-- Header -->
        <div class="row align-items-end mb-5">
            <div class="col-lg-8">
                <h2 class="section-title" style="font-size: 2.4rem; font-weight: 800; color: hsl(var(--heading));">Support Our Causes</h2>
                <p class="section-subtitle" style="color: hsl(var(--body)); font-size: 1.1rem;">
                    Choose a cause close to your heart and help make a real impact.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ url('fundraisers') }}" class="btn" style="background: transparent; border: 2px solid hsl(var(--base)); color: hsl(var(--base)); padding: 8px 20px; border-radius: 3px; font-weight: 500; transition: all 0.3s;">
                    View All <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        @if($fundraisers->count())

        <!-- Grid -->
        <div class="row g-4">
            @foreach($fundraisers as $fundraiser)
            <div class="col-md-6 col-lg-4">

                <div class="fundraiser-card" style="background: hsl(var(--white)); border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px hsl(var(--dark)/0.06); transition: all 0.3s;">
                    <div style="transition: all 0.3s;">
                        <div class="fundraiser-card:hover" style="transform: translateY(-8px); box-shadow: 0 20px 40px hsl(var(--dark)/0.12);"></div>
                    </div>

                    <!-- Image -->
                    <div class="card-image" style="position: relative;">
                        <img src="{{ $fundraiser->featured_image_url ?: asset('assets/images/default.png') }}"
                             alt="{{ $fundraiser->title }}"
                             style="width: 100%; height: 210px; object-fit: cover;">

                        @if($fundraiser->is_featured)
                            <span class="badge" style="position: absolute; top: 12px; left: 12px; padding: 6px 12px; border-radius: 50px; font-size: .7rem; font-weight: 700; background: hsl(var(--warning)); color: hsl(var(--dark));">
                                <i class="fas fa-star me-1"></i> Featured
                            </span>
                        @endif

                        @if($fundraiser->urgency_level)
                            <span class="badge" style="position: absolute; top: 12px; right: 12px; padding: 6px 12px; border-radius: 50px; font-size: .7rem; font-weight: 700; background: {{ $fundraiser->urgency_level === 'urgent' ? 'hsl(var(--warning))' : 'hsl(var(--danger))' }}; color: hsl(var(--white));">
                                {{ strtoupper($fundraiser->urgency_level) }}
                            </span>
                        @endif

                        @if($fundraiser->category)
                            <span class="badge" style="position: absolute; bottom: 12px; left: 12px; padding: 6px 12px; border-radius: 50px; font-size: .7rem; font-weight: 700; background: hsl(var(--white)/0.9); color: hsl(var(--base));">
                                {{ $fundraiser->category->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Body -->
                    <div class="card-body" style="padding: 1.5rem;">

                        <h5 class="title" style="font-weight: 700; margin-bottom: .4rem; color: hsl(var(--heading));">
                            {{ Str::limit($fundraiser->title, 55) }}
                        </h5>

                        <p class="excerpt" style="font-size: .9rem; color: hsl(var(--body)); min-height: 48px;">
                            {{ Str::limit($fundraiser->short_description, 90) }}
                        </p>

                        <!-- Amount -->
                        <div class="amount" style="display: flex; gap: .3rem; align-items: flex-start; margin-bottom: 0.5rem;">
                            <span class="currency" style="font-size: .9rem; margin-top: .35rem; color: hsl(var(--body));">{{ $fundraiser->currency }}</span>
                            <span class="value" style="font-size: 1.8rem; font-weight: 800; color: hsl(var(--base));">{{ $fundraiser->formatted_raised_amount }}</span>
                        </div>
                        <div class="goal" style="font-size: .8rem; color: hsl(var(--body));">
                            of {{ $fundraiser->currency }} {{ $fundraiser->formatted_target_amount }} goal
                        </div>

                        <!-- Progress -->
                        <div class="progress-wrapper mt-3">
                            <div class="progress" style="height: 8px; border-radius: 50px; background: hsl(var(--light-600));">
                                <div class="progress-bar"
                                     style="width: {{ $fundraiser->progress_percentage }}%; background: linear-gradient(90deg, hsl(var(--base)), hsl(var(--base-600))); height: 100%; border-radius: 50px;">
                                </div>
                            </div>
                            <span class="progress-text" style="font-size: .75rem; font-weight: 600; margin-top: 4px; display: block; color: hsl(var(--body));">
                                {{ $fundraiser->progress_percentage }}% funded
                            </span>
                        </div>

                        <!-- Meta -->
                        <div class="meta" style="display: flex; justify-content: space-between; font-size: .8rem; color: hsl(var(--body)); margin-top: .8rem;">
                            <span>
                                <i class="fas fa-map-marker-alt" style="color: hsl(var(--base));"></i>
                                {{ $fundraiser->location ?: 'Global' }}
                            </span>
                            <span>
                                <i class="fas fa-users" style="color: hsl(var(--base));"></i>
                                {{ $fundraiser->beneficiaries_count }}
                            </span>
                        </div>

                        <!-- CTA -->
                        <a href="{{ route('fundraisers.show', $fundraiser->slug) }}"
                           class="btn w-100 mt-3"
                           style="background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base-600))); color: hsl(var(--white)); border-radius: 12px; padding: 12px; font-weight: 700; border: none; transition: all 0.3s; display: inline-block; text-align: center;">
                            <i class="fas fa-heart me-2"></i> Donate Now
                        </a>

                    </div>
                </div>

            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($fundraisers->hasPages())
        <div class="mt-5 d-flex justify-content-center">
            {{ $fundraisers->links() }}
        </div>
        @endif

        @else
        <!-- Empty -->
        <div class="alert text-center py-5" style="background: hsl(var(--info)/0.1); color: hsl(var(--info)); border: 1px solid hsl(var(--info)); border-radius: 5px; padding: 3rem;">
            <i class="fas fa-search fa-3x mb-3" style="color: hsl(var(--info));"></i>
            <h4 style="color: hsl(var(--heading));">No Fundraisers Available</h4>
            <p style="color: hsl(var(--body));">Please check back later.</p>
        </div>
        @endif

    </div>
</section>

<style>
/* Minimal custom styles that can't be done with inline styles */
.fundraiser-card {
    transition: all 0.3s;
}

.fundraiser-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px hsl(var(--dark)/0.12) !important;
}

.btn-outline-primary:hover {
    background: hsl(var(--base)) !important;
    color: hsl(var(--white)) !important;
    border-color: hsl(var(--base)) !important;
}

.btn-donate:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px hsl(var(--base)/0.3) !important;
}

/* Pagination styling to match theme */
.pagination .page-link {
    border-radius: 8px;
    border: none;
    color: hsl(var(--base));
    background: transparent;
    padding: 8px 12px;
    margin: 0 4px;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, hsl(var(--base)), hsl(var(--base-600)));
    color: hsl(var(--white));
}

.pagination .page-item.disabled .page-link {
    color: hsl(var(--body)/0.5);
    pointer-events: none;
}
</style>

<script>
// Add hover effect handling for the donate button
document.addEventListener('DOMContentLoaded', function() {
    const donateBtns = document.querySelectorAll('.btn-donate');
    donateBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 10px 20px hsl(var(--base)/0.3)';
        });
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>