@extends('layouts.frontend')

@section('title', 'Donate to ' . $campaign->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="custom--card border-0">
                <div class="card-body p-5">
                    <!-- Progress Steps -->
                    <div class="row mb-5">
                        <div class="col-12">
                            <div class="steps">
                                <div class="step active">
                                    <div class="step-number bg--base text--white">1</div>
                                    <div class="step-label text--base fw-medium">Donation Details</div>
                                </div>
                                <div class="step">
                                    <div class="step-number bg--secondary text--white">2</div>
                                    <div class="step-label text--secondary">Payment</div>
                                </div>
                                <div class="step">
                                    <div class="step-number bg--secondary text--white">3</div>
                                    <div class="step-label text--secondary">Confirmation</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-4 text--heading">Make a Donation</h2>
                            <p class="text--body mb-4">Supporting: <strong class="text--base">{{ $campaign->title }}</strong></p>
                            
                            <form action="{{ route('donations.store', $campaign) }}" method="POST" id="donationForm">
                                @csrf
                                
                                <!-- Donation Amount -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold text--body">Select Donation Amount ({{ $campaign->currency }})</label>
                                    <div class="row g-2 mb-3">
                                        @foreach([25, 50, 100, 250, 500] as $amount)
                                        <div class="col-4 col-md-auto">
                                            <button type="button" class="btn btn--outline-base w-100 amount-btn" 
                                                    data-amount="{{ $amount }}">
                                                {{ $amount }}
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text bg--light border--base text--base">{{ $campaign->currency }}</span>
                                        <input type="number" class="form--control" id="amount" name="amount" 
                                               placeholder="Enter custom amount" min="1" step="0.01" required>
                                    </div>
                                </div>
                                
                                <!-- Personal Information -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 text--heading">Personal Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label text--body">Full Name *</label>
                                            <input type="text" class="form--control" name="donor_name" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text--body">Email Address *</label>
                                            <input type="email" class="form--control" name="donor_email" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text--body">Phone Number</label>
                                            <input type="text" class="form--control" name="donor_phone">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text--body">Country</label>
                                            <input type="text" class="form--control" name="donor_address">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Donation Options -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 text--heading">Donation Options</h5>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonymous">
                                        <label class="form-check-label text--body" for="anonymous">
                                            Make this an anonymous donation
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="tax_deductible" id="taxDeductible">
                                        <label class="form-check-label text--body" for="taxDeductible">
                                            This donation is tax deductible
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Payment Method -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 text--heading">Payment Method</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="card-option border--base">
                                                <input class="form-check-input" type="radio" name="payment_method" 
                                                       value="credit_card" id="creditCard" checked>
                                                <label class="form-check-label text--body" for="creditCard">
                                                    <i class="fab fa-cc-visa fa-2x me-2 text--base"></i>
                                                    <i class="fab fa-cc-mastercard fa-2x me-2 text--base"></i>
                                                    <i class="fab fa-cc-amex fa-2x text--base"></i>
                                                    <div class="mt-1">Credit/Debit Card</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-option border--base">
                                                <input class="form-check-input" type="radio" name="payment_method" 
                                                       value="bank_transfer" id="bankTransfer">
                                                <label class="form-check-label text--body" for="bankTransfer">
                                                    <i class="fas fa-university fa-2x me-2 text--base"></i>
                                                    <div class="mt-1">Bank Transfer</div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card-option border--base">
                                                <input class="form-check-input" type="radio" name="payment_method" 
                                                       value="digital_wallet" id="digitalWallet">
                                                <label class="form-check-label text--body" for="digitalWallet">
                                                    <i class="fas fa-wallet fa-2x me-2 text--base"></i>
                                                    <div class="mt-1">Digital Wallet</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Message -->
                                <div class="mb-4">
                                    <label class="form-label text--body">Message of Support (Optional)</label>
                                    <textarea class="form--control" name="message" rows="3" 
                                              placeholder="Add a personal message to show your support..."></textarea>
                                </div>
                                
                                <!-- Terms -->
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label text--body" for="terms">
                                            I agree to the <a href="#" class="text--base">Terms of Service</a> and 
                                            <a href="#" class="text--base">Privacy Policy</a>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Submit -->
                                <div class="d-grid">
                                    <button type="submit" class="btn cmn--btn btn--lg">
                                        <i class="fas fa-lock me-2"></i> Complete Donation
                                    </button>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text--body">
                                        <i class="fas fa-shield-alt me-1 text--base"></i> Secure SSL encrypted payment
                                    </small>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Campaign Summary -->
                        <div class="col-lg-4">
                            <div class="custom--card border-0 bg--light">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3 text--heading">Campaign Summary</h5>
                                    <div class="mb-3">
                                        <img src="{{ $campaign->image_url }}" class="img-fluid rounded mb-3" 
                                             alt="{{ $campaign->title }}">
                                        <h6 class="fw-bold text--heading">{{ $campaign->title }}</h6>
                                        <p class="small text--body">{{ Str::limit($campaign->tagline, 80) }}</p>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="progress mb-2" style="height: 6px;">
                                            <div class="progress-bar bg--success" 
                                                 style="width: {{ $campaign->funding_percentage }}%"></div>
                                        </div>
                                        <div class="d-flex justify-content-between small">
                                            <span class="text--body">{{ $campaign->funding_percentage }}% funded</span>
                                            <span class="text--base">{{ $campaign->formatted_raised }} raised</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text--body">Donors:</span>
                                            <span class="fw-bold text--heading">{{ $campaign->donors_count }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text--body">Beneficiaries:</span>
                                            <span class="fw-bold text--heading">{{ $campaign->beneficiaries_count }}</span>
                                        </div>
                                        @if($campaign->days_remaining !== null)
                                        <div class="d-flex justify-content-between">
                                            <span class="text--body">Days Left:</span>
                                            <span class="fw-bold text--heading">{{ $campaign->days_remaining }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="border-top border--base pt-3">
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span class="text--heading">Your Donation:</span>
                                            <span class="text--base" id="donationSummary">{{ $campaign->currency }} 0.00</span>
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
        background: hsl(var(--border));
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
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .step.active .step-number {
        background-color: hsl(var(--base));
        color: hsl(var(--white));
    }
    .step.active .step-label {
        color: hsl(var(--base));
    }
    .step-label {
        font-size: 0.875rem;
        color: hsl(var(--body));
    }
    .card-option {
        border: 2px solid hsl(var(--border));
        border-radius: 8px;
        padding: 15px;
        cursor: pointer;
        transition: all 0.3s;
        background-color: hsl(var(--white));
    }
    .card-option:hover {
        border-color: hsl(var(--base));
    }
    .card-option:has(input[type="radio"]:checked) {
        border-color: hsl(var(--base));
        background-color: hsl(var(--base)/0.05);
    }
    .card-option .form-check-input {
        cursor: pointer;
    }
    .card-option .form-check-input:checked {
        background-color: hsl(var(--base));
        border-color: hsl(var(--base));
    }
    .card-option .form-check-label {
        cursor: pointer;
        width: 100%;
    }
    .input-group-text {
        border: 1px solid hsl(var(--border));
        border-radius: 5px 0 0 5px;
        background-color: hsl(var(--light));
        color: hsl(var(--base));
    }
    .amount-btn.btn--base {
        background-color: hsl(var(--base));
        border-color: hsl(var(--base));
        color: hsl(var(--white));
    }
    .amount-btn.btn--base:hover {
        background-color: hsl(var(--base-600));
    }
    .progress-bar.bg--success {
        background-color: hsl(var(--success)) !important;
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
                
                // Visual feedback
                document.querySelectorAll('.amount-btn').forEach(btn => {
                    btn.classList.remove('btn--base');
                    btn.classList.add('btn--outline-base');
                });
                this.classList.remove('btn--outline-base');
                this.classList.add('btn--base');
            });
        });
        
        // Real-time amount update
        amountInput.addEventListener('input', function() {
            updateDonationSummary(this.value);
        });
        
        function updateDonationSummary(amount) {
            if (amount && !isNaN(amount) && parseFloat(amount) > 0) {
                donationSummary.textContent = `${currency} ${parseFloat(amount).toFixed(2)}`;
            } else {
                donationSummary.textContent = `${currency} 0.00`;
            }
        }
        
        // Payment method card selection
        document.querySelectorAll('.card-option').forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking directly on radio input (prevents double triggering)
                if (e.target.type === 'radio') return;
                
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    
                    // Trigger change event for any listeners
                    const event = new Event('change', { bubbles: true });
                    radio.dispatchEvent(event);
                }
            });
            
            // Add change listener to radio for styling
            const radio = card.querySelector('input[type="radio"]');
            if (radio) {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        // Remove checked state from all cards
                        document.querySelectorAll('.card-option').forEach(c => {
                            c.style.borderColor = 'hsl(var(--border))';
                        });
                        // Add checked state to this card
                        card.style.borderColor = 'hsl(var(--base))';
                    }
                });
            }
        });
        
        // Form validation
        document.getElementById('donationForm').addEventListener('submit', function(e) {
            const amount = parseFloat(amountInput.value);
            if (isNaN(amount) || amount < 1) {
                e.preventDefault();
                alert('Please enter a valid donation amount of at least 1.');
                amountInput.focus();
            }
            
            // Check terms
            const terms = document.getElementById('terms');
            if (!terms.checked) {
                e.preventDefault();
                alert('Please agree to the Terms of Service and Privacy Policy.');
                terms.focus();
            }
        });
    });
</script>
@endpush