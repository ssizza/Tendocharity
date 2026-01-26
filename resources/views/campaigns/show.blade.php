@extends('layouts.frontend')

@section('title', $campaign->title . ' - Charity Organization')

@push('styles')
<style>
    .campaign-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ $campaign->image_url }}');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 80px 0;
        margin-bottom: 40px;
    }
    .progress-lg {
        height: 12px;
    }
    .impact-card {
        transition: transform 0.3s;
    }
    .impact-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="campaign-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                @if($campaign->is_urgent)
                <span class="badge bg-danger mb-3 px-3 py-2">
                    <i class="fas fa-exclamation-triangle me-1"></i> URGENT APPEAL
                </span>
                @endif
                
                <h1 class="display-4 fw-bold mb-3">{{ $campaign->title }}</h1>
                <p class="lead mb-4">{{ $campaign->tagline }}</p>
                
                <div class="d-flex justify-content-center align-items-center mb-4">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <span>{{ $campaign->location_country }}{{ $campaign->location_region ? ', ' . $campaign->location_region : '' }}</span>
                </div>
                
                <!-- Quick Stats -->
                <div class="row g-4 mb-4">
                    <div class="col-4">
                        <div class="text-center">
                            <div class="display-5 fw-bold">{{ $campaign->funding_percentage }}%</div>
                            <small>Funded</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="display-5 fw-bold">{{ $campaign->donors_count }}</div>
                            <small>Supporters</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <div class="display-5 fw-bold">{{ $campaign->beneficiaries_count }}</div>
                            <small>Beneficiaries</small>
                        </div>
                    </div>
                </div>
                
                <a href="#donate" class="btn btn-danger btn-lg px-5">
                    <i class="fas fa-hand-holding-heart me-2"></i> Donate Now
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Progress Bar -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-0">Campaign Progress</h5>
                            <small class="text-muted">Help us reach our goal</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-primary">{{ $campaign->formatted_raised }} raised</div>
                            <small class="text-muted">of {{ $campaign->formatted_goal }} goal</small>
                        </div>
                    </div>
                    
                    <div class="progress progress-lg mb-3">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $campaign->funding_percentage }}%">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i> {{ $campaign->donors_count }} people donated
                        </small>
                        @if($campaign->days_remaining !== null)
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i> {{ $campaign->days_remaining }} days remaining
                        </small>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Campaign Description -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">About This Campaign</h3>
                    <div class="content mb-4">
                        {!! $campaign->description !!}
                    </div>
                    
                    @if($campaign->problem_statement)
                    <div class="alert alert-warning border-0 mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-exclamation-circle me-2"></i> The Problem
                        </h5>
                        <p class="mb-0">{{ $campaign->problem_statement }}</p>
                    </div>
                    @endif
                    
                    @if($campaign->solution_statement)
                    <div class="alert alert-success border-0">
                        <h5 class="alert-heading">
                            <i class="fas fa-lightbulb me-2"></i> Our Solution
                        </h5>
                        <p class="mb-0">{{ $campaign->solution_statement }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Milestones -->
            @if($campaign->milestones->count() > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">
                        <i class="fas fa-flag-checkered me-2"></i> Campaign Milestones
                    </h3>
                    <div class="timeline">
                        @foreach($campaign->milestones as $milestone)
                        <div class="timeline-item mb-4">
                            <div class="d-flex">
                                <div class="timeline-marker me-3">
                                    <div class="rounded-circle bg-{{ $milestone->status == 'completed' ? 'success' : ($milestone->status == 'in_progress' ? 'warning' : 'secondary') }}"
                                         style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-{{ $milestone->status == 'completed' ? 'check' : 'flag' }} text-white"></i>
                                    </div>
                                </div>
                                <div class="timeline-content flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="fw-bold mb-0">{{ $milestone->title }}</h5>
                                        <span class="badge bg-{{ $milestone->status == 'completed' ? 'success' : ($milestone->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-muted mb-2">{{ $milestone->description }}</p>
                                    @if($milestone->target_amount)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            Target: {{ $campaign->currency }} {{ number_format($milestone->target_amount, 2) }}
                                        </small>
                                        @if($milestone->completion_date)
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i> {{ $milestone->completion_date->format('M d, Y') }}
                                        </small>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Campaign Updates -->
            @if($campaign->updates->count() > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">
                        <i class="fas fa-newspaper me-2"></i> Campaign Updates
                    </h3>
                    @foreach($campaign->updates as $update)
                    <div class="update-item mb-4 pb-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">{{ $update->title }}</h5>
                            <span class="badge bg-info">{{ ucfirst($update->type) }}</span>
                        </div>
                        <p class="text-muted mb-3">{{ $update->content }}</p>
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i> {{ $update->created_at->diffForHumans() }}
                        </small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- FAQs -->
            @if($campaign->faqs->count() > 0)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">
                        <i class="fas fa-question-circle me-2"></i> Frequently Asked Questions
                    </h3>
                    <div class="accordion" id="faqAccordion">
                        @foreach($campaign->faqs as $index => $faq)
                        <div class="accordion-item border-0 mb-2">
                            <h2 class="accordion-header" id="faqHeading{{ $index }}">
                                <button class="accordion-button collapsed bg-light shadow-none" type="button" 
                                        data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}">
                                    {{ $faq->question }}
                                </button>
                            </h2>
                            <div id="faqCollapse{{ $index }}" class="accordion-collapse collapse" 
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Donations -->
            @if($campaign->donations->count() > 0)
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="fw-bold mb-4">
                        <i class="fas fa-heart me-2"></i> Recent Supporters
                    </h3>
                    <div class="row g-3">
                        @foreach($campaign->donations as $donation)
                        <div class="col-6 col-md-4">
                            <div class="p-3 bg-light rounded text-center">
                                <div class="mb-2">
                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                </div>
                                <h6 class="fw-bold mb-1">
                                    @if($donation->is_anonymous)
                                    Anonymous
                                    @else
                                    {{ $donation->donor_name }}
                                    @endif
                                </h6>
                                <small class="text-muted">{{ $donation->formatted_amount }}</small>
                                @if($donation->message)
                                <p class="small mt-2 mb-0 text-muted">
                                    "{{ Str::limit($donation->message, 50) }}"
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Donation Card -->
            <div class="card shadow-lg border-0 sticky-top" style="top: 20px;">
                <div class="card-body">
                    <h4 class="fw-bold text-center mb-4" id="donate">Support This Campaign</h4>
                    
                    <form action="{{ route('donations.store', $campaign) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Donation Amount ({{ $campaign->currency }})</label>
                            <div class="row g-2 mb-3">
                                <div class="col-4">
                                    <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="25">25</button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="50">50</button>
                                </div>
                                <div class="col-4">
                                    <button type="button" class="btn btn-outline-primary w-100 amount-btn" data-amount="100">100</button>
                                </div>
                            </div>
                            <input type="number" class="form-control" id="amount" name="amount" 
                                   placeholder="Enter custom amount" min="1" step="0.01" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Your Name</label>
                            <input type="text" class="form-control" name="donor_name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" class="form-control" name="donor_email" required>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_anonymous" id="anonymous">
                                <label class="form-check-label" for="anonymous">
                                    Donate anonymously
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tax_deductible" id="taxDeductible">
                                <label class="form-check-label" for="taxDeductible">
                                    This donation is tax deductible
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select class="form-select" name="payment_method" required>
                                <option value="credit_card">Credit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="digital_wallet">Digital Wallet</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Message (Optional)</label>
                            <textarea class="form-control" name="message" rows="2" 
                                      placeholder="Add a message of support..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-danger w-100 btn-lg">
                            <i class="fas fa-heart me-2"></i> Donate Now
                        </button>
                        
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i> Secure payment processed
                            </small>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Service Info -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Part of: {{ $campaign->service->title }}</h5>
                    <p class="small text-muted mb-3">{{ Str::limit($campaign->service->mission, 100) }}</p>
                    <a href="{{ route('services.show', $campaign->service->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-info-circle me-1"></i> Learn About This Service
                    </a>
                </div>
            </div>

            <!-- Impact Metrics -->
            @if($campaign->impact_metrics)
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Impact Metrics</h5>
                    @foreach(json_decode($campaign->impact_metrics, true) as $metric => $value)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">{{ $metric }}</span>
                        <span class="fw-bold">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Share Campaign -->
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Share This Campaign</h5>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Campaigns -->
    @if($relatedCampaigns->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="fw-bold mb-4">Related Campaigns</h3>
            <div class="row g-4">
                @foreach($relatedCampaigns as $related)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ $related->image_url }}" class="card-img-top" alt="{{ $related->title }}" 
                             style="height: 150px; object-fit: cover;">
                        <div class="card-body">
                            <h6 class="card-title fw-bold">{{ Str::limit($related->title, 50) }}</h6>
                            <div class="progress mb-2" style="height: 4px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ $related->funding_percentage }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">{{ $related->funding_percentage }}%</span>
                                <span class="text-primary">{{ $related->formatted_raised }} raised</span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top-0">
                            <a href="{{ route('campaigns.show', $related->slug) }}" class="btn btn-sm btn-outline-primary w-100">
                                View Campaign
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    @auth
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Campaign Management</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('campaigns.edit', $campaign) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit Campaign
                        </a>
                        <button class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addUpdateModal">
                            <i class="fas fa-plus me-1"></i> Add Update
                        </button>
                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#addMilestoneModal">
                            <i class="fas fa-flag me-1"></i> Add Milestone
                        </button>
                        <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                            <i class="fas fa-question me-1"></i> Add FAQ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endauth
</div>

<!-- Modals for Management -->
@auth
<!-- Add Update Modal -->
<div class="modal fade" id="addUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Campaign Update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('campaigns.add-update', $campaign) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea class="form-control" name="content" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Update Type</label>
                        <select class="form-select" name="type" required>
                            <option value="progress">Progress Update</option>
                            <option value="milestone">Milestone Reached</option>
                            <option value="general">General Update</option>
                            <option value="emergency">Emergency Update</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_public" checked>
                        <label class="form-check-label">Make this update public</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Milestone Modal -->
<div class="modal fade" id="addMilestoneModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Milestone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('campaigns.add-milestone', $campaign) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Milestone Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Target Amount</label>
                            <input type="number" class="form-control" name="target_amount" min="0" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Completion Date</label>
                            <input type="date" class="form-control" name="completion_date">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="delayed">Delayed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Milestone</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add FAQ Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('campaigns.add-faq', $campaign) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <input type="text" class="form-control" name="question" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Answer</label>
                        <textarea class="form-control" name="answer" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Amount buttons
        document.querySelectorAll('.amount-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('amount').value = this.dataset.amount;
            });
        });
        
        // Share buttons
        document.querySelectorAll('.share-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const url = window.location.href;
                const platform = this.dataset.platform;
                
                let shareUrl = '';
                switch(platform) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent("{{ $campaign->title }}")}`;
                        break;
                    case 'whatsapp':
                        shareUrl = `https://wa.me/?text=${encodeURIComponent("Check out this campaign: " + url)}`;
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
@endsection