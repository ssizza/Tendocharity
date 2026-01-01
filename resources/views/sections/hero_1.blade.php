@php
    $hero1 = @getContent('hero_1.content', null, true)->first();
@endphp

@if($hero1)
<section class="hero-section hero-style-1 section-full d-flex align-items-center" 
         @if(isset($hero1->data_values->has_background_image) && $hero1->data_values->has_background_image == 1 && isset($hero1->data_values->background_image))
         style="background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ getImage('assets/images/frontend/hero_1/' . $hero1->data_values->background_image, '1920x800') }}') no-repeat center center/cover; min-height: 80vh; padding: 100px 0;"
         @else
         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 80vh; padding: 100px 0;"
         @endif>
    <div class="container">
        <div class="row align-items-center @if(isset($hero1->data_values->image_position) && $hero1->data_values->image_position == 'right') flex-row-reverse @endif">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="hero-content text-white">
                    <h1 class="hero-organization-name display-4 fw-bold mb-3">
                        {{ __($hero1->data_values->organization_name ?? 'TransNoah Tech Nexus Center') }}
                    </h1>
                    
                    <h2 class="hero-tagline h3 text-primary mb-4">
                        {{ __($hero1->data_values->tagline ?? '(TNTCEO)') }}
                    </h2>
                    
                    <p class="hero-description lead mb-5">
                        {{ __($hero1->data_values->description ?? 'Creating modern digital tools for advocacy and offering technology support to human rights defenders in East and the Horn of Africa.') }}
                    </p>
                    
                    <div class="hero-buttons d-flex flex-wrap gap-3">
                        @if(isset($hero1->data_values->button1_text))
                        <a href="{{ $hero1->data_values->button1_link ?? '#' }}" 
                           class="btn btn-lg btn-primary px-5 py-3">
                            {{ __($hero1->data_values->button1_text) }}
                        </a>
                        @endif
                        
                        @if(isset($hero1->data_values->button2_text))
                        <a href="{{ $hero1->data_values->button2_link ?? '#' }}" 
                           class="btn btn-lg btn-outline-light px-5 py-3">
                            {{ __($hero1->data_values->button2_text) }}
                            <i class="las la-arrow-right ms-2"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            @if(isset($hero1->data_values->has_foreground_image) && $hero1->data_values->has_foreground_image == 1 && isset($hero1->data_values->foreground_image))
            <div class="col-lg-6">
                <div class="hero-image-wrapper">
                    <img src="{{ getImage('assets/images/frontend/hero_1/' . $hero1->data_values->foreground_image, '600x400') }}" 
                         alt="{{ __($hero1->data_values->organization_name ?? 'Organization') }}" 
                         class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
.hero-style-1 .hero-organization-name {
    font-size: 3.5rem;
    line-height: 1.1;
    letter-spacing: -0.5px;
}

.hero-style-1 .hero-tagline {
    color: #4facfe !important;
    font-weight: 300;
    font-style: italic;
}

.hero-style-1 .hero-description {
    font-size: 1.25rem;
    line-height: 1.8;
    max-width: 600px;
}

@media (max-width: 768px) {
    .hero-style-1 .hero-organization-name {
        font-size: 2.5rem;
    }
    
    .hero-style-1 .hero-description {
        font-size: 1.1rem;
    }
}
</style>
@endif