@extends('layouts.frontend')

@section('title', $fundraiser->title)

@section('content')
<div class="container py-5">

    {{-- ================= TOP HERO CARD ================= --}}
    <div class="custom--card fundraiser-hero-card mb-5 overflow-hidden">
        <div class="row g-0 align-items-stretch">

            {{-- LEFT CONTENT --}}
            <div class="col-lg-7">
                <div class="p-4 p-lg-5 h-100 d-flex flex-column justify-content-between">

                    <div>
                        {{-- Badges --}}
                        <div class="mb-3">
                            @if($fundraiser->is_featured)
                                <span class="badge badge--warning text--dark me-2">FEATURED</span>
                            @endif

                            @if($fundraiser->urgency_level === 'critical')
                                <span class="badge badge--danger">CRITICAL NEED</span>
                            @elseif($fundraiser->urgency_level === 'urgent')
                                <span class="badge badge--warning text--dark">URGENT</span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h1 class="fw-bold mb-3 text--heading">
                            {{ $fundraiser->title }}
                        </h1>

                        {{-- Tagline --}}
                        @if($fundraiser->tagline)
                            <p class="text--body lead mb-4">
                                {{ $fundraiser->tagline }}
                            </p>
                        @endif

                        {{-- Meta --}}
                        <div class="d-flex flex-wrap gap-4 text--body small mb-4">
                            <div><i class="fas fa-map-marker-alt text--base me-2"></i>{{ $fundraiser->location ?? 'Global' }}</div>
                            <div><i class="fas fa-user text--base me-2"></i>{{ $fundraiser->project_leader ?? 'Organizer' }}</div>
                            <div><i class="fas fa-users text--base me-2"></i>{{ $fundraiser->beneficiaries_count }} beneficiaries</div>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div>
                        <div class="progress mb-3" style="height:8px; background-color: hsl(var(--light-600));">
                            <div class="progress-bar bg--base"
                                 style="width: {{ $fundraiser->progress_percentage }}%; background: linear-gradient(135deg, hsl(var(--base-400)), hsl(var(--base-600)));">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="h5 fw-bold mb-0 text--heading">
                                    {{ $fundraiser->currency }}
                                    {{ number_format($fundraiser->raised_amount) }}
                                </div>
                                <small class="text--body">
                                    raised of {{ $fundraiser->currency }}
                                    {{ number_format($fundraiser->target_amount) }}
                                </small>
                            </div>

                            <div class="text-end">
                                <div class="fw-semibold text--base">
                                    {{ round($fundraiser->progress_percentage,1) }}%
                                </div>
                                <small class="text--body">
                                    {{ $donationStats['total_donations'] }} donors
                                </small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- RIGHT IMAGE --}}
            <div class="col-lg-5">
                <div class="fundraiser-hero-image h-100">
                    <img src="{{ $fundraiser->featured_image_url }}"
                         alt="{{ $fundraiser->title }}"
                         class="w-100 h-100 object-fit-cover">
                </div>
            </div>

        </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="row g-5">

        {{-- LEFT CONTENT --}}
        <div class="col-lg-8">

            {{-- Tabs --}}
            <ul class="nav cmn--tabs mb-4 gap-2 border-0">
                <li class="nav-item">
                    <button class="nav-link active bg--base text--white rounded" data-bs-toggle="tab" data-bs-target="#story">
                        Story
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text--base" data-bs-toggle="tab" data-bs-target="#problem">
                        The Problem
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text--base" data-bs-toggle="tab" data-bs-target="#solution">
                        The Solution
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="story">
                    <div class="custom--card p-4">
                        <div class="card-body">
                            {!! $fundraiser->description !!}
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="problem">
                    <div class="custom--card p-4">
                        <div class="card-body">
                            {!! $fundraiser->problem_statement ?? '<p class="text--body">No problem statement provided.</p>' !!}
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="solution">
                    <div class="custom--card p-4">
                        <div class="card-body">
                            {!! $fundraiser->solution_statement ?? '<p class="text--body">No solution statement provided.</p>' !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="col-lg-4">

            {{-- Donation Card --}}
            <div class="custom--card donation-card p-4" style="position: sticky; top: 100px;">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 text--heading">Donate to this cause</h5>

                    <form action="{{ route('donation.initiate', $fundraiser->slug) }}" method="GET">
                        @csrf

                        <div class="input-group mb-3">
                            <span class="input-group-text bg--light text--body border-0">{{ $fundraiser->currency }}</span>
                            <input type="number" name="amount" class="form-control form--control" value="50" min="1" required>
                        </div>

                        <div class="d-flex gap-2 mb-3 flex-wrap">
                            @foreach([10,25,50,100] as $amt)
                                <button type="button"
                                        class="btn btn--outline-base quick-amount rounded"
                                        data-amount="{{ $amt }}">
                                    {{ $fundraiser->currency }} {{ $amt }}
                                </button>
                            @endforeach
                        </div>

                        <input type="text" name="donor_name" class="form-control form--control mb-3" placeholder="Your name" required>
                        <input type="email" name="donor_email" class="form-control form--control mb-3" placeholder="Email address" required>

                        <button class="btn btn--base w-100">
                            <i class="fas fa-heart me-2"></i> Donate Now
                        </button>

                        <p class="text--body small text-center mt-3">
                            <i class="fas fa-lock me-1"></i> Secure payment
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ================= STYLES (minimal, only structural) ================= --}}
<style>
.fundraiser-hero-card {
    border-radius: 20px;
}

.fundraiser-hero-image {
    min-height: 300px;
}

.fundraiser-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-top-right-radius: 20px;
    border-bottom-right-radius: 20px;
}

@media (max-width: 991px) {
    .fundraiser-hero-image img {
        border-top-right-radius: 0;
        border-bottom-left-radius: 20px;
    }
}

.object-fit-cover {
    object-fit: cover;
}

/* Quick amount button active state */
.quick-amount.active {
    background: hsl(var(--base)) !important;
    color: hsl(var(--white)) !important;
    border-color: hsl(var(--base)) !important;
}

/* Tab styling to match theme */
.cmn--tabs .nav-link {
    padding: 8px 20px;
    border-radius: 5px;
    transition: all 0.3s;
}

.cmn--tabs .nav-link:not(.active):hover {
    background-color: hsl(var(--base)/0.1);
}

/* Progress bar styling */
.progress {
    background-color: hsl(var(--light-600));
    border-radius: 10px;
}
</style>

{{-- ================= SCRIPT ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.querySelector('[name="amount"]');
    const quickButtons = document.querySelectorAll('.quick-amount');

    quickButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update amount input
            amountInput.value = this.dataset.amount;

            // Remove active class from all buttons
            quickButtons.forEach(b => {
                b.classList.remove('active');
                b.style.background = '';
                b.style.color = '';
            });

            // Add active class to clicked button
            this.classList.add('active');
        });
    });
});
</script>
@endsection