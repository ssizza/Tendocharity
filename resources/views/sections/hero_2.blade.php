@php
    $hero2 = @getContent('hero_2.content', null, true)->first();
@endphp

@if($hero2)
<section class="hero-section hero-style-2 section-full d-flex align-items-center min-vh-80 py-5 py-lg-6" 
         @if(isset($hero2->data_values->background_color)) 
         style="background-color: {{ $hero2->data_values->background_color }};"
         @else
         style="background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%);"
         @endif>
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-content text-white">
                    @if(isset($hero2->data_values->emergency_tag))
                    <div class="emergency-tag mb-4">
                        <span class="badge bg--danger px-4 py-2 fw-normal fs-6 rounded-pill">
                            {{ __($hero2->data_values->emergency_tag) }}
                        </span>
                    </div>
                    @endif
                    
                    <h1 class="display-3 fw-bold mb-3 text-white text-uppercase lh-1">
                        {{ __($hero2->data_values->title ?? 'PawBuddiz Rescue') }}
                    </h1>
                    
                    <h2 class="h2 mb-4 text-white text-uppercase fw-light ls-2">
                        {{ __($hero2->data_values->subtitle ?? 'EMERGENCY CARE FOR STREET DOGS IN CRISIS') }}
                    </h2>
                    
                    <div class="divider mb-4">
                        <hr class="border-white border-2 opacity-100 w-25">
                    </div>
                    
                    <p class="lead mb-5 text-white fs-5 lh-lg" style="max-width: 500px;">
                        {{ __($hero2->data_values->description ?? 'We respond to emergency calls for street dogs in danger, providing immediate medical care and finding safe shelter.') }}
                    </p>
                    
                    @if(isset($hero2->data_values->button_text))
                    <a href="{{ $hero2->data_values->button_link ?? '#' }}" 
                       class="btn btn--light btn--lg fw-bold px-5 py-3">
                        {{ __($hero2->data_values->button_text) }}
                        <i class="las la-arrow-right ms-2"></i>
                    </a>
                    @endif
                </div>
            </div>
            
            @if(isset($hero2->data_values->hero_image))
            <div class="col-lg-6">
                <div class="hero-image-wrapper">
                    <img src="{{ getImage('assets/images/frontend/hero_2/' . $hero2->data_values->hero_image, '800x600') }}" 
                         alt="{{ __($hero2->data_values->title ?? 'Hero Image') }}" 
                         class="img-fluid custom-radius-10 shadow-lg">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

@push('style')
<style>
.min-vh-80 {
    min-height: 80vh;
}
.ls-2 {
    letter-spacing: 2px;
}
@media (max-width: 768px) {
    .min-vh-80 {
        min-height: auto;
    }
    .py-lg-6 {
        padding-top: 60px !important;
        padding-bottom: 60px !important;
    }
}
</style>
@endpush
@endif