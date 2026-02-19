@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container pt-120 pb-120">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="custom--card border-0 text-center">
                <div class="card-body p-5">
                    <!-- Cancel Icon -->
                    <div class="mb-4">
                        <div class="cancel-icon mx-auto bg--danger rounded--circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: linear-gradient(135deg, hsl(var(--danger)), hsl(var(--warning)));">
                            <i class="fas fa-times-circle text--white" style="font-size: 50px;"></i>
                        </div>
                    </div>
                    
                    <!-- Cancel Message -->
                    <h1 class="fw-bold text--danger mb-3">Donation Cancelled</h1>
                    <p class="lead mb-4 text--body">Your donation process has been cancelled.</p>
                    
                    @if($donation)
                    <div class="alert alert--warning d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <p class="mb-0">Reference: <strong>{{ $donation->payment_reference }}</strong></p>
                    </div>
                    @endif
                    
                    <!-- Reason (if any) -->
                    <div class="alert alert--info mb-5">
                        <h6 class="fw-bold text--info mb-3"><i class="fas fa-info-circle me-2"></i>Why was my donation cancelled?</h6>
                        <ul class="mb-0 ps-3 text--body">
                            <li>You cancelled the payment process</li>
                            <li>Payment gateway timeout</li>
                            <li>Payment authorization failed</li>
                            <li>Technical issues during processing</li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                        @if($donation && $donation->fundraiser)
                        <a href="{{ route('donation.initiate', $donation->fundraiser->slug) }}" class="btn cmn--btn">
                            <i class="fas fa-redo me-2"></i> Try Again
                        </a>
                        @endif
                        <a href="{{ route('fundraisers.index') }}" class="btn btn--outline-base">
                            <i class="fas fa-search me-2"></i> Browse Other Campaigns
                        </a>
                        <a href="{{ route('home') }}" class="btn btn--success">
                            <i class="fas fa-home me-2"></i> Return Home
                        </a>
                    </div>
                    
                    <!-- Contact Support -->
                    <div class="mt-5 pt-4 border-top border--primary">
                        <h6 class="fw-bold mb-3 text--heading">Need Help?</h6>
                        <p class="text--body mb-3">If you believe this was an error or need assistance, please contact our support team.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="mailto:support@tendocharity.com" class="btn btn--sm btn--outline-base">
                                <i class="fas fa-envelope me-1"></i> Email Support
                            </a>
                            <a href="tel:+1234567890" class="btn btn--sm btn--outline-base">
                                <i class="fas fa-phone me-1"></i> Call Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Frequently Asked Questions -->
            <div class="custom--card mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4 text--heading">Frequently Asked Questions</h5>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 mb-3">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button bg--light text--base fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" style="background-color: hsl(var(--light));">
                                    Will I be charged for a cancelled donation?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body bg--white p-3">
                                    <p class="text--body mb-0">No, if the donation was cancelled before completion, no charges will be applied to your account. If you see a pending charge, it should disappear within 3-5 business days depending on your bank.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0 mb-3">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed bg--light text--base fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" style="background-color: hsl(var(--light));">
                                    Can I donate using a different payment method?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body bg--white p-3">
                                    <p class="text--body mb-0">Yes, you can try again with a different payment method. We accept credit cards, PayPal, bank transfers, and other secure payment options.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed bg--light text--base fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" style="background-color: hsl(var(--light));">
                                    Is my payment information secure?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body bg--white p-3">
                                    <p class="text--body mb-0">Absolutely! We use industry-standard SSL encryption and partner with trusted payment processors. We never store your full payment details on our servers.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
/* Minimal inline styles that can't be achieved with classes */
.accordion-button:not(.collapsed) {
    background-color: hsl(var(--danger)/0.1) !important;
    color: hsl(var(--danger)) !important;
}

.accordion-button:focus {
    box-shadow: none !important;
    border-color: transparent !important;
}

.accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23dc3545'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
}

.cancel-icon {
    background: linear-gradient(135deg, hsl(var(--danger)), hsl(var(--warning)));
}
</style>
@endpush