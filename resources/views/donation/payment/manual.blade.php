@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Progress Steps -->
            <div class="steps mb-5 d-flex justify-content-between">
                <div class="step text-center" style="flex: 1;">
                    <div class="step-number mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center bg--base text--white" style="width: 40px; height: 40px;">1</div>
                    <div class="step-label text--base fw-bold">Donor Info</div>
                </div>
                <div class="step text-center" style="flex: 1;">
                    <div class="step-number mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center bg--base text--white" style="width: 40px; height: 40px;">2</div>
                    <div class="step-label text--base fw-bold">Payment Method</div>
                </div>
                <div class="step text-center" style="flex: 1;">
                    <div class="step-number mx-auto mb-2 rounded-circle d-flex align-items-center justify-content-center bg--base text--white" style="width: 40px; height: 40px;">3</div>
                    <div class="step-label text--base fw-bold">Payment Details</div>
                </div>
            </div>

            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-5">
                        <div class="mb-3">
                            <i class="fas fa-university fa-3x text--base"></i>
                        </div>
                        <h2 class="fw-bold text--heading">{{ $gatewayCurrency->method->name }}</h2>
                        <p class="text--body">{{ $gatewayCurrency->method->description ?? 'Please complete your donation using the details below' }}</p>
                    </div>
                    
                    <div class="row g-4">
                        <!-- Left Column: Bank/Instructions Details -->
                        <div class="col-lg-5">
                            <div class="card border--base h-100">
                                <div class="card-header bg--base text--white fw-bold">
                                    <i class="fas fa-info-circle me-2"></i> Payment Instructions
                                </div>
                                <div class="card-body">
                                    @if(!empty($bankDetails))
                                        @foreach($bankDetails as $key => $value)
                                            @if(!empty($value))
                                            <div class="mb-3">
                                                <div class="text--body small text-uppercase mb-1">{{ str_replace('_', ' ', $key) }}</div>
                                                <div class="fw-bold text--heading">{{ $value }}</div>
                                            </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="alert bg--warning text--white border-0">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            No payment instructions available. Please contact support.
                                        </div>
                                    @endif
                                    
                                    <div class="alert mt-4" style="background-color: hsl(var(--info)/0.1); border-left: 3px solid hsl(var(--info));">
                                        <h6 class="fw-bold text--info"><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                                        <ul class="mb-0 small text--body">
                                            <li>Use your donation reference in the payment description</li>
                                            <li>Bank transfers may take 2-5 business days to process</li>
                                            <li>Upload proof of payment below for faster verification</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Payment Form -->
                        <div class="col-lg-7">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg--light fw-bold text--heading">
                                    <i class="fas fa-file-invoice me-2 text--base"></i> Submit Payment Details
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('donation.payment.manual.submit') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <!-- Donation Summary -->
                                        <div class="alert mb-4 border-0" style="background-color: hsl(var(--info)/0.1); border-left: 3px solid hsl(var(--info));">
                                            <div class="d-flex justify-content-between text--body">
                                                <span>Donation Reference:</span>
                                                <span class="fw-bold text--base">{{ $donation->payment_reference }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2 text--body">
                                                <span>Amount to Transfer:</span>
                                                <span class="fw-bold text--heading">{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</span>
                                            </div>
                                            @php
                                                $metadata = json_decode($donation->metadata, true);
                                            @endphp
                                            @if(isset($metadata['final_amount']) && $metadata['final_amount'] != $donation->amount)
                                            <div class="d-flex justify-content-between mt-2 text--warning">
                                                <span>Total including fees:</span>
                                                <span class="fw-bold">{{ $donation->currency }} {{ number_format($metadata['final_amount'], 2) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Dynamic Form Fields from Database -->
                                        @if($form && $form->form_data)
                                            <div class="mb-4">
                                                <h5 class="fw-bold text--heading mb-3">Additional Information</h5>
                                                <x-viser-form identifier="id" identifierValue="{{ $form->id }}" />
                                            </div>
                                        @endif
                                        
                                        <!-- Payment Proof Upload (Always Required) -->
                                        <div class="mb-4">
                                            <label class="form-label fw-bold text--heading">
                                                Proof of Payment <span class="text--danger">*</span>
                                            </label>
                                            <div class="file-upload-area p-4 rounded" style="border: 2px dashed hsl(var(--border)); transition: all 0.3s;">
                                                <input type="file" class="form-control border-0 p-0" name="payment_proof" 
                                                       accept=".jpg,.jpeg,.png,.pdf" required id="paymentProof" style="background: transparent;">
                                                <div class="form-text text--body mt-2">Upload screenshot, photo, or PDF of bank transfer confirmation</div>
                                                <div class="preview-area mt-3 d-none" id="previewArea">
                                                    <img id="previewImage" class="img-fluid rounded" style="max-height: 200px;">
                                                    <div id="fileName" class="mt-2 text--body"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Terms -->
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="confirmTransfer" required style="accent-color: hsl(var(--base));">
                                            <label class="form-check-label text--body" for="confirmTransfer">
                                                I confirm that I have completed the payment with the correct amount and reference number
                                            </label>
                                        </div>
                                        
                                        <!-- Submit Button -->
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn--base btn--lg">
                                                <i class="fas fa-paper-plane me-2"></i> Submit for Verification
                                            </button>
                                        </div>
                                        
                                        <!-- Processing Time -->
                                        <div class="text-center mt-3">
                                            <small class="text--body">
                                                <i class="fas fa-clock me-1 text--base"></i> Verification usually takes 1-3 business days
                                            </small>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Next Steps -->
                            <div class="card mt-4 border-0 bg--light">
                                <div class="card-body">
                                    <h6 class="fw-bold text--heading mb-3"><i class="fas fa-arrow-right me-2 text--base"></i>What Happens Next?</h6>
                                    <div class="timeline" style="position: relative; padding-left: 20px;">
                                        <div class="timeline-item mb-3 position-relative">
                                            <div class="timeline-marker position-absolute rounded-circle bg--base" style="left: -20px; top: 5px; width: 10px; height: 10px; border: 2px solid hsl(var(--white));"></div>
                                            <div class="timeline-content ps-3">
                                                <div class="fw-bold text--heading">Submission Received</div>
                                                <small class="text--body">We'll email you a confirmation immediately</small>
                                            </div>
                                        </div>
                                        <div class="timeline-item mb-3 position-relative">
                                            <div class="timeline-marker position-absolute rounded-circle bg--base" style="left: -20px; top: 5px; width: 10px; height: 10px; border: 2px solid hsl(var(--white));"></div>
                                            <div class="timeline-content ps-3">
                                                <div class="fw-bold text--heading">Payment Verification</div>
                                                <small class="text--body">Our team verifies your payment (1-3 days)</small>
                                            </div>
                                        </div>
                                        <div class="timeline-item mb-3 position-relative">
                                            <div class="timeline-marker position-absolute rounded-circle bg--base" style="left: -20px; top: 5px; width: 10px; height: 10px; border: 2px solid hsl(var(--white));"></div>
                                            <div class="timeline-content ps-3">
                                                <div class="fw-bold text--heading">Donation Confirmed</div>
                                                <small class="text--body">You'll receive a donation receipt via email</small>
                                            </div>
                                        </div>
                                        <div style="position: absolute; left: 15px; top: 0; bottom: 0; width: 2px; background-color: hsl(var(--border));"></div>
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

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentProof = document.getElementById('paymentProof');
    const previewArea = document.getElementById('previewArea');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    
    // File upload area hover effect
    const uploadArea = document.querySelector('.file-upload-area');
    if (uploadArea) {
        uploadArea.addEventListener('mouseenter', function() {
            this.style.borderColor = 'hsl(var(--base))';
            this.style.backgroundColor = 'hsl(var(--base)/0.05)';
        });
        uploadArea.addEventListener('mouseleave', function() {
            this.style.borderColor = 'hsl(var(--border))';
            this.style.backgroundColor = 'transparent';
        });
    }
    
    paymentProof.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            fileName.textContent = file.name;
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewArea.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            } else {
                previewArea.classList.add('d-none');
            }
        } else {
            previewArea.classList.add('d-none');
        }
    });
});
</script>
@endpush