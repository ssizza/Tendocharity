{{-- /home/rodhni/tendocharity/resources/views/sections/mission.blade.php --}}
@php
    $mission = @getContent('mission.content', null, true)->first();
@endphp

@if($mission)
<section class="mission-section section-full py-5" style="background-color: hsl(var(--light));">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mission-card" 
                     style="background-color: hsl(var(--white)); 
                            border-radius: 15px; 
                            box-shadow: 0 5px 20px hsl(var(--base)/0.05); 
                            padding: 2rem; 
                            position: relative; 
                            overflow: hidden;">
                    
                    {{-- Top decorative gradient bar --}}
                    <div style="position: absolute; 
                                top: 0; 
                                left: 0; 
                                right: 0; 
                                height: 5px; 
                                background: linear-gradient(90deg, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%);">
                    </div>
                    
                    {{-- Icon and Heading --}}
                    <div class="text-center mb-5">
                        <div style="display: inline-flex; 
                                    align-items: center; 
                                    justify-content: center; 
                                    width: 80px; 
                                    height: 80px; 
                                    background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%); 
                                    border-radius: 50%; 
                                    margin-bottom: 1.5rem;">
                            <i class="{{ $mission->data_values->icon ?? 'las la-bullseye' }}" 
                               style="font-size: 2.5rem; color: hsl(var(--white));"></i>
                        </div>
                        
                        <h2 style="color: hsl(var(--heading)); 
                                  font-size: 2rem; 
                                  font-weight: 700; 
                                  margin-bottom: 0;">
                            {{ __($mission->data_values->heading ?? 'Our Mission') }}
                        </h2>
                    </div>
                    
                    {{-- Description --}}
                    <div class="text-center mb-4">
                        <p style="color: hsl(var(--body)); 
                                 line-height: 1.8; 
                                 font-size: 1.1rem; 
                                 margin-bottom: 2rem;">
                            {{ __($mission->data_values->description ?? "At The Hannah Karema Foundation, we are committed to transforming lives by empowering women and girls through education, skill development, and leadership training. Our mission is to dismantle barriers and inspire future generations by addressing critical issues such as teenage pregnancies and early childhood marriages while fostering resilience and self-confidence.") }}
                        </p>
                    </div>
                    
                    {{-- Quote --}}
                    @if(isset($mission->data_values->quote))
                    <div style="background-color: hsl(var(--base)/0.05); 
                                border-left: 4px solid hsl(var(--base)); 
                                border-radius: 8px; 
                                padding: 1.5rem; 
                                margin-top: 2rem;">
                        <p style="color: hsl(var(--body)); 
                                 font-style: italic; 
                                 margin-bottom: 0;">
                            "{{ __($mission->data_values->quote) }}"
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endif