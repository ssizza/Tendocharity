@php
    $vision = @getContent('vision.content', null, true)->first();
@endphp

@if($vision)
<section class="vision-section section-full py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="vision-card bg-white rounded-4 shadow-lg p-4 p-lg-5">
                    <div class="text-center mb-5">
                        @if(isset($vision->data_values->icon) && $vision->data_values->icon)
                        <div class="vision-icon mb-4">
                            <i class="{{ $vision->data_values->icon }} fs-1 text-primary"></i>
                        </div>
                        @else
                        <div class="vision-icon mb-4">
                            <i class="las la-eye fs-1 text-primary"></i>
                        </div>
                        @endif
                        
                        <h2 class="section-heading mb-4 text-center">
                            {{ __($vision->data_values->heading ?? 'Vision') }}
                        </h2>
                    </div>
                    
                    <div class="vision-content text-center">
                        <div class="fs-5 lh-lg text-muted">
                            {{ __($vision->data_values->description ?? "To create a transformative and lasting legacy of empowered women who break barriers, overcome challenges, and lead with confidence. By raising awareness about critical issues like teenage pregnancies and early childhood marriages, promoting education, and equipping women with essential skills and leadership qualities. We aim to inspire future generations to pursue their dreams with unwavering determination and build a world where every woman can thrive and succeed.") }}
                        </div>
                    </div>
                    
                    <div class="row mt-5 pt-4 border-top">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="vision-point text-center p-3 h-100">
                                <i class="las la-bullhorn fs-2 text-primary mb-3"></i>
                                <h5 class="fw-bold">Awareness</h5>
                                <p class="mb-0 small">Raising awareness about critical social issues</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4 mb-md-0">
                            <div class="vision-point text-center p-3 h-100">
                                <i class="las la-graduation-cap fs-2 text-primary mb-3"></i>
                                <h5 class="fw-bold">Education</h5>
                                <p class="mb-0 small">Promoting education and skill development</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="vision-point text-center p-3 h-100">
                                <i class="las la-hands-helping fs-2 text-primary mb-3"></i>
                                <h5 class="fw-bold">Empowerment</h5>
                                <p class="mb-0 small">Empowering women with leadership qualities</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.vision-section {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.vision-card {
    position: relative;
    overflow: hidden;
}

.vision-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.vision-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white !important;
    border-radius: 50%;
    margin: 0 auto;
}

.vision-icon i {
    font-size: 2.5rem !important;
}

.vision-point {
    transition: all 0.3s ease;
    border-radius: 10px;
}

.vision-point:hover {
    background-color: #f8f9fa;
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
@endif