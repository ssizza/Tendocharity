@php
    $founder = @getContent('founder.content', null, true)->first();
@endphp

@if($founder)
<section class="founder-section section-full py-5" style="background-color: hsl(var(--light));">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 @if(isset($founder->data_values->image_alignment) && $founder->data_values->image_alignment == 'right') order-lg-2 @endif">
                <div class="founder-content">
                    <h2 class="section-heading fw-bold mb-4 pb-3 position-relative" 
                        style="color: hsl(var(--heading)); font-size: 2.5rem;">
                        {{ __($founder->data_values->heading ?? 'Founder') }}
                        <span class="position-absolute start-0 bottom-0" 
                              style="width: 80px; height: 4px; background: linear-gradient(90deg, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%);"></span>
                    </h2>
                    
                    <div class="founder-description">
                        <p class="lead mb-4" style="font-size: 1.1rem; line-height: 1.8; color: hsl(var(--body));">
                            {{ __($founder->data_values->description ?? "Hannah Karema, once a beauty queen, has used her platform to advocate for women's empowerment. Her path from a modest village in Nakaseke to Miss Uganda, and now as a prominent philanthropist, illustrates her dedication to her mission.") }}
                        </p>
                    </div>
                </div>
            </div>
            
            @if(isset($founder->data_values->has_image) && $founder->data_values->has_image == '1' && isset($founder->data_values->image))
            <div class="col-lg-6 @if(isset($founder->data_values->image_alignment) && $founder->data_values->image_alignment == 'right') order-lg-1 @endif">
                <div class="founder-image-wrapper text-center">
                    <img src="{{ getImage('assets/images/frontend/founder/' . $founder->data_values->image, '600x600') }}" 
                         alt="{{ __($founder->data_values->heading ?? 'Founder') }}" 
                         class="img-fluid rounded-circle shadow-lg" 
                         style="max-width: 400px; border: 5px solid hsl(var(--white)); box-shadow: 0 10px 30px hsl(var(--dark)/0.1);">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
@media (max-width: 768px) {
    .section-heading {
        font-size: 2rem !important;
    }
}
</style>
@endif