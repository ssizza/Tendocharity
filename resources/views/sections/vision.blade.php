{{-- /home/rodhni/tendocharity/resources/views/sections/vision.blade.php --}}
@php
    $vision = @getContent('vision.content', null, true)->first();
@endphp

@if($vision)
<section class="vision-section section-full py-5" style="background-color: hsl(var(--light));">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="vision-card" 
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
                                background: linear-gradient(90deg, hsl(var(--base-600)) 0%, hsl(var(--base)) 100%);">
                    </div>
                    
                    {{-- Icon and Heading --}}
                    <div class="text-center mb-5">
                        <div style="display: inline-flex; 
                                    align-items: center; 
                                    justify-content: center; 
                                    width: 80px; 
                                    height: 80px; 
                                    background: linear-gradient(135deg, hsl(var(--base-600)) 0%, hsl(var(--base)) 100%); 
                                    border-radius: 50%; 
                                    margin-bottom: 1.5rem;">
                            <i class="{{ $vision->data_values->icon ?? 'las la-eye' }}" 
                               style="font-size: 2.5rem; color: hsl(var(--white));"></i>
                        </div>
                        
                        <h2 style="color: hsl(var(--heading)); 
                                  font-size: 2rem; 
                                  font-weight: 700; 
                                  margin-bottom: 0;">
                            {{ __($vision->data_values->heading ?? 'Our Vision') }}
                        </h2>
                    </div>
                    
                    {{-- Description --}}
                    <div class="text-center mb-5">
                        <p style="color: hsl(var(--body)); 
                                 line-height: 1.8; 
                                 font-size: 1.1rem;">
                            {{ __($vision->data_values->description ?? "To create a transformative and lasting legacy of empowered women who break barriers, overcome challenges, and lead with confidence. By raising awareness about critical issues like teenage pregnancies and early childhood marriages, promoting education, and equipping women with essential skills and leadership qualities. We aim to inspire future generations to pursue their dreams with unwavering determination and build a world where every woman can thrive and succeed.") }}
                        </p>
                    </div>
                    
                    {{-- Vision Points --}}
                    <div class="row g-4 mt-4" style="border-top: 1px solid hsl(var(--border)); padding-top: 2rem;">
                        <div class="col-md-4">
                            <div style="text-align: center; 
                                       padding: 1.5rem; 
                                       height: 100%; 
                                       transition: all 0.3s ease; 
                                       border-radius: 10px;"
                                 onmouseover="this.style.backgroundColor='hsl(var(--base)/0.05)'; this.style.transform='translateY(-5px)'"
                                 onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                                <i class="las la-bullhorn" style="font-size: 2rem; color: hsl(var(--base)); margin-bottom: 1rem; display: block;"></i>
                                <h5 style="color: hsl(var(--heading)); font-weight: 700; margin-bottom: 0.5rem;">Awareness</h5>
                                <p style="color: hsl(var(--body)); font-size: 0.9rem; margin-bottom: 0;">Raising awareness about critical social issues</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div style="text-align: center; 
                                       padding: 1.5rem; 
                                       height: 100%; 
                                       transition: all 0.3s ease; 
                                       border-radius: 10px;"
                                 onmouseover="this.style.backgroundColor='hsl(var(--base)/0.05)'; this.style.transform='translateY(-5px)'"
                                 onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                                <i class="las la-graduation-cap" style="font-size: 2rem; color: hsl(var(--base)); margin-bottom: 1rem; display: block;"></i>
                                <h5 style="color: hsl(var(--heading)); font-weight: 700; margin-bottom: 0.5rem;">Education</h5>
                                <p style="color: hsl(var(--body)); font-size: 0.9rem; margin-bottom: 0;">Promoting education and skill development</p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div style="text-align: center; 
                                       padding: 1.5rem; 
                                       height: 100%; 
                                       transition: all 0.3s ease; 
                                       border-radius: 10px;"
                                 onmouseover="this.style.backgroundColor='hsl(var(--base)/0.05)'; this.style.transform='translateY(-5px)'"
                                 onmouseout="this.style.backgroundColor='transparent'; this.style.transform='translateY(0)'">
                                <i class="las la-hands-helping" style="font-size: 2rem; color: hsl(var(--base)); margin-bottom: 1rem; display: block;"></i>
                                <h5 style="color: hsl(var(--heading)); font-weight: 700; margin-bottom: 0.5rem;">Empowerment</h5>
                                <p style="color: hsl(var(--body)); font-size: 0.9rem; margin-bottom: 0;">Empowering women with leadership qualities</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif