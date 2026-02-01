@php
    $mission = @getContent('mission.content', null, true)->first();
@endphp

@if($mission)
<section class="mission-section section-full py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <div class="mission-statement">
                    <h2 class="display-4 fw-bold mb-4 text-primary">
                        {{ __($mission->data_values->heading ?? 'Mission') }}
                    </h2>
                    
                    <div class="mission-highlight bg-primary text-white p-4 rounded-3 mb-4">
                        <p class="mb-0 fst-italic">
                            "Transforming lives through empowerment and opportunity"
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="mission-content bg-white rounded-4 shadow-lg p-4 p-lg-5">
                    <div class="mission-description mb-4">
                        <p class="fs-5 lh-lg">
                            {{ __($mission->data_values->description ?? "At The Hannah Karema Foundation, we are committed to transforming lives by empowering women and girls through education, skill development, and leadership training. Our mission is to dismantle barriers and inspire future generations by addressing critical issues such as teenage pregnancies and early childhood marriages while fostering resilience and self-confidence. Through strategic partnerships, community engagement, and impactful initiatives, we strive to create a world where every woman can thrive, lead with confidence, and realize her fullest potential.") }}
                        </p>
                    </div>
                    
                    <div class="mission-objectives">
                        <h5 class="fw-bold mb-3">Our Core Objectives:</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="las la-check-circle text-success me-2"></i>
                                        <span>Education & Skill Development</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="las la-check-circle text-success me-2"></i>
                                        <span>Leadership Training</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="las la-check-circle text-success me-2"></i>
                                        <span>Addressing Social Issues</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <i class="las la-check-circle text-success me-2"></i>
                                        <span>Building Resilience</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="las la-check-circle text-success me-2"></i>
                                        <span>Strategic Partnerships</span>
                                    </li>
                                    <li class="mb-2">
                                        <i class="las la-check-circle text-success me-2"></i>
                                        <span>Community Engagement</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mission-action mt-4 pt-3 border-top">
                        <a href="#register" class="btn btn-primary px-4 py-2 me-3">
                            <i class="las la-user-plus me-2"></i>Join Our Mission
                        </a>
                        <a href="#donate" class="btn btn-outline-primary px-4 py-2">
                            <i class="las la-hand-holding-heart me-2"></i>Support Our Work
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.mission-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
    overflow: hidden;
}

.mission-section:before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 50%;
    transform: translate(150px, -150px);
}

.mission-section:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 50%;
    transform: translate(-100px, 100px);
}

.mission-statement {
    position: relative;
    z-index: 1;
}

.mission-highlight {
    position: relative;
    border-left: 5px solid #ffc107;
}

.mission-content {
    position: relative;
    z-index: 1;
    border-top: 5px solid #667eea;
}

.mission-objectives ul li {
    padding: 8px 0;
    border-bottom: 1px dashed #dee2e6;
}

.mission-objectives ul li:last-child {
    border-bottom: none;
}

.mission-action .btn {
    transition: all 0.3s ease;
}

.mission-action .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
@endif