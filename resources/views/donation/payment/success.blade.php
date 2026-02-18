@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="success-icon mx-auto">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <h1 class="fw-bold text-success mb-3">Thank You!</h1>
                    <p class="lead mb-4">Your donation has been successfully processed.</p>
                    
                    <!-- Donation Details -->
                    <div class="card border-0 bg-light mb-5">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4">Donation Receipt</h5>
                            <div class="row text-start">
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted small">Reference Number</div>
                                    <div class="fw-bold">{{ $donation->payment_reference }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted small">Date & Time</div>
                                    <div class="fw-bold">{{ $donation->created_at->format('F d, Y h:i A') }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted small">Amount Donated</div>
                                    <div class="fw-bold">{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="text-muted small">Payment Method</div>
                                    <div class="fw-bold">{{ ucwords(str_replace('_', ' ', $donation->payment_method)) }}</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="text-muted small">Campaign</div>
                                    <div class="fw-bold">{{ $donation->fundraiser->title ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-12">
                                    <div class="text-muted small">Donor Name</div>
                                    <div class="fw-bold">{{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                        <a href="{{ route('fundraisers.show', $donation->fundraiser->slug) }}" class="btn btn-outline-primary">
                            <i class="fas fa-heart me-2"></i> Back to Campaign
                        </a>
                        <a href="#" class="btn btn-primary" id="printReceipt">
                            <i class="fas fa-print me-2"></i> Print Receipt
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-success">
                            <i class="fas fa-home me-2"></i> Return Home
                        </a>
                    </div>
                    
                    <!-- Email Notification -->
                    <div class="alert alert-info mt-5">
                        <div class="d-flex">
                            <i class="fas fa-envelope fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold">Receipt Sent to Email</h6>
                                <p class="mb-0">A donation receipt has been sent to <strong>{{ $donation->donor_email }}</strong>. 
                                Please check your inbox and spam folder.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Share Options -->
                    <div class="mt-5">
                        <h6 class="fw-bold mb-3">Share Your Support</h6>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="btn btn-sm btn-outline-primary share-btn" data-platform="facebook">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-info share-btn" data-platform="twitter">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-danger share-btn" data-platform="linkedin">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-success share-btn" data-platform="whatsapp">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Next Steps -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">What Happens Next?</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3">
                                <div class="mb-3">
                                    <i class="fas fa-envelope-open-text fa-2x text-primary"></i>
                                </div>
                                <h6 class="fw-bold">Email Confirmation</h6>
                                <p class="small text-muted">You'll receive a detailed receipt via email within 24 hours.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3">
                                <div class="mb-3">
                                    <i class="fas fa-chart-line fa-2x text-success"></i>
                                </div>
                                <h6 class="fw-bold">Campaign Impact</h6>
                                <p class="small text-muted">Track how your donation helps the campaign reach its goal.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3">
                                <div class="mb-3">
                                    <i class="fas fa-taxi fa-2x text-warning"></i>
                                </div>
                                <h6 class="fw-bold">Tax Receipt</h6>
                                <p class="small text-muted">For tax-deductible donations, official receipt will be emailed in 7-10 days.</p>
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
.success-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #28a745, #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
.success-icon i {
    font-size: 50px;
    color: white;
}
.share-btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s;
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print receipt
    document.getElementById('printReceipt').addEventListener('click', function(e) {
        e.preventDefault();
        window.print();
    });
    
    // Share buttons
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const platform = this.dataset.platform;
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent("I just donated to {{ $donation->fundraiser->title ?? 'a great cause' }} on Tendo Charity!");
            
            let shareUrl = '';
            switch(platform) {
                case 'facebook':
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case 'twitter':
                    shareUrl = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;
                    break;
                case 'linkedin':
                    shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
                    break;
                case 'whatsapp':
                    shareUrl = `https://wa.me/?text=${text}%20${url}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });
});
</script>
@endpush