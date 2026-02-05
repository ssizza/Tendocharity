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
<section class="featured-fundraiser py-5">
    <div class="container">
        <div class="row align-items-center g-5">

            <!-- LEFT CONTENT -->
            <div class="col-lg-7">

                <!-- Urgency -->
                @if(in_array($featuredFundraiser->urgency_level, ['critical','urgent']))
                    <div class="mb-3">
                        <span class="badge urgency {{ $featuredFundraiser->urgency_level }}">
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
                        <span class="category-pill">
                            {{ $featuredFundraiser->category->name }}
                        </span>
                    </div>
                @endif

                <!-- Title -->
                <h1 class="fundraiser-title mb-3">
                    {{ $featuredFundraiser->title }}
                </h1>

                <!-- Tagline -->
                @if($featuredFundraiser->tagline)
                    <p class="fundraiser-tagline mb-4">
                        {{ $featuredFundraiser->tagline }}
                    </p>
                @endif

                <!-- AMOUNT -->
                <div class="amount-block mb-4">
                    <div class="amount">
                        <span class="currency">{{ $featuredFundraiser->currency }}</span>
                        <span class="value">{{ $featuredFundraiser->formatted_raised_amount }}</span>
                    </div>
                    <div class="goal">
                        raised of {{ $featuredFundraiser->currency }} {{ $featuredFundraiser->formatted_target_amount }}
                    </div>
                </div>

                <!-- PROGRESS -->
                <div class="progress-wrapper mb-4">
                    <div class="d-flex justify-content-between small mb-1">
                        <span>Progress</span>
                        <strong>{{ $featuredFundraiser->progress_percentage }}%</strong>
                    </div>
                    <div class="progress">
                        <div class="progress-bar"
                             style="width: {{ $featuredFundraiser->progress_percentage }}%">
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="cta-buttons d-flex flex-wrap gap-3">
                    <a href="{{ route('fundraisers.show', $featuredFundraiser->slug) }}"
                       class="btn btn-donate">
                        <i class="fas fa-heart me-2"></i> Donate Now
                    </a>

                    <a href="{{ route('fundraisers.show', $featuredFundraiser->slug) }}"
                       class="btn btn-outline">
                        Learn More
                    </a>
                </div>

            </div>

            <!-- RIGHT IMAGE -->
            <div class="col-lg-5">
                <div class="image-card">
                    <img src="{{ $featuredFundraiser->featured_image_url ?: asset('assets/images/default.png') }}"
                         alt="{{ $featuredFundraiser->title }}">

                    @if($featuredFundraiser->gallery_images)
                        @php
                            $gallery = is_array($featuredFundraiser->gallery_images)
                                ? $featuredFundraiser->gallery_images
                                : json_decode($featuredFundraiser->gallery_images, true);
                        @endphp

                        @if(is_array($gallery) && count($gallery))
                            <div class="gallery-preview">
                                @foreach(array_slice($gallery, 0, 3) as $img)
                                    <img src="{{ $featuredFundraiser->getGalleryImageUrl($img) }}">
                                @endforeach

                                @if(count($gallery) > 3)
                                    <div class="more">+{{ count($gallery) - 3 }}</div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endif


<style>
.featured-fundraiser {
    background: linear-gradient(135deg, #f8f9ff, #eef1ff);
    border-radius: 24px;
}

/* Urgency */
.urgency {
    padding: 8px 14px;
    border-radius: 50px;
    font-weight: 700;
    font-size: .8rem;
}
.urgency.critical {
    background: #dc3545;
    color: #fff;
}
.urgency.urgent {
    background: #ffc107;
    color: #000;
}

/* Category */
.category-pill {
    background: rgba(102,126,234,.1);
    color: #667eea;
    padding: 6px 14px;
    border-radius: 50px;
    font-weight: 600;
    font-size: .85rem;
}

/* Title & text */
.fundraiser-title {
    font-size: clamp(2rem, 4vw, 2.8rem);
    font-weight: 800;
    line-height: 1.2;
}
.fundraiser-tagline {
    color: #6c757d;
    font-size: 1.1rem;
}

/* Amount */
.amount-block .amount {
    display: flex;
    align-items: flex-start;
    gap: .3rem;
}
.amount-block .currency {
    font-size: 1rem;
    margin-top: .4rem;
    color: #6c757d;
}
.amount-block .value {
    font-size: 3rem;
    font-weight: 800;
    color: #667eea;
}
.amount-block .goal {
    font-size: .9rem;
    color: #6c757d;
}

/* Progress */
.progress {
    height: 10px;
    background: #e9ecef;
    border-radius: 50px;
}
.progress-bar {
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 50px;
}

/* Buttons */
.btn-donate {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    padding: 14px 32px;
    font-weight: 700;
    border-radius: 12px;
    border: none;
    transition: .3s;
}
.btn-donate:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(102,126,234,.3);
}

.btn-outline {
    border: 2px solid #667eea;
    color: #667eea;
    padding: 14px 28px;
    font-weight: 700;
    border-radius: 12px;
}
.btn-outline:hover {
    background: #667eea;
    color: #fff;
}

/* Image */
.image-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,.1);
}
.image-card img {
    width: 100%;
    height: 420px;
    object-fit: cover;
}

/* Gallery */
.gallery-preview {
    position: absolute;
    bottom: 15px;
    left: 15px;
    display: flex;
    gap: 8px;
}
.gallery-preview img,
.gallery-preview .more {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    border: 2px solid #fff;
    object-fit: cover;
}
.gallery-preview .more {
    background: rgba(0,0,0,.7);
    color: #fff;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

</style>