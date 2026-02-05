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
                <h2 class="section-title">Support Our Causes</h2>
                <p class="section-subtitle">
                    Choose a cause close to your heart and help make a real impact.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ url('fundraisers') }}" class="btn btn-outline-primary">
                    View All <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        @if($fundraisers->count())

        <!-- Grid -->
        <div class="row g-4">
            @foreach($fundraisers as $fundraiser)
            <div class="col-md-6 col-lg-4">

                <div class="fundraiser-card">

                    <!-- Image -->
                    <div class="card-image">
                        <img src="{{ $fundraiser->featured_image_url ?: asset('assets/images/default.png') }}"
                             alt="{{ $fundraiser->title }}">

                        @if($fundraiser->is_featured)
                            <span class="badge badge-featured">
                                <i class="fas fa-star me-1"></i> Featured
                            </span>
                        @endif

                        @if($fundraiser->urgency_level)
                            <span class="badge badge-urgency {{ $fundraiser->urgency_level }}">
                                {{ strtoupper($fundraiser->urgency_level) }}
                            </span>
                        @endif

                        @if($fundraiser->category)
                            <span class="badge badge-category">
                                {{ $fundraiser->category->name }}
                            </span>
                        @endif
                    </div>

                    <!-- Body -->
                    <div class="card-body">

                        <h5 class="title">
                            {{ Str::limit($fundraiser->title, 55) }}
                        </h5>

                        <p class="excerpt">
                            {{ Str::limit($fundraiser->short_description, 90) }}
                        </p>

                        <!-- Amount -->
                        <div class="amount mb-2">
                            <span class="currency">{{ $fundraiser->currency }}</span>
                            <span class="value">{{ $fundraiser->formatted_raised_amount }}</span>
                        </div>
                        <div class="goal">
                            of {{ $fundraiser->currency }} {{ $fundraiser->formatted_target_amount }} goal
                        </div>

                        <!-- Progress -->
                        <div class="progress-wrapper mt-3">
                            <div class="progress">
                                <div class="progress-bar"
                                     style="width: {{ $fundraiser->progress_percentage }}%">
                                </div>
                            </div>
                            <span class="progress-text">
                                {{ $fundraiser->progress_percentage }}% funded
                            </span>
                        </div>

                        <!-- Meta -->
                        <div class="meta">
                            <span>
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $fundraiser->location ?: 'Global' }}
                            </span>
                            <span>
                                <i class="fas fa-users"></i>
                                {{ $fundraiser->beneficiaries_count }}
                            </span>
                        </div>

                        <!-- CTA -->
                        <a href="{{ route('fundraisers.show', $fundraiser->slug) }}"
                           class="btn btn-donate w-100 mt-3">
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
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-search fa-3x mb-3"></i>
            <h4>No Fundraisers Available</h4>
            <p>Please check back later.</p>
        </div>
        @endif

    </div>
</section>


<style>
.fundraisers-grid {
    background: #f8f9ff;
}

/* Header */
.section-title {
    font-size: 2.4rem;
    font-weight: 800;
}
.section-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
}

/* Card */
.fundraiser-card {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,.06);
    transition: .3s;
}
.fundraiser-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0,0,0,.12);
}

/* Image */
.card-image {
    position: relative;
}
.card-image img {
    width: 100%;
    height: 210px;
    object-fit: cover;
}

/* Badges */
.badge {
    position: absolute;
    padding: 6px 12px;
    border-radius: 50px;
    font-size: .7rem;
    font-weight: 700;
}
.badge-featured {
    top: 12px;
    left: 12px;
    background: #ffc107;
    color: #000;
}
.badge-urgency {
    top: 12px;
    right: 12px;
    background: #dc3545;
    color: #fff;
}
.badge-urgency.urgent {
    background: #fd7e14;
}
.badge-category {
    bottom: 12px;
    left: 12px;
    background: rgba(255,255,255,.9);
    color: #667eea;
}

/* Body */
.card-body {
    padding: 1.5rem;
}
.title {
    font-weight: 700;
    margin-bottom: .4rem;
}
.excerpt {
    font-size: .9rem;
    color: #6c757d;
    min-height: 48px;
}

/* Amount */
.amount {
    display: flex;
    gap: .3rem;
    align-items: flex-start;
}
.amount .currency {
    font-size: .9rem;
    margin-top: .35rem;
    color: #6c757d;
}
.amount .value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #667eea;
}
.goal {
    font-size: .8rem;
    color: #6c757d;
}

/* Progress */
.progress {
    height: 8px;
    border-radius: 50px;
    background: #e9ecef;
}
.progress-bar {
    background: linear-gradient(90deg, #667eea, #764ba2);
}
.progress-text {
    font-size: .75rem;
    font-weight: 600;
    margin-top: 4px;
    display: block;
}

/* Meta */
.meta {
    display: flex;
    justify-content: space-between;
    font-size: .8rem;
    color: #6c757d;
    margin-top: .8rem;
}

/* Buttons */
.btn-donate {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    border-radius: 12px;
    padding: 12px;
    font-weight: 700;
    border: none;
    transition: .3s;
}
.btn-donate:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102,126,234,.3);
}

/* Pagination */
.pagination .page-link {
    border-radius: 8px;
    border: none;
    color: #667eea;
}
.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
}

</style>