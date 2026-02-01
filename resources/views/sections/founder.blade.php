@php
    $founder = @getContent('founder.content', null, true)->first();
@endphp

@if($founder)
<section class="founder-section section-full py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 @if(isset($founder->data_values->image_alignment) && $founder->data_values->image_alignment == 'right') order-lg-2 @endif">
                <div class="founder-content">
                    <h2 class="section-heading mb-4">
                        {{ __($founder->data_values->heading ?? 'Founder') }}
                    </h2>
                    
                    <div class="founder-description">
                        <p class="lead mb-4">
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
                         class="img-fluid rounded-circle shadow-lg" style="max-width: 400px;">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<style>
.founder-section {
    background-color: #f8f9fa;
}

.section-heading {
    color: #2c3e50;
    font-size: 2.5rem;
    font-weight: 700;
    position: relative;
    padding-bottom: 15px;
}

.section-heading:after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.founder-description {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #5a6c7d;
}

@media (max-width: 768px) {
    .section-heading {
        font-size: 2rem;
    }
}
</style>
@endif