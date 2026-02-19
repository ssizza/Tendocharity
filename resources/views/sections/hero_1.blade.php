@php
    $hero1 = @getContent('hero_1.content', null, true)->first();
@endphp

@if($hero1)
<section class="hero-section hero-style-1 section-full d-flex align-items-center position-relative overflow-hidden" 
         @if(isset($hero1->data_values->background_image))
         style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ getImage('assets/images/frontend/hero_1/' . $hero1->data_values->background_image, '1920x800') }}') no-repeat center center/cover;"
         @else
         style="background: linear-gradient(135deg, hsl(var(--base-700)) 0%, hsl(var(--accent)) 100%);"
         @endif>
    <div class="container position-relative z-index-1">
        <div class="row align-items-center g-4 @if(isset($hero1->data_values->image_position) && $hero1->data_values->image_position == 'right') flex-row-reverse @endif">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="hero-organization-name display-4 fw-bold mb-3 text-white">
                        {{ __($hero1->data_values->organization_name ?? 'TransNoah Tech Nexus Center') }}
                    </h1>
                    
                    <h2 class="hero-tagline h3 fst-italic mb-4 text--base">
                        {{ __($hero1->data_values->tagline ?? '(TNTCEO)') }}
                    </h2>
                    
                    <p class="hero-description lead mb-5 text-white opacity-75">
                        {{ __($hero1->data_values->description ?? 'Creating modern digital tools for advocacy and offering technology support to human rights defenders in East and the Horn of Africa.') }}
                    </p>
                    
                    <div class="hero-buttons d-flex flex-wrap gap-3">
                        @if(isset($hero1->data_values->button1_text))
                        <a href="{{ $hero1->data_values->button1_link ?? '#' }}" 
                           class="btn cmn--btn btn--lg">
                            {{ __($hero1->data_values->button1_text) }}
                        </a>
                        @endif
                        
                        @if(isset($hero1->data_values->button2_text))
                        <a href="{{ $hero1->data_values->button2_link ?? '#' }}" 
                           class="btn btn--outline-light btn--lg">
                            {{ __($hero1->data_values->button2_text) }}
                            <i class="las la-arrow-right ms-2"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            @if(isset($hero1->data_values->foreground_image))
            <div class="col-lg-6">
                <div class="hero-image-wrapper text-center text-lg-end">
                    <img src="{{ getImage('assets/images/frontend/hero_1/' . $hero1->data_values->foreground_image, '600x400') }}" 
                         alt="{{ __($hero1->data_values->organization_name ?? 'Organization') }}" 
                         class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@push('style')
<style>
.hero-section {
    min-height: 80vh;
    padding: 100px 0;
    position: relative;
}

.hero-section::before {
    position: absolute;
    content: '';
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, hsl(var(--dark)/0.9) 0%, hsl(var(--dark)/0.4) 100%);
    z-index: 0;
}

.z-index-1 {
    z-index: 1;
}

.hero-organization-name {
    font-size: clamp(2rem, 5vw, 3.5rem);
    line-height: 1.2;
    letter-spacing: -0.02em;
}

.hero-tagline {
    font-weight: 400;
    position: relative;
    display: inline-block;
}

.hero-tagline::after {
    position: absolute;
    content: '';
    bottom: -10px;
    left: 0;
    width: 80px;
    height: 3px;
    background: hsl(var(--base));
}

.hero-description {
    font-size: clamp(1rem, 2vw, 1.25rem);
    line-height: 1.8;
    max-width: 600px;
}

.hero-image-wrapper img {
    animation: float 6s ease-in-out infinite;
    max-height: 500px;
    width: auto;
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
    100% {
        transform: translateY(0px);
    }
}

@media (max-width: 991px) {
    .hero-section {
        padding: 80px 0;
    }
    
    .hero-tagline::after {
        width: 60px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        min-height: auto;
        padding: 60px 0;
    }
    
    .hero-organization-name {
        font-size: 2rem;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .hero-image-wrapper {
        margin-top: 40px;
    }
    
    .hero-image-wrapper img {
        max-height: 400px;
    }
}

.btn--outline-light {
    border: 2px solid hsl(var(--white));
    background: transparent;
    color: hsl(var(--white));
}

.btn--outline-light:hover {
    background: hsl(var(--base));
    border-color: hsl(var(--base));
    color: hsl(var(--white));
}
</style>
@endpush
@endif