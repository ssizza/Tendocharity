@php
    $hero2 = @getContent('hero_2.content', null, true)->first();
@endphp

@if($hero2)
<section class="hero-section hero-style-2 section-full d-flex align-items-center" 
         @if(isset($hero2->data_values->background_color)) 
         style="background-color: {{ $hero2->data_values->background_color }}; min-height: 80vh; padding: 100px 0;"
         @else
         style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 80vh; padding: 100px 0;"
         @endif>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content text-white">
                    @if(isset($hero2->data_values->emergency_tag))
                    <div class="emergency-tag mb-4">
                        <span class="badge bg-danger px-4 py-2 fs-6 fw-normal">
                            {{ __($hero2->data_values->emergency_tag) }}
                        </span>
                    </div>
                    @endif
                    
                    <h1 class="hero-title display-3 fw-bold mb-3">
                        {{ __($hero2->data_values->title ?? 'PawBuddiz Rescue') }}
                    </h1>
                    
                    <h2 class="hero-subtitle h2 mb-4">
                        {{ __($hero2->data_values->subtitle ?? 'EMERGENCY CARE FOR STREET DOGS IN CRISIS') }}
                    </h2>
                    
                    <div class="divider mb-4">
                        <hr class="border-white border-2 opacity-100" style="width: 100px;">
                    </div>
                    
                    <p class="hero-description lead mb-5">
                        {{ __($hero2->data_values->description ?? 'We respond to emergency calls for street dogs in danger, providing immediate medical care and finding safe shelter.') }}
                    </p>
                    
                    @if(isset($hero2->data_values->button_text))
                    <a href="{{ $hero2->data_values->button_link ?? '#' }}" 
                       class="btn btn-lg btn-light text-dark px-5 py-3 fw-bold">
                        {{ __($hero2->data_values->button_text) }}
                        <i class="las la-arrow-right ms-2"></i>
                    </a>
                    @endif
                </div>
            </div>
            
            @if(isset($hero2->data_values->has_hero_image) && $hero2->data_values->has_hero_image == 1 && isset($hero2->data_values->hero_image))
            <div class="col-lg-6 mt-5 mt-lg-0">
                <div class="hero-image-wrapper">
                    <img src="{{ getImage('assets/images/frontend/hero/' . $hero2->data_values->hero_image, '800x600') }}" 
                         alt="{{ __($hero2->data_values->title ?? 'Hero Image') }}" 
                         class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
.hero-style-2 .hero-title {
    font-size: 3.5rem;
    line-height: 1.1;
    text-transform: uppercase;
}

.hero-style-2 .hero-subtitle {
    font-weight: 300;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.hero-style-2 .emergency-tag .badge {
    font-size: 1rem;
    padding: 8px 20px;
    border-radius: 50px;
}

.hero-style-2 .hero-description {
    font-size: 1.25rem;
    line-height: 1.8;
    max-width: 500px;
}

@media (max-width: 768px) {
    .hero-style-2 .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-style-2 .hero-subtitle {
        font-size: 1.5rem;
    }
}
</style>
@endif