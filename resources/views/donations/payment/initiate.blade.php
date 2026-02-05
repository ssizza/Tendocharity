@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Steps -->
            <div class="steps mb-5">
                <div class="step active">
                    <div class="step-number">1</div>
                    <div class="step-label">Donor Info</div>
                </div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <div class="step-label">Payment Method</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-label">Confirmation</div>
                </div>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-4 p-lg-5">
                    <div class="row">
                        <!-- Left Column: Donation Info -->
                        <div class="col-lg-6">
                            <h2 class="fw-bold mb-4">Complete Your Donation</h2>
                            
                            <!-- Fundraiser Info -->
                            <div class="card border-0 bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">You're donating to:</h5>
                                    <div class="d-flex align-items-start">
                                        <img src="{{ $fundraiser->featured_image_url }}" 
                                             class="rounded me-3" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h6 class="fw-bold mb-1">{{ $fundraiser->title }}</h6>
                                            <p class="small text-muted mb-2">{{ Str::limit($fundraiser->tagline, 100) }}</p>
                                            <div class="progress mb-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" 
                                                     style="width: {{ $fundraiser->progress_percentage }}%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between small">
                                                <span class="text-muted">{{ round($fundraiser->progress_percentage, 1) }}% funded</span>
                                                <span class="text-primary">{{ $fundraiser->currency }} {{ number_format($fundraiser->raised_amount) }} raised</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Donation Summary -->
                            <div class="card border-0 bg-light">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Donation Summary</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Donation Amount:</span>
                                        <span class="fw-bold" id="donationAmountSummary">{{ $fundraiser->currency }} 0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Payment Fee:</span>
                                        <span class="fw-bold" id="paymentFeeSummary">{{ $fundraiser->currency }} 0.00</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total to Pay:</span>
                                        <span id="totalAmountSummary">{{ $fundraiser->currency }} 0.00</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Payment Form -->
                        <div class="col-lg-6">
                            <form action="{{ route('donation.insert', $fundraiser->id) }}" method="POST" id="paymentForm">
                                @csrf
                                
                                <!-- Donation Amount -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Donation Amount ({{ $fundraiser->currency }})</label>
                                    <div class="row g-2 mb-3">
                                        @foreach([25, 50, 100, 250, 500] as $amount)
                                        <div class="col-6 col-md-4">
                                            <button type="button" class="btn btn-outline-primary w-100 amount-btn" 
                                                    data-amount="{{ $amount }}">
                                                {{ $amount }}
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ $fundraiser->currency }}</span>
                                        <input type="number" class="form-control" id="amount" name="amount" 
                                               placeholder="Enter custom amount" min="1" step="0.01" required>
                                        <input type="hidden" id="gateway" name="gateway">
                                        <input type="hidden" id="currency" name="currency">
                                    </div>
                                </div>
                                
                                <!-- Donor Information -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Your Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="first_name" 
                                                   placeholder="First Name *" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="last_name" 
                                                   placeholder="Last Name *" required>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="email" class="form-control" name="email" 
                                                   placeholder="Email Address *" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="phone" 
                                                   placeholder="Phone Number">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" name="country" 
                                                   placeholder="Country">
                                        </div>
                                        <div class="col-md-12">
                                            <textarea class="form-control" name="message" rows="2" 
                                                      placeholder="Message of support (optional)"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Donation Options -->
                                <div class="mb-4">
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
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="receive_updates" id="receiveUpdates" checked>
                                        <label class="form-check-label" for="receiveUpdates">
                                            Receive updates about this fundraiser
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Payment Methods -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3">Select Payment Method</h5>
                                    <div class="row g-3" id="paymentMethods">
                                        @foreach($gatewayCurrency as $gateway)
                                        <div class="col-12">
                                            <div class="card payment-method-card" data-gateway="{{ $gateway->method_code }}" data-currency="{{ $gateway->currency }}">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="form-check mb-0">
                                                        <input class="form-check-input" type="radio" 
                                                               name="gateway_selector" 
                                                               id="gateway_{{ $gateway->id }}"
                                                               data-gateway="{{ $gateway->method_code }}"
                                                               data-currency="{{ $gateway->currency }}">
                                                        <label class="form-check-label d-flex align-items-center" for="gateway_{{ $gateway->id }}">
                                                            <img src="{{ getImage(getFilePath('gateway').'/'.$gateway->method->image) }}" 
                                                                 class="me-3" style="height: 30px;">
                                                            <div>
                                                                <div class="fw-bold">{{ $gateway->name }}</div>
                                                                <small class="text-muted">
                                                                    Fee: {{ $gateway->fixed_charge }} + {{ $gateway->percent_charge }}%
                                                                </small>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                        <i class="fas fa-lock me-2"></i> Proceed to Payment
                                    </button>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i> Secure SSL encrypted payment
                                    </small>
                                </div>
                            </form>
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
    flex: 1;
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
    font-weight: bold;
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
.payment-method-card {
    border: 2px solid #dee2e6;
    cursor: pointer;
    transition: all 0.3s;
}
.payment-method-card:hover,
.payment-method-card.active {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05);
}
.amount-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const gatewayInput = document.getElementById('gateway');
    const currencyInput = document.getElementById('currency');
    const submitBtn = document.getElementById('submitBtn');
    const donationAmountSummary = document.getElementById('donationAmountSummary');
    const paymentFeeSummary = document.getElementById('paymentFeeSummary');
    const totalAmountSummary = document.getElementById('totalAmountSummary');
    
    let selectedGateway = null;
    let selectedCurrency = null;
    let gatewayData = @json($gatewayCurrency->keyBy('method_code'));
    
    // Amount buttons
    document.querySelectorAll('.amount-btn').forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.dataset.amount;
            amountInput.value = amount;
            updateDonationSummary();
            
            // Update button states
            document.querySelectorAll('.amount-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
    
    // Payment method selection
    document.querySelectorAll('.payment-method-card').forEach(card => {
        card.addEventListener('click', function() {
            const gateway = this.dataset.gateway;
            const currency = this.dataset.currency;
            
            // Update selection
            document.querySelectorAll('.payment-method-card').forEach(c => {
                c.classList.remove('active');
            });
            this.classList.add('active');
            
            // Update radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Update form inputs
            selectedGateway = gateway;
            selectedCurrency = currency;
            gatewayInput.value = gateway;
            currencyInput.value = currency;
            
            updateDonationSummary();
            validateForm();
        });
    });
    
    // Real-time amount update
    amountInput.addEventListener('input', function() {
        updateDonationSummary();
        
        // Clear amount button selection
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.classList.remove('active');
        });
    });
    
    function updateDonationSummary() {
        const amount = parseFloat(amountInput.value) || 0;
        const gateway = selectedGateway ? gatewayData[selectedGateway] : null;
        
        if (gateway && amount > 0) {
            const charge = gateway.fixed_charge + (amount * gateway.percent_charge / 100);
            const total = amount + charge;
            
            donationAmountSummary.textContent = `{{ $fundraiser->currency }} ${amount.toFixed(2)}`;
            paymentFeeSummary.textContent = `{{ $fundraiser->currency }} ${charge.toFixed(2)}`;
            totalAmountSummary.textContent = `{{ $fundraiser->currency }} ${total.toFixed(2)}`;
        } else {
            donationAmountSummary.textContent = `{{ $fundraiser->currency }} 0.00`;
            paymentFeeSummary.textContent = `{{ $fundraiser->currency }} 0.00`;
            totalAmountSummary.textContent = `{{ $fundraiser->currency }} 0.00`;
        }
    }
    
    function validateForm() {
        const amount = parseFloat(amountInput.value) || 0;
        const isValid = amount >= 1 && selectedGateway !== null;
        
        submitBtn.disabled = !isValid;
    }
    
    // Validate on changes
    amountInput.addEventListener('input', validateForm);
    
    // Form submission
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const amount = parseFloat(amountInput.value);
        if (amount < 1) {
            e.preventDefault();
            alert('Please enter a donation amount of at least 1.');
            amountInput.focus();
            return false;
        }
        
        if (!selectedGateway) {
            e.preventDefault();
            alert('Please select a payment method.');
            return false;
        }
    });
});
</script>
<script>
    // Temporary fix - enable button regardless
document.addEventListener('DOMContentLoaded', function() {
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = false;
        
        // Also auto-select first payment method if none selected
        const firstPaymentMethod = document.querySelector('.payment-method-card');
        if (firstPaymentMethod) {
            const radio = firstPaymentMethod.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
                firstPaymentMethod.classList.add('active');
                
                // Set hidden inputs
                document.getElementById('gateway').value = firstPaymentMethod.dataset.gateway;
                document.getElementById('currency').value = firstPaymentMethod.dataset.currency;
            }
        }
    }
    
    // Auto-fill amount from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const amount = urlParams.get('amount');
    if (amount) {
        document.getElementById('amount').value = amount;
    }
    
    // Auto-fill name and email from URL parameters
    const donorName = urlParams.get('donor_name');
    const donorEmail = urlParams.get('donor_email');
    
    if (donorName) {
        const nameParts = donorName.split(' ');
        document.querySelector('input[name="first_name"]').value = nameParts[0] || '';
        document.querySelector('input[name="last_name"]').value = nameParts[1] || '';
    }
    
    if (donorEmail) {
        document.querySelector('input[name="email"]').value = donorEmail;
    }
});
</script>
@endpush