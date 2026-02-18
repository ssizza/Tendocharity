@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <!-- Pending Icon -->
                    <div class="mb-4">
                        <div class="pending-icon mx-auto">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    
                    <!-- Pending Message -->
                    <h1 class="fw-bold text-warning mb-3">Pending Verification</h1>
                    <p class="lead mb-4">Your payment details have been submitted successfully.</p>
                    
                    <!-- Donation Details -->
                    <div class="card border-0 bg-light mb-5">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4">Submission Details</h5>
                            <div class="row text-start">
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted small">Reference Number</div>
                                    <div class="fw-bold">{{ $donation->payment_reference }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted small">Date Submitted</div>
                                    <div class="fw-bold">{{ $donation->created_at->format('F d, Y h:i A') }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted small">Amount</div>
                                    <div class="fw-bold">{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted small">Status</div>
                                    <div class="fw-bold text-warning">Pending Verification</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- What Happens Next -->
                    <div class="alert alert-info mb-5">
                        <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>What happens next?</h6>
                        <ul class="mb-0 text-start">
                            <li>Our team will verify your payment within 1-3 business days</li>
                            <li>You'll receive an email confirmation once verified</li>
                            <li>The campaign page will update with your donation</li>
                            <li>A tax receipt will be emailed if applicable</li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                        <a href="{{ route('fundraisers.show', $donation->fundraiser->slug) }}" class="btn btn-outline-primary">
                            <i class="fas fa-heart me-2"></i> Back to Campaign
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-success">
                            <i class="fas fa-home me-2"></i> Return Home
                        </a>
                    </div>
                    
                    <!-- Contact Support -->
                    <div class="mt-5 pt-4 border-top">
                        <h6 class="fw-bold mb-3">Need Help?</h6>
                        <p class="text-muted mb-3">If you have any questions about your donation, please contact our support team.</p>
                        <a href="mailto:support@tendocharity.com" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-envelope me-1"></i> support@tendocharity.com
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.pending-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #f39c12, #f1c40f);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.pending-icon i {
    font-size: 50px;
    color: white;
}
</style>
@endpush