@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- Main Success Card --}}
            <div class="card border-0 text-center" style="background-color: hsl(var(--white)); box-shadow: 0 0.5rem 1rem hsl(var(--dark)/0.15); border-radius: 0.5rem;">
                <div class="card-body p-5">
                    {{-- Success Icon --}}
                    <div class="mb-4">
                        <div class="mx-auto" style="width: 100px; height: 100px; background: linear-gradient(135deg, hsl(var(--success)), hsl(var(--info))); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <i class="fas fa-check-circle" style="font-size: 50px; color: hsl(var(--white));"></i>
                        </div>
                    </div>
                    
                    {{-- Success Message --}}
                    <h1 class="fw-bold mb-3" style="color: hsl(var(--success));">Thank You!</h1>
                    <p class="lead mb-4" style="color: hsl(var(--body));">Your donation has been successfully processed.</p>
                    
                    {{-- Donation Details --}}
                    <div class="card border-0 mb-5" style="background-color: hsl(var(--light)); border-radius: 0.5rem;">
                        <div class="card-body">
                            <h5 class="fw-bold mb-4" style="color: hsl(var(--heading));">Donation Receipt</h5>
                            <div class="row text-start">
                                <div class="col-md-6 mb-3">
                                    <div class="small" style="color: hsl(var(--body)/0.7);">Reference Number</div>
                                    <div class="fw-bold" style="color: hsl(var(--heading));">{{ $donation->payment_reference }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="small" style="color: hsl(var(--body)/0.7);">Date & Time</div>
                                    <div class="fw-bold" style="color: hsl(var(--heading));">{{ $donation->created_at->format('F d, Y h:i A') }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="small" style="color: hsl(var(--body)/0.7);">Amount Donated</div>
                                    <div class="fw-bold" style="color: hsl(var(--heading));">{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="small" style="color: hsl(var(--body)/0.7);">Payment Method</div>
                                    <div class="fw-bold" style="color: hsl(var(--heading));">{{ ucwords(str_replace('_', ' ', $donation->payment_method)) }}</div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="small" style="color: hsl(var(--body)/0.7);">Campaign</div>
                                    <div class="fw-bold" style="color: hsl(var(--heading));">{{ $donation->fundraiser->title ?? 'N/A' }}</div>
                                </div>
                                <div class="col-md-12">
                                    <div class="small" style="color: hsl(var(--body)/0.7);">Donor Name</div>
                                    <div class="fw-bold" style="color: hsl(var(--heading));">{{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Action Buttons --}}
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                        <a href="{{ route('fundraisers.show', $donation->fundraiser->slug) }}" class="btn" style="background-color: transparent; border: 2px solid hsl(var(--base)); color: hsl(var(--base)); padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: 500; transition: all 0.3s;">
                            <i class="fas fa-heart me-2"></i> Back to Campaign
                        </a>
                        <a href="#" class="btn" id="printReceipt" style="background-color: hsl(var(--base)); border: 2px solid hsl(var(--base)); color: hsl(var(--white)); padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: 500; transition: all 0.3s;">
                            <i class="fas fa-print me-2"></i> Print Receipt
                        </a>
                        <a href="{{ route('home') }}" class="btn" style="background-color: hsl(var(--success)); border: 2px solid hsl(var(--success)); color: hsl(var(--white)); padding: 0.5rem 1rem; border-radius: 0.25rem; font-weight: 500; transition: all 0.3s;">
                            <i class="fas fa-home me-2"></i> Return Home
                        </a>
                    </div>
                    
                    {{-- Email Notification --}}
                    <div class="mt-5 p-3" style="background-color: hsl(var(--info)/0.1); border: 1px solid hsl(var(--info)); border-radius: 0.5rem;">
                        <div class="d-flex">
                            <i class="fas fa-envelope" style="font-size: 2rem; margin-right: 1rem; color: hsl(var(--info));"></i>
                            <div>
                                <h6 class="fw-bold" style="color: hsl(var(--info));">Receipt Sent to Email</h6>
                                <p class="mb-0" style="color: hsl(var(--body));">A donation receipt has been sent to <strong style="color: hsl(var(--heading));">{{ $donation->donor_email }}</strong>. 
                                Please check your inbox and spam folder.</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Share Options --}}
                    <div class="mt-5">
                        <h6 class="fw-bold mb-3" style="color: hsl(var(--heading));">Share Your Support</h6>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="#" class="btn share-btn" data-platform="facebook" style="background-color: transparent; border: 2px solid hsl(var(--base)); color: hsl(var(--base)); padding: 0.25rem 1rem; font-size: 0.875rem; border-radius: 0.25rem; font-weight: 500; transition: all 0.3s;">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a href="#" class="btn share-btn" data-platform="twitter" style="background-color: transparent; border: 2px solid hsl(var(--info)); color: hsl(var(--info)); padding: 0.25rem 1rem; font-size: 0.875rem; border-radius: 0.25rem; font-weight: 500; transition: all 0.3s;">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a href="#" class="btn share-btn" data-platform="linkedin" style="background-color: transparent; border: 2px solid hsl(var(--danger)); color: hsl(var(--danger)); padding: 0.25rem 1rem; font-size: 0.875rem; border-radius: 0.25rem; font-weight: 500; transition: all 0.3s;">
                                <i class="fab fa-linkedin-in"></i> LinkedIn
                            </a>
                            <a href="#" class="btn share-btn" data-platform="whatsapp" style="background-color: transparent; border: 2px solid hsl(var(--success)); color: hsl(var(--success)); padding: 0.25rem 1rem; font-size: 0.875rem; border-radius: 0.25rem; font-weight: 500; transition: all 0.3s;">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Next Steps --}}
            <div class="card border-0 mt-4" style="background-color: hsl(var(--white)); box-shadow: 0 0.125rem 0.25rem hsl(var(--dark)/0.075); border-radius: 0.5rem;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3" style="color: hsl(var(--heading));">What Happens Next?</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3">
                                <div class="mb-3">
                                    <i class="fas fa-envelope-open-text" style="font-size: 2rem; color: hsl(var(--base));"></i>
                                </div>
                                <h6 class="fw-bold" style="color: hsl(var(--heading));">Email Confirmation</h6>
                                <p class="small" style="color: hsl(var(--body)/0.7);">You'll receive a detailed receipt via email within 24 hours.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3">
                                <div class="mb-3">
                                    <i class="fas fa-chart-line" style="font-size: 2rem; color: hsl(var(--success));"></i>
                                </div>
                                <h6 class="fw-bold" style="color: hsl(var(--heading));">Campaign Impact</h6>
                                <p class="small" style="color: hsl(var(--body)/0.7);">Track how your donation helps the campaign reach its goal.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="text-center p-3">
                                <div class="mb-3">
                                    <i class="fas fa-receipt" style="font-size: 2rem; color: hsl(var(--warning));"></i>
                                </div>
                                <h6 class="fw-bold" style="color: hsl(var(--heading));">Tax Receipt</h6>
                                <p class="small" style="color: hsl(var(--body)/0.7);">For tax-deductible donations, official receipt will be emailed in 7-10 days.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.share-btn:hover {
    transform: translateY(-2px);
    transition: all 0.3s;
}

/* Button hover effects using your theme colors */
.btn[style*="background-color: transparent"]:hover {
    background-color: hsl(var(--base)/0.1) !important;
}

.btn[style*="background-color: hsl(var(--base))"]:hover {
    background-color: hsl(var(--base-600)) !important;
    border-color: hsl(var(--base-600)) !important;
}

.btn[style*="background-color: hsl(var(--success))"]:hover {
    background-color: hsl(var(--success)/0.9) !important;
    border-color: hsl(var(--success)/0.9) !important;
}

/* Share button specific hover effects */
.btn[data-platform="facebook"]:hover {
    background-color: hsl(var(--base)) !important;
    color: hsl(var(--white)) !important;
}

.btn[data-platform="twitter"]:hover {
    background-color: hsl(var(--info)) !important;
    color: hsl(var(--white)) !important;
}

.btn[data-platform="linkedin"]:hover {
    background-color: hsl(var(--danger)) !important;
    color: hsl(var(--white)) !important;
}

.btn[data-platform="whatsapp"]:hover {
    background-color: hsl(var(--success)) !important;
    color: hsl(var(--white)) !important;
}
</style>
@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print receipt
    document.getElementById('printReceipt')?.addEventListener('click', function(e) {
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