@php
    $strategicPlan = @getContent('strategic_plan.content', null, true)->first();
@endphp

@if($strategicPlan)
<section class="strategic-plan-section section-full py-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <span class="badge bg-warning text-dark fs-6 px-4 py-2 mb-3">{{ __($strategicPlan->data_values->heading ?? '2025 – 2030 Strategic Plan') }}</span>
                    <h2 class="text-white display-5 fw-bold mb-4">
                        {{ __($strategicPlan->data_values->title ?? 'Advancing Education and Community Empowerment') }}
                    </h2>
                </div>
                
                <div class="row align-items-center">
                    @if(isset($strategicPlan->data_values->has_image) && $strategicPlan->data_values->has_image == '1' && isset($strategicPlan->data_values->image))
                    <div class="col-lg-6 mb-5 mb-lg-0">
                        <div class="plan-image-wrapper">
                            <img src="{{ getImage('assets/images/frontend/strategic_plan/' . $strategicPlan->data_values->image, '800x600') }}" 
                                 alt="{{ __($strategicPlan->data_values->title ?? 'Strategic Plan') }}" 
                                 class="img-fluid rounded shadow-lg">
                        </div>
                    </div>
                    @endif
                    
                    <div class="@if(isset($strategicPlan->data_values->has_image) && $strategicPlan->data_values->has_image == '1') col-lg-6 @else col-lg-12 @endif">
                        <div class="plan-description bg-white rounded p-4 p-lg-5 shadow">
                            <p class="fs-5 mb-0">
                                {{ __($strategicPlan->data_values->description ?? "The Hannah Karema Foundation's 2025–2030 Strategic Plan focuses on expanding educational programs, leadership training, and health infrastructure while fostering international partnerships to empower communities and secure a brighter future for all.") }}
                            </p>
                        </div>
                        
                        <div class="mt-4 text-white">
                            <h4 class="mb-3">Key Focus Areas:</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="las la-check-circle me-2 text-success"></i> Educational Programs</li>
                                        <li class="mb-2"><i class="las la-check-circle me-2 text-success"></i> Leadership Training</li>
                                        <li class="mb-2"><i class="las la-check-circle me-2 text-success"></i> Health Infrastructure</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="list-unstyled">
                                        <li class="mb-2"><i class="las la-check-circle me-2 text-success"></i> International Partnerships</li>
                                        <li class="mb-2"><i class="las la-check-circle me-2 text-success"></i> Community Empowerment</li>
                                        <li class="mb-2"><i class="las la-check-circle me-2 text-success"></i> Sustainable Development</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.strategic-plan-section {
    position: relative;
    overflow: hidden;
}

.strategic-plan-section:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}

.strategic-plan-section > .container {
    position: relative;
    z-index: 1;
}

.plan-description {
    position: relative;
    border-left: 4px solid #667eea;
}

.plan-image-wrapper img {
    transition: transform 0.3s ease;
    border: 3px solid rgba(255,255,255,0.2);
}

.plan-image-wrapper img:hover {
    transform: scale(1.02);
}
</style>
@endif