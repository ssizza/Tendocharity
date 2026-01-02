@php
    $aboutUs = @getContent('about_us.content', null, true)->first();
@endphp

@if($aboutUs)
<section class="about-section section-full py-60">
    <div class="container">
        <div class="row align-items-center gy-5">
            @php
                $imageAlignment = $aboutUs->data_values->image_alignment ?? 'left';
                $hasImage = isset($aboutUs->data_values->image) && !empty($aboutUs->data_values->image);
            @endphp
            
            {{-- Image on left, content on right --}}
            @if($imageAlignment == 'left' && $hasImage)
            <div class="col-lg-6">
                <div class="about-image">
                    <img src="{{ getImage('assets/images/frontend/about_us/' . $aboutUs->data_values->image, '800x600') }}" 
                         alt="{{ $aboutUs->data_values->heading ?? 'About Us' }}" 
                         class="w-100 rounded-3 shadow">
                </div>
            </div>
            @endif
            
            <div class="col-lg-6">
                <div class="about-content ps-lg-5">
                    @if(isset($aboutUs->data_values->heading))
                    <h2 class="about-title mb-3">
                        {{ __($aboutUs->data_values->heading) }}
                    </h2>
                    @endif
                    
                    @if(isset($aboutUs->data_values->subheading))
                    <h3 class="about-subtitle mb-4 text-muted">
                        {{ __($aboutUs->data_values->subheading) }}
                    </h3>
                    @endif
                    
                    @if(isset($aboutUs->data_values->description))
                    <div class="about-description mb-5">
                        <p class="lead">{{ __($aboutUs->data_values->description) }}</p>
                    </div>
                    @endif
                    
                    @if(isset($aboutUs->data_values->button_text))
                    <div class="about-actions">
                        <a href="{{ $aboutUs->data_values->button_link ?? '#' }}" 
                           class="btn btn--primary btn-lg px-5 py-3">
                            {{ __($aboutUs->data_values->button_text) }}
                            <i class="las la-arrow-right ms-2"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            {{-- Image on right, content on left --}}
            @if($imageAlignment == 'right' && $hasImage)
            <div class="col-lg-6 order-lg-2">
                <div class="about-image">
                    <img src="{{ getImage('assets/images/frontend/about_us/' . $aboutUs->data_values->image, '800x600') }}" 
                         alt="{{ $aboutUs->data_values->heading ?? 'About Us' }}" 
                         class="w-100 rounded-3 shadow">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
.about-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 230px 0;
}

.about-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    line-height: 1.2;
}

.about-subtitle {
    font-size: 1.5rem;
    font-weight: 500;
    color: #6c757d;
}

.about-description p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #555;
}

.about-image {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
}

.about-image img {
    transition: transform 0.5s ease;
}

.about-image:hover img {
    transform: scale(1.05);
}

.btn--primary {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    border: none;
    color: white;
    padding: 12px 35px;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.btn--primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(79, 172, 254, 0.3);
    color: white;
}

@media (max-width: 992px) {
    .about-content {
        padding-left: 0 !important;
        padding-top: 30px;
    }
    
    .about-title {
        font-size: 2rem;
    }
    
    .about-subtitle {
        font-size: 1.25rem;
    }
}
</style>
@endif