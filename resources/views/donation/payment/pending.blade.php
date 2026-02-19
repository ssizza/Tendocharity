@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="custom--card border-0 text-center">
                <div class="card-body p-5">
                    <!-- Pending Icon -->
                    <div class="mb-4">
                        <div class="pending-icon mx-auto bg--warning">
                            <i class="fas fa-clock text--white"></i>
                        </div>
                    </div>
                    
                    <!-- Pending Message -->
                    <h1 class="fw-bold text--warning mb-3">Pending Verification</h1>
                    <p class="lead mb-4 text--body">Your payment details have been submitted successfully.</p>
                    
                    <!-- Donation Details -->
                    <div class="card border-0 bg--light mb-5">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4 text--heading">Submission Details</h5>
                            <div class="row text-start">
                                <div class="col-md-6 mb-3">
                                    <div class="text--muted small">Reference Number</div>
                                    <div class="fw-bold text--dark">{{ $donation->payment_reference }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text--muted small">Date Submitted</div>
                                    <div class="fw-bold text--dark">{{ $donation->created_at->format('F d, Y h:i A') }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text--muted small">Amount</div>
                                    <div class="fw-bold text--dark">{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text--muted small">Status</div>
                                    <div class="fw-bold text--warning">Pending Verification</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- What Happens Next -->
                    <div class="alert alert--info mb-5 bg--info bg--info/10 border--info">
                        <h6 class="fw-bold text--white mb-3">
                            <i class="fas fa-info-circle me-2"></i>What happens next?
                        </h6>
                        <ul class="mb-0 text-start text--white">
                            <li>Our team will verify your payment within 1-3 business days</li>
                            <li>You'll receive an email confirmation once verified</li>
                            <li>The campaign page will update with your donation</li>
                            <li>A tax receipt will be emailed if applicable</li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                        <a href="{{ route('fundraisers.show', $donation->fundraiser->slug) }}" class="btn btn--outline-base">
                            <i class="fas fa-heart me-2"></i> Back to Campaign
                        </a>
                        <a href="{{ route('home') }}" class="btn btn--success">
                            <i class="fas fa-home me-2"></i> Return Home
                        </a>
                    </div>
                    
                    <!-- Contact Support -->
                    <div class="mt-5 pt-4 border-top border--light">
                        <h6 class="fw-bold mb-3 text--heading">Need Help?</h6>
                        <p class="text--muted mb-3">If you have any questions about your donation, please contact our support team.</p>
                        <a href="mailto:support@tendocharity.com" class="btn btn--sm btn--outline-secondary">
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
    background: linear-gradient(135deg, hsl(var(--warning)) 0%, hsl(var(--warning)/0.8) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 25px -5px hsl(var(--warning)/0.4);
}
.pending-icon i {
    font-size: 50px;
    color: hsl(var(--white));
}

/* Custom alert style if needed */
.alert--info {
    background-color: hsl(var(--info)/0.1);
    border: 1px solid hsl(var(--info));
    border-radius: 5px;
    padding: 1rem;
}

.alert--info h6 {
    color: hsl(var(--info-600));
}

.alert--info ul li {
    color: hsl(var(--body));
    margin-bottom: 0.5rem;
}

.alert--info ul li:last-child {
    margin-bottom: 0;
}
</style>
@endpush

@push('script')
<script>
    // Optional: Add any JavaScript if needed
</script>
@endpush