{{-- /home/rodhni/tendocharity/resources/views/sections/strategic_plan.blade.php --}}
@php
    $strategicPlan = @getContent('strategic_plan.content', null, true)->first();
@endphp

@if($strategicPlan)
<section class="strategic-plan-section section-full py-5" style="background-color: hsl(var(--base)/0.03); position: relative; overflow: hidden;">
    {{-- Subtle background pattern --}}
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.02; background-image: url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23000000' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- Header --}}
                <div class="text-center mb-5">
                    <span class="badge px-4 py-2 mb-3 fs-6" style="background-color: hsl(var(--base)/0.1); color: hsl(var(--base)); border: 1px solid hsl(var(--base)/0.2);">
                        {{ __($strategicPlan->data_values->heading ?? '2025 – 2030 Strategic Plan') }}
                    </span>
                    <h2 class="display-6 fw-bold mb-4" style="color: hsl(var(--heading));">
                        {{ __($strategicPlan->data_values->title ?? 'Advancing Education and Community Empowerment') }}
                    </h2>
                </div>
                
                {{-- Two Column Layout --}}
                <div class="row g-4">
                    {{-- Column 1: Title and Image --}}
                    <div class="col-lg-6">
                        <div class="h-100 d-flex flex-column">
                            @if(isset($strategicPlan->data_values->has_image) && $strategicPlan->data_values->has_image == '1' && isset($strategicPlan->data_values->image))
                            <div class="strategic-image-wrapper mb-4">
                                <img src="{{ getImage('assets/images/frontend/strategic_plan/' . $strategicPlan->data_values->image, '800x600') }}" 
                                     alt="{{ __($strategicPlan->data_values->title ?? 'Strategic Plan') }}" 
                                     class="img-fluid rounded-4 w-100" 
                                     style="box-shadow: 0 10px 30px hsl(var(--base)/0.1); border: 1px solid hsl(var(--border)); transition: transform 0.3s ease;">
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Column 2: Description and Key Focus Areas --}}
                    <div class="col-lg-6">
                        <div class="h-100 d-flex flex-column">
                            {{-- Row 1: Description --}}
                            <div class="strategic-description mb-4 p-4 rounded-4" style="background-color: hsl(var(--white)); border: 1px solid hsl(var(--border));">
                                <p class="fs-5 mb-0 lh-lg" style="color: hsl(var(--body));">
                                    {{ __($strategicPlan->data_values->description ?? "The Hannah Karema Foundation's 2025–2030 Strategic Plan focuses on expanding educational programs, leadership training, and health infrastructure while fostering international partnerships to empower communities and secure a brighter future for all.") }}
                                </p>
                            </div>
                            
                            {{-- Row 2: Key Focus Areas (Commented out for now, can be enabled when needed) --}}
                            {{--
                            <div class="strategic-focus p-4 rounded-4" style="background-color: hsl(var(--white)); border: 1px solid hsl(var(--border));">
                                <h4 class="fw-bold mb-4" style="color: hsl(var(--heading));">
                                    <i class="las la-bullseye me-2" style="color: hsl(var(--base));"></i>
                                    Key Focus Areas
                                </h4>
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="las la-check-circle fs-5 me-2" style="color: hsl(var(--success));"></i>
                                                <span style="color: hsl(var(--body));">Educational Programs</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="las la-check-circle fs-5 me-2" style="color: hsl(var(--success));"></i>
                                                <span style="color: hsl(var(--body));">Leadership Training</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="las la-check-circle fs-5 me-2" style="color: hsl(var(--success));"></i>
                                                <span style="color: hsl(var(--body));">Health Infrastructure</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="list-unstyled">
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="las la-check-circle fs-5 me-2" style="color: hsl(var(--success));"></i>
                                                <span style="color: hsl(var(--body));">International Partnerships</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="las la-check-circle fs-5 me-2" style="color: hsl(var(--success));"></i>
                                                <span style="color: hsl(var(--body));">Community Empowerment</span>
                                            </li>
                                            <li class="mb-3 d-flex align-items-center">
                                                <i class="las la-check-circle fs-5 me-2" style="color: hsl(var(--success));"></i>
                                                <span style="color: hsl(var(--body));">Sustainable Development</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif