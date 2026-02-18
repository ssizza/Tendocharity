@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <!-- Cancel Icon -->
                    <div class="mb-4">
                        <div class="cancel-icon mx-auto">
                            <i class="fas fa-times-circle"></i>
                        </div>
                    </div>
                    
                    <!-- Cancel Message -->
                    <h1 class="fw-bold text-danger mb-3">Donation Cancelled</h1>
                    <p class="lead mb-4">Your donation process has been cancelled.</p>
                    
                    @if($donation)
                    <div class="alert alert-warning">
                        <p class="mb-0">Reference: <strong>{{ $donation->payment_reference }}</strong></p>
                    </div>
                    @endif
                    
                    <!-- Reason (if any) -->
                    <div class="alert alert-info mb-5">
                        <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Why was my donation cancelled?</h6>
                        <ul class="mb-0 ps-3">
                            <li>You cancelled the payment process</li>
                            <li>Payment gateway timeout</li>
                            <li>Payment authorization failed</li>
                            <li>Technical issues during processing</li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                        @if($donation && $donation->fundraiser)
                        <a href="{{ route('donation.initiate', $donation->fundraiser->slug) }}" class="btn btn-primary">
                            <i class="fas fa-redo me-2"></i> Try Again
                        </a>
                        @endif
                        <a href="{{ route('fundraisers.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-search me-2"></i> Browse Other Campaigns
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-success">
                            <i class="fas fa-home me-2"></i> Return Home
                        </a>
                    </div>
                    
                    <!-- Contact Support -->
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="fw-bold mb-3">Need Help?</h6>
                        <p class="text-muted mb-3">If you believe this was an error or need assistance, please contact our support team.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="mailto:support@tendocharity.com" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-envelope me-1"></i> Email Support
                            </a>
                            <a href="tel:+1234567890" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-phone me-1"></i> Call Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Frequently Asked Questions -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">Frequently Asked Questions</h5>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Will I be charged for a cancelled donation?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    No, if the donation was cancelled before completion, no charges will be applied to your account. 
                                    If you see a pending charge, it should disappear within 3-5 business days depending on your bank.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Can I donate using a different payment method?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, you can try again with a different payment method. We accept credit cards, PayPal, 
                                    bank transfers, and other secure payment options.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Is my payment information secure?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolutely! We use industry-standard SSL encryption and partner with trusted payment 
                                    processors. We never store your full payment details on our servers.
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
.cancel-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #dc3545, #fd7e14);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.cancel-icon i {
    font-size: 50px;
    color: white;
}
.accordion-button:not(.collapsed) {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    font-weight: 600;
}
</style>
@endpush