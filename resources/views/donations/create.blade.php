@extends('layouts.frontend')

@section('title', 'Donate to ' . $campaign->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <!-- Progress Steps -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="steps">
                                <div class="step active">
                                    <div class="step-number">1</div>
                                    <div class="step-label">Donation Details</div>
                                </div>
                                <div class="step">
                                    <div class="step-number">2</div>
                                    <div class="step-label">Payment</div>
                                </div>
                                <div class="step">
                                    <div class="step-number">3</div>
                                    <div class="step-label">Confirmation</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-4">Make a Donation</h2>
                            <p class="text-muted mb-4">Supporting: <strong>{{ $campaign->title }}</strong></p>
                            
                            <form action="{{ route('donations.store', $campaign) }}" method="POST" id="donationForm">
                                @csrf
                                
                                <!-- Donation Amount -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Select Donation Amount ({{ $campaign->currency }})</label>
                                    <div class="row g-2 mb-3">
                                        @foreach([25, 50, 100, 250, 500] as $amount)
                                        <div class="col-4 col-md-auto">
                                            <button type="button" class="btn btn-outline-primary w-100 amount-btn" 
                                                    data-amount="{{ $amount }}">
                                                {{ $amount }}
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $campaign->currency }}</span>
                                        <input type="number" class="form-control" id="amount" name="amount" 
                                               placeholder="Enter custom amount" min="1" step="0.01" required>
                                    </div>
                                </div>
                                
                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Personal Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text" class="form-control" name="donor_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Email Address *</label>
                                            <input type="email" class="form-control" name="donor_email" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="donor_phone">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Country</label>
                                            <input type="text" class="form-control" name="donor_address">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Donation Options -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Donation Options</h5>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonymous">
                                        <label class="form-check-label" for="anonymous">
                                            Make this an anonymous donation
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="tax_deductible" id="taxDeductible">
                                        <label class="form-check-label" for="taxDeductible">
                                            This donation is tax deductible
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Payment Method -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Payment Method</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-check card-option">
                                                <input class="form-check-input" type="radio" name="payment_method" 
                                                       value="credit_card" id="creditCard" checked>
                                                <label class="form-check-label" for="creditCard">
                                                    <i class="fab fa-cc-visa fa-2x me-2"></i>
                                                    <i class="fab fa-cc-mastercard fa-2x me-2"></i>
                                                    <i class="fab fa-cc-amex fa-2x"></i>
                                                    <div class="mt-1">Credit/Debit Card</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check card-option">
                                                <input class="form-check-input" type="radio" name="payment_method" 
                                                       value="bank_transfer" id="bankTransfer">
                                                <label class="form-check-label" for="bankTransfer">
                                                    <i class="fas fa-university fa-2x me-2"></i>
                                                    <div class="mt-1">Bank Transfer</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check card-option">
                                                <input class="form-check-input" type="radio" name="payment_method" 
                                                       value="digital_wallet" id="digitalWallet">
                                                <label class="form-check-label" for="digitalWallet">
                                                    <i class="fas fa-wallet fa-2x me-2"></i>
                                                    <div class="mt-1">Digital Wallet</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Message -->
                                <div class="mb-4">
                                    <label class="form-label">Message of Support (Optional)</label>
                                    <textarea class="form-control" name="message" rows="3" 
                                              placeholder="Add a personal message to show your support..."></textarea>
                                </div>
                                
                                <!-- Terms -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" class="text-primary">Terms of Service</a> and 
                                            <a href="#" class="text-primary">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Submit -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-danger btn-lg">
                                        <i class="fas fa-lock me-2"></i> Complete Donation
                                    </button>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i> Secure SSL encrypted payment
                                    </small>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Campaign Summary -->
                        <div class="col-lg-4">
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Campaign Summary</h5>
                                    <div class="mb-3">
                                        <img src="{{ $campaign->image_url }}" class="img-fluid rounded mb-3" 
                                             alt="{{ $campaign->title }}">
                                        <h6 class="fw-bold">{{ $campaign->title }}</h6>
                                        <p class="small text-muted">{{ Str::limit($campaign->tagline, 80) }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $campaign->funding_percentage }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span class="text-muted">{{ $campaign->funding_percentage }}% funded</span>
                                            <span class="text-primary">{{ $campaign->formatted_raised }} raised</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Donors:</span>
                                            <span class="fw-bold">{{ $campaign->donors_count }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Beneficiaries:</span>
                                            <span class="fw-bold">{{ $campaign->beneficiaries_count }}</span>
                                        </div>
                                        @if($campaign->days_remaining !== null)
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Days Left:</span>
                                            <span class="fw-bold">{{ $campaign->days_remaining }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="border-top pt-3">
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Your Donation:</span>
                                            <span id="donationSummary">{{ $campaign->currency }} 0.00</span>
                                        </div>
                                    </div>
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

@push('styles')
<style>
    .steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    .steps::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 0;
        right: 0;
        height: 2px;
        background: #dee2e6;
        z-index: 1;
    }
    .step {
        text-align: center;
        position: relative;
        z-index: 2;
    }
    .step-number {
        width: 30px;
        height: 30px;
        background: #6c757d;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
    }
    .step.active .step-number {
        background: #0d6efd;
    }
    .step-label {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .step.active .step-label {
        color: #0d6efd;
        font-weight: 500;
    }
    .card-option {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .card-option:hover {
        border-color: #0d6efd;
    }
    .card-option .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.getElementById('amount');
        const donationSummary = document.getElementById('donationSummary');
        const currency = '{{ $campaign->currency }}';
        
        // Amount buttons
        document.querySelectorAll('.amount-btn').forEach(button => {
            button.addEventListener('click', function() {
                const amount = this.dataset.amount;
                amountInput.value = amount;
                updateDonationSummary(amount);
            });
        });
        
        // Real-time amount update
        amountInput.addEventListener('input', function() {
            updateDonationSummary(this.value);
        });
        
        function updateDonationSummary(amount) {
            if (amount) {
                donationSummary.textContent = `${currency} ${parseFloat(amount).toFixed(2)}`;
            } else {
                donationSummary.textContent = `${currency} 0.00`;
            }
        }
        
        // Form validation
        document.getElementById('donationForm').addEventListener('submit', function(e) {
            const amount = parseFloat(amountInput.value);
            if (amount < 1) {
                e.preventDefault();
                alert('Please enter a donation amount of at least 1.');
                amountInput.focus();
            }
        });
    });
</script>
@endpush