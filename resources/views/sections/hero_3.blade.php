@php
    $hero3 = @getContent('hero_3.content', null, true)->first();
@endphp

@if($hero3)
<section class="hero-section hero-style-3 section-full d-flex align-items-center" 
         @if(isset($hero3->data_values->has_background_image) && $hero3->data_values->has_background_image == 1 && isset($hero3->data_values->background_image))
         style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ getImage('assets/images/frontend/hero/' . $hero3->data_values->background_image, '1920x800') }}') no-repeat center center/cover; min-height: 90vh; padding: 120px 0;"
         @else
         style="background: linear-gradient(135deg, #1a365d 0%, #2d3748 100%); min-height: 90vh; padding: 120px 0;"
         @endif>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="hero-content text-white">
                    <h1 class="hero-foundation-name display-2 fw-bold mb-4">
                        {{ __($hero3->data_values->foundation_name ?? 'THE HANNAH KAREMA FOUNDATION') }}
                    </h1>
                    
                    <h2 class="hero-tagline h1 mb-4 text-uppercase" style="letter-spacing: 3px;">
                        {{ __($hero3->data_values->tagline ?? 'EMPOWERING COMMUNITIES THROUGH ACTION') }}
                    </h2>
                    
                    <p class="hero-description lead mb-5 mx-auto" style="max-width: 700px;">
                        {{ __($hero3->data_values->description ?? 'Empowering women and girls through education, skill development, and leadership training.') }}
                    </p>
                    
                    <div class="hero-buttons d-flex flex-wrap justify-content-center gap-3 mb-5">
                        @if(isset($hero3->data_values->button1_text))
                        <a href="{{ $hero3->data_values->button1_link ?? '#' }}" 
                           class="btn btn-lg btn-light text-dark px-5 py-3 fw-bold">
                            {{ __($hero3->data_values->button1_text) }}
                        </a>
                        @endif
                        
                        @if(isset($hero3->data_values->button2_text))
                        <a href="{{ $hero3->data_values->button2_link ?? '#' }}" 
                           class="btn btn-lg btn-outline-light px-5 py-3">
                            {{ __($hero3->data_values->button2_text) }}
                        </a>
                        @endif
                    </div>
                    
                    @if(isset($hero3->data_values->highlight_text))
                    <div class="hero-highlight mt-5 pt-5 border-top border-white border-opacity-25">
                        <div class="row align-items-center">
                            <div class="col-lg-8 text-lg-start">
                                <h4 class="h3 mb-0">{{ __($hero3->data_values->highlight_text) }}</h4>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <div class="highlight-date h5 mb-0">
                                    {{ __($hero3->data_values->highlight_date ?? '22 April, 2024') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.hero-style-3 .hero-foundation-name {
    font-size: 4rem;
    line-height: 1.1;
    letter-spacing: -1px;
}

.hero-style-3 .hero-tagline {
    font-weight: 300;
    color: #ffd700 !important;
}

.hero-style-3 .hero-description {
    font-size: 1.3rem;
    line-height: 1.8;
}

.hero-style-3 .hero-highlight {
    padding-top: 40px;
    margin-top: 40px;
}

.hero-style-3 .highlight-date {
    color: #ffd700;
    font-weight: 600;
}

@media (max-width: 768px) {
    .hero-style-3 .hero-foundation-name {
        font-size: 2.8rem;
    }
    
    .hero-style-3 .hero-tagline {
        font-size: 1.8rem;
        letter-spacing: 1px;
    }
    
    .hero-style-3 .hero-description {
        font-size: 1.1rem;
    }
}
</style>
@endif