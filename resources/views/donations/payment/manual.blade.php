@extends('layouts.frontend')

@section('title', $pageTitle)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
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
                <div class="step active">
                    <div class="step-number">3</div>
                    <div class="step-label">Bank Details</div>
                </div>
            </div>

            <div class="card shadow-lg border-0">
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-5">
                        <div class="mb-3">
                            <i class="fas fa-university fa-3x text-primary"></i>
                        </div>
                        <h2 class="fw-bold">Bank Transfer Instructions</h2>
                        <p class="text-muted">Please complete your donation using the bank details below</p>
                    </div>
                    
                    <div class="row">
                        <!-- Left Column: Bank Details -->
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white fw-bold">
                                    <i class="fas fa-info-circle me-2"></i> Bank Account Details
                                </div>
                                <div class="card-body">
                                    @php
                                        $gatewayParams = json_decode($method->gateway_parameter, true);
                                    @endphp
                                    
                                    <div class="mb-3">
                                        <div class="text-muted small">Bank Name</div>
                                        <div class="fw-bold">{{ $gatewayParams['bank_name'] ?? 'Tendo Charity Bank' }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="text-muted small">Account Name</div>
                                        <div class="fw-bold">{{ $gatewayParams['account_name'] ?? 'Tendo Charity Foundation' }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="text-muted small">Account Number</div>
                                        <div class="fw-bold">{{ $gatewayParams['account_number'] ?? '1234567890' }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="text-muted small">SWIFT/BIC Code</div>
                                        <div class="fw-bold">{{ $gatewayParams['swift_code'] ?? 'ABCDEFGH' }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="text-muted small">IBAN</div>
                                        <div class="fw-bold">{{ $gatewayParams['iban'] ?? 'AB12 3456 7890 1234 5678 90' }}</div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="text-muted small">Branch Address</div>
                                        <div class="fw-bold">{{ $gatewayParams['branch_address'] ?? '123 Charity Street, Hope City, HC 12345' }}</div>
                                    </div>
                                    
                                    <div class="alert alert-warning mt-4">
                                        <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                                        <ul class="mb-0 small">
                                            <li>Include your reference number in the transfer description</li>
                                            <li>Bank transfers may take 2-5 business days to process</li>
                                            <li>International transfers may incur additional fees</li>
                                            <li>You must upload proof of payment below</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column: Payment Form -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header bg-light fw-bold">
                                    <i class="fas fa-file-invoice me-2"></i> Upload Payment Proof
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('donation.payment.manual.update') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <!-- Donation Summary -->
                                        <div class="alert alert-info mb-4">
                                            <div class="d-flex justify-content-between">
                                                <span>Donation Reference:</span>
                                                <span class="fw-bold">{{ $donation->payment_reference }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mt-2">
                                                <span>Amount to Transfer:</span>
                                                <span class="fw-bold">{{ $donation->currency }} {{ number_format($donation->amount, 2) }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Transaction Details -->
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Bank Transaction ID *</label>
                                            <input type="text" class="form-control" name="transaction_id" 
                                                   placeholder="Enter the transaction ID from your bank" required>
                                            <div class="form-text">This helps us identify your payment</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Payment Date *</label>
                                            <input type="date" class="form-control" name="payment_date" 
                                                   max="{{ date('Y-m-d') }}" required>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="form-label fw-bold">Proof of Payment *</label>
                                            <div class="file-upload-area">
                                                <input type="file" class="form-control" name="payment_proof" 
                                                       accept="image/*,.pdf" required id="paymentProof">
                                                <div class="form-text">Upload screenshot or PDF of bank transfer confirmation</div>
                                                <div class="preview-area mt-3 d-none" id="previewArea">
                                                    <img id="previewImage" class="img-fluid rounded" style="max-height: 200px;">
                                                    <div id="fileName" class="mt-2 text-muted"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Instructions -->
                                        <div class="alert alert-light border mb-4">
                                            <h6 class="fw-bold mb-2">To ensure quick processing:</h6>
                                            <ol class="mb-0 small">
                                                <li>Complete the bank transfer using details on the left</li>
                                                <li>Take a screenshot or download receipt from your bank</li>
                                                <li>Fill in your transaction details above</li>
                                                <li>Upload the payment proof</li>
                                                <li>Click Submit for Verification</li>
                                            </ol>
                                        </div>
                                        
                                        <!-- Terms -->
                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" id="confirmTransfer" required>
                                            <label class="form-check-label" for="confirmTransfer">
                                                I confirm that I have completed the bank transfer with the exact amount and reference number
                                            </label>
                                        </div>
                                        
                                        <!-- Submit Button -->
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-paper-plane me-2"></i> Submit for Verification
                                            </button>
                                        </div>
                                        
                                        <!-- Processing Time -->
                                        <div class="text-center mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i> Verification usually takes 1-3 business days
                                            </small>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Next Steps -->
                            <div class="card mt-4 border-0 bg-light">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-arrow-right me-2"></i>What Happens Next?</h6>
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <div class="fw-bold">Submission Received</div>
                                                <small class="text-muted">We'll email you a confirmation immediately</small>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <div class="fw-bold">Payment Verification</div>
                                                <small class="text-muted">Our team verifies your bank transfer (1-3 days)</small>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-marker"></div>
                                            <div class="timeline-content">
                                                <div class="fw-bold">Donation Confirmed</div>
                                                <small class="text-muted">You'll receive a donation receipt via email</small>
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
</div>
@endsection

@push('style')
<style>
.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s;
}
.file-upload-area:hover {
    border-color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.05);
}
.timeline {
    position: relative;
    padding-left: 20px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -20px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #0d6efd;
    border: 2px solid white;
}
.timeline-content {
    padding-left: 10px;
}
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentProof = document.getElementById('paymentProof');
    const previewArea = document.getElementById('previewArea');
    const previewImage = document.getElementById('previewImage');
    const fileName = document.getElementById('fileName');
    
    paymentProof.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Show file name
            fileName.textContent = file.name;
            
            // Show preview for images
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
    
    // Set max date to today
    document.querySelector('input[name="payment_date"]').max = new Date().toISOString().split('T')[0];
});
</script>
@endpush