@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container pt-120 pb-120">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Steps - Using theme variables -->
            <div class="steps mb-5 position-relative d-flex justify-content-between">
                <div class="step text-center position-relative flex-1">
                    <div class="step-number width-30 height-30 rounded--circle bg--base text--white d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold">1</div>
                    <div class="step-label fs--14px text--base fw-medium">Donor Info</div>
                </div>
                <div class="step text-center position-relative flex-1">
                    <div class="step-number width-30 height-30 rounded--circle bg--base text--white d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold">2</div>
                    <div class="step-label fs--14px text--base fw-medium">Payment Method</div>
                </div>
                <div class="step text-center position-relative flex-1">
                    <div class="step-number width-30 height-30 rounded--circle bg--light text--body d-flex align-items-center justify-content-center mx-auto mb-2 fw-bold">3</div>
                    <div class="step-label fs--14px text--body">Confirmation</div>
                </div>
            </div>

            <div class="custom--card border-0">
                <div class="card-body p-4 p-lg-5">
                    <div class="row g-4">
                        <!-- Left Column: Donation Info -->
                        <div class="col-lg-6">
                            <h2 class="fw-bold mb-4 text--heading">Complete Your Donation</h2>
                            
                            <!-- Fundraiser Info -->
                            <div class="custom--card bg--light border-0 mb-4">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3 text--heading">You're donating to:</h5>
                                    <div class="d-flex align-items-start gap-3">
                                        <img src="{{ $fundraiser->featured_image_url }}" 
                                             class="rounded-3" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h6 class="fw-bold mb-1 text--heading">{{ $fundraiser->title }}</h6>
                                            <p class="small text--body mb-2">{{ Str::limit($fundraiser->tagline, 100) }}</p>
                                            <div class="progress mb-2 w-100 bg--light-600" style="height: 6px;">
                                                <div class="progress-bar bg--success rounded" 
                                                     style="width: {{ $fundraiser->progress_percentage }}%"></div>
                                            </div>
                                            <div class="d-flex justify-content-between small">
                                                <span class="text--body">{{ round($fundraiser->progress_percentage, 1) }}% funded</span>
                                                <span class="text--base">{{ $fundraiser->currency }} {{ number_format($fundraiser->raised_amount) }} raised</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Donation Summary -->
                            <div class="custom--card bg--light border-0">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3 text--heading">Donation Summary</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text--body">Donation Amount:</span>
                                        <span class="fw-bold text--heading" id="donationAmountSummary">{{ $fundraiser->currency }} 0.00</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text--body">Payment Fee:</span>
                                        <span class="fw-bold text--heading" id="paymentFeeSummary">{{ $fundraiser->currency }} 0.00</span>
                                    </div>
                                    <hr class="bg--border">
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span class="text--heading">Total to Pay:</span>
                                        <span class="text--base" id="totalAmountSummary">{{ $fundraiser->currency }} 0.00</span>
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
                                    <label class="form-label fw-bold text--heading">Donation Amount ({{ $fundraiser->currency }})</label>
                                    <div class="row g-2 mb-3">
                                        @foreach([25, 50, 100, 250, 500] as $amount)
                                        <div class="col-6 col-md-4">
                                            <button type="button" class="btn btn--outline-base w-100 amount-btn" 
                                                    data-amount="{{ $amount }}">
                                                {{ $amount }}
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="custom-input-box d-flex align-items-center gap-2">
                                        <span class="text--base fw-bold px-2">{{ $fundraiser->currency }}</span>
                                        <input type="number" class="flex-grow-1 border-0 bg-transparent" id="amount" name="amount" 
                                               placeholder="Enter custom amount" min="1" step="0.01" required>
                                        <input type="hidden" id="gateway" name="gateway">
                                        <input type="hidden" id="currency" name="currency">
                                    </div>
                                </div>
                                
                                <!-- Donor Information -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 text--heading">Your Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <input type="text" class="form--control w-100" name="first_name" 
                                                   placeholder="First Name *" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form--control w-100" name="last_name" 
                                                   placeholder="Last Name *" required>
                                        </div>
                                        <div class="col-md-12">
                                            <input type="email" class="form--control w-100" name="email" 
                                                   placeholder="Email Address *" required>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form--control w-100" name="phone" 
                                                   placeholder="Phone Number">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form--control w-100" name="country" 
                                                   placeholder="Country">
                                        </div>
                                        <div class="col-md-12">
                                            <textarea class="form--control w-100" name="message" rows="2" 
                                                      placeholder="Message of support (optional)"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Donation Options -->
                                <div class="mb-4">
                                    <div class="custom--checkbox mb-2">
                                        <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonymous">
                                        <label class="form-check-label text--body" for="anonymous">
                                            Make this an anonymous donation
                                        </label>
                                    </div>
                                    <div class="custom--checkbox mb-2">
                                        <input class="form-check-input" type="checkbox" name="tax_deductible" id="taxDeductible">
                                        <label class="form-check-label text--body" for="taxDeductible">
                                            This donation is tax deductible
                                        </label>
                                    </div>
                                    <div class="custom--checkbox mb-2">
                                        <input class="form-check-input" type="checkbox" name="receive_updates" id="receiveUpdates" checked>
                                        <label class="form-check-label text--body" for="receiveUpdates">
                                            Receive updates about this fundraiser
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Payment Methods -->
                                <div class="mb-4">
                                    <h5 class="fw-bold mb-3 text--heading">Select Payment Method</h5>
                                    <div class="row g-3" id="paymentMethods">
                                        @foreach($gatewayCurrency as $gateway)
                                        <div class="col-12">
                                            <div class="payment-method-card custom--card border-2 cursor-pointer" 
                                                 data-gateway="{{ $gateway->method_code }}" 
                                                 data-currency="{{ $gateway->currency }}">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="custom--radio mb-0 w-100">
                                                        <input class="form-check-input" type="radio" 
                                                               name="gateway_selector" 
                                                               id="gateway_{{ $gateway->id }}"
                                                               data-gateway="{{ $gateway->method_code }}"
                                                               data-currency="{{ $gateway->currency }}">
                                                        <label class="form-check-label d-flex align-items-center w-100" for="gateway_{{ $gateway->id }}">
                                                            <img src="{{ getImage(getFilePath('gateway').'/'.$gateway->method->image) }}" 
                                                                 class="me-3" style="height: 30px;">
                                                            <div>
                                                                <div class="fw-bold text--heading">{{ __($gateway->name) }}</div>
                                                                <small class="text--body">
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
                                    <button type="submit" class="cmn--btn w-100 btn--lg" id="submitBtn" disabled>
                                        <i class="fas fa-lock me-2"></i> Proceed to Payment
                                    </button>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text--body">
                                        <i class="fas fa-shield-alt me-1 text--base"></i> Secure SSL encrypted payment
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
/* Minimal custom CSS using theme variables */
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
    flex: 1;
}

.width-30 {
    width: 30px;
}

.height-30 {
    height: 30px;
}

.flex-1 {
    flex: 1;
}

.payment-method-card {
    border: 2px solid hsl(var(--border));
    cursor: pointer;
    transition: all 0.3s;
}

.payment-method-card:hover,
.payment-method-card.active {
    border-color: hsl(var(--base));
    background-color: hsl(var(--base)/0.05);
}

.payment-method-card .custom--radio input[type=radio]:checked ~ label::before {
    border-color: hsl(var(--base)) !important;
}

.payment-method-card .custom--radio input[type=radio]:checked ~ label::after {
    background-color: hsl(var(--base)) !important;
}

.amount-btn.active {
    background-color: hsl(var(--base));
    color: hsl(var(--white));
    border-color: hsl(var(--base));
}

.custom-input-box {
    border: 1px solid hsl(var(--border));
    border-radius: 5px;
    padding: 0.375rem 0;
    transition: all 0.3s;
}

.custom-input-box:focus-within {
    border-color: hsl(var(--base));
}

.custom-input-box input:focus {
    outline: none;
}

.bg--light-600 {
    background-color: hsl(var(--light-600));
}

.cursor-pointer {
    cursor: pointer;
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
            
            // Trigger radio change event for custom radio styling
            const event = new Event('change', { bubbles: true });
            radio.dispatchEvent(event);
            
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
        const gateway = selectedGateway && gatewayData[selectedGateway] ? gatewayData[selectedGateway] : null;
        
        if (gateway && amount > 0) {
            const charge = parseFloat(gateway.fixed_charge) + (amount * parseFloat(gateway.percent_charge) / 100);
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
    
    // Initialize - check URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const amount = urlParams.get('amount');
    if (amount) {
        amountInput.value = amount;
        updateDonationSummary();
    }
    
    // Auto-fill name and email from URL parameters
    const donorName = urlParams.get('donor_name');
    const donorEmail = urlParams.get('donor_email');
    
    if (donorName) {
        const nameParts = donorName.split(' ');
        const firstNameInput = document.querySelector('input[name="first_name"]');
        const lastNameInput = document.querySelector('input[name="last_name"]');
        if (firstNameInput) firstNameInput.value = nameParts[0] || '';
        if (lastNameInput) lastNameInput.value = nameParts.slice(1).join(' ') || '';
    }
    
    if (donorEmail) {
        const emailInput = document.querySelector('input[name="email"]');
        if (emailInput) emailInput.value = donorEmail;
    }
});
</script>
@endpush