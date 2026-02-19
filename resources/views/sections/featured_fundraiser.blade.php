@php
    use App\Models\Fundraiser;

    $featuredFundraiser = Fundraiser::with(['category', 'service'])
        ->where('status', 'active')
        ->where('is_featured', 1)
        ->orderBy('priority', 'desc')
        ->orderBy('created_at', 'desc')
        ->first();
@endphp

@if($featuredFundraiser)
<section class="featured-fundraiser section-full py-5">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- LEFT CONTENT -->
            <div class="col-lg-7">

                <!-- Urgency -->
                @if(in_array($featuredFundraiser->urgency_level, ['critical','urgent']))
                    <div class="mb-3">
                        <span class="badge badge--lg 
                            {{ $featuredFundraiser->urgency_level === 'critical' ? 'badge--danger' : 'badge--warning' }}">
                            @if($featuredFundraiser->urgency_level === 'critical')
                                <i class="fas fa-exclamation-triangle me-1"></i> Critical Need
                            @else
                                <i class="fas fa-clock me-1"></i> Urgent
                            @endif
                        </span>
                    </div>
                @endif

                <!-- Category -->
                @if($featuredFundraiser->category)
                    <div class="mb-3">
                        <span class="badge badge--base badge--lg" 
                              style="background-color: hsl(var(--base)/0.1) !important; color: hsl(var(--base)) !important; border: none;">
                            {{ $featuredFundraiser->category->name }}
                        </span>
                    </div>
                @endif

                <!-- Title -->
                <h1 class="fundraiser-title mb-3 text--heading">
                    {{ $featuredFundraiser->title }}
                </h1>

                <!-- Tagline -->
                @if($featuredFundraiser->tagline)
                    <p class="fundraiser-tagline mb-4 text--body">
                        {{ $featuredFundraiser->tagline }}
                    </p>
                @endif

                <!-- AMOUNT -->
                <div class="amount-block mb-4">
                    <div class="amount d-flex align-items-baseline gap-2">
                        <span class="currency text--body fs--16px">{{ $featuredFundraiser->currency }}</span>
                        <span class="value text--base fs--48px fw-bold">{{ $featuredFundraiser->formatted_raised_amount }}</span>
                    </div>
                    <div class="goal text--body">
                        raised of {{ $featuredFundraiser->currency }} {{ $featuredFundraiser->formatted_target_amount }}
                    </div>
                </div>

                <!-- PROGRESS -->
                <div class="progress-wrapper mb-4">
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text--body">Progress</span>
                        <strong class="text--base">{{ $featuredFundraiser->progress_percentage }}%</strong>
                    </div>
                    <div class="progress" style="height: 10px; background-color: hsl(var(--light-600)); border-radius: 50px;">
                        <div class="progress-bar" 
                             style="width: {{ $featuredFundraiser->progress_percentage }}%; 
                                    background: linear-gradient(90deg, hsl(var(--base)), hsl(var(--base-600))); 
                                    border-radius: 50px;">
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="cta-buttons d-flex flex-wrap gap-3">
                    <a href="{{ route('fundraisers.show', $featuredFundraiser->slug) }}"
                       class="btn cmn--btn btn--lg d-flex align-items-center">
                        <i class="fas fa-heart me-2"></i> Donate Now
                    </a>

                    <a href="{{ route('fundraisers.show', $featuredFundraiser->slug) }}"
                       class="btn cmn--btn btn--outline btn--lg">
                        Learn More
                    </a>
                </div>

            </div>

            <!-- RIGHT IMAGE -->
            <div class="col-lg-5">
                <div class="image-card position-relative" 
                     style="border-radius: 20px; overflow: hidden; 
                            box-shadow: 0 20px 40px hsl(var(--dark)/0.1);">
                    <img src="{{ $featuredFundraiser->featured_image_url ?: asset('assets/images/default.png') }}"
                         alt="{{ $featuredFundraiser->title }}"
                         style="width: 100%; height: 420px; object-fit: cover;">

                    @if($featuredFundraiser->gallery_images)
                        @php
                            $gallery = is_array($featuredFundraiser->gallery_images)
                                ? $featuredFundraiser->gallery_images
                                : json_decode($featuredFundraiser->gallery_images, true);
                        @endphp

                        @if(is_array($gallery) && count($gallery))
                            <div class="gallery-preview position-absolute" 
                                 style="bottom: 15px; left: 15px; display: flex; gap: 8px;">
                                @foreach(array_slice($gallery, 0, 3) as $img)
                                    <img src="{{ $featuredFundraiser->getGalleryImageUrl($img) }}"
                                         style="width: 60px; height: 60px; border-radius: 10px; 
                                                border: 2px solid hsl(var(--white)); object-fit: cover;">
                                @endforeach

                                @if(count($gallery) > 3)
                                    <div class="more d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px; border-radius: 10px; 
                                                border: 2px solid hsl(var(--white)); 
                                                background-color: hsl(var(--dark)/0.7); 
                                                color: hsl(var(--white)); font-weight: 700;">
                                        +{{ count($gallery) - 3 }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

<style>
.featured-fundraiser {
    background: linear-gradient(135deg, hsl(var(--light)), hsl(var(--base)/0.05));
    border-radius: 24px;
}

/* Custom amount block styling */
.amount-block .fs--48px {
    font-size: 3rem;
    line-height: 1.2;
}

@media (max-width: 768px) {
    .amount-block .fs--48px {
        font-size: 2.5rem;
    }
}

@media (max-width: 576px) {
    .amount-block .fs--48px {
        font-size: 2rem;
    }
}

/* Ensure progress bar works with theme */
.progress {
    height: 10px;
    background-color: hsl(var(--light-600));
    border-radius: 50px;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(90deg, hsl(var(--base)), hsl(var(--base-600)));
    border-radius: 50px;
}

/* Override button outline to match theme better */
.btn--outline {
    border: 2px solid hsl(var(--base)) !important;
    color: hsl(var(--base)) !important;
    background: transparent !important;
}

.btn--outline:hover {
    background: hsl(var(--base)) !important;
    color: hsl(var(--white)) !important;
}
</style>

@push('css')
<style>
/* Additional theme-specific adjustments */
.featured-fundraiser .badge--base {
    background-color: hsl(var(--base)/0.1) !important;
    color: hsl(var(--base)) !important;
    border: none !important;
    padding: 6px 14px !important;
    font-size: 0.85rem !important;
}

.featured-fundraiser .badge--danger {
    background-color: hsl(var(--danger)) !important;
    color: hsl(var(--white)) !important;
    border: none !important;
    padding: 8px 14px !important;
    font-size: 0.8rem !important;
}

.featured-fundraiser .badge--warning {
    background-color: hsl(var(--warning)) !important;
    color: hsl(var(--dark)) !important;
    border: none !important;
    padding: 8px 14px !important;
    font-size: 0.8rem !important;
}

/* Fundraiser title color */
.fundraiser-title {
    color: hsl(var(--heading));
}

/* CTA buttons spacing */
.cta-buttons .btn {
    min-width: 160px;
    justify-content: center;
}

@media (max-width: 576px) {
    .cta-buttons .btn {
        width: 100%;
    }
}
</style>
@endpush
@endif