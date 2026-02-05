@extends('layouts.frontend')

@section('title', $fundraiser->title)

@section('content')
<div class="container py-5">

    {{-- ================= TOP HERO CARD ================= --}}
    <div class="card fundraiser-hero-card mb-5">
        <div class="row g-0 align-items-stretch">

            {{-- LEFT CONTENT --}}
            <div class="col-lg-7">
                <div class="p-4 p-lg-5 h-100 d-flex flex-column justify-content-between">

                    <div>
                        {{-- Badges --}}
                        <div class="mb-3">
                            @if($fundraiser->is_featured)
                                <span class="badge bg-warning text-dark me-2">FEATURED</span>
                            @endif

                            @if($fundraiser->urgency_level === 'critical')
                                <span class="badge bg-danger">CRITICAL NEED</span>
                            @elseif($fundraiser->urgency_level === 'urgent')
                                <span class="badge bg-warning text-dark">URGENT</span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h1 class="fw-bold mb-3">
                            {{ $fundraiser->title }}
                        </h1>

                        {{-- Tagline --}}
                        @if($fundraiser->tagline)
                            <p class="text-muted lead mb-4">
                                {{ $fundraiser->tagline }}
                            </p>
                        @endif

                        {{-- Meta --}}
                        <div class="d-flex flex-wrap gap-4 text-muted small mb-4">
                            <div><i class="fas fa-map-marker-alt me-2"></i>{{ $fundraiser->location ?? 'Global' }}</div>
                            <div><i class="fas fa-user me-2"></i>{{ $fundraiser->project_leader ?? 'Organizer' }}</div>
                            <div><i class="fas fa-users me-2"></i>{{ $fundraiser->beneficiaries_count }} beneficiaries</div>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div>
                        <div class="progress mb-3" style="height:8px;">
                            <div class="progress-bar"
                                 style="width: {{ $fundraiser->progress_percentage }}%">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="h5 fw-bold mb-0">
                                    {{ $fundraiser->currency }}
                                    {{ number_format($fundraiser->raised_amount) }}
                                </div>
                                <small class="text-muted">
                                    raised of {{ $fundraiser->currency }}
                                    {{ number_format($fundraiser->target_amount) }}
                                </small>
                            </div>

                            <div class="text-end">
                                <div class="fw-semibold">
                                    {{ round($fundraiser->progress_percentage,1) }}%
                                </div>
                                <small class="text-muted">
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
                         alt="{{ $fundraiser->title }}">
                </div>
            </div>

        </div>
    </div>

    {{-- ================= MAIN CONTENT ================= --}}
    <div class="row g-5">

        {{-- LEFT CONTENT --}}
        <div class="col-lg-8">

            {{-- Tabs --}}
            <ul class="nav nav-pills mb-4 gap-2">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#story">
                        Story
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#problem">
                        The Problem
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#solution">
                        The Solution
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="story">
                    <div class="card p-4">
                        {!! $fundraiser->description !!}
                    </div>
                </div>

                <div class="tab-pane fade" id="problem">
                    <div class="card p-4">
                        {!! $fundraiser->problem_statement ?? '<p>No problem statement provided.</p>' !!}
                    </div>
                </div>

                <div class="tab-pane fade" id="solution">
                    <div class="card p-4">
                        {!! $fundraiser->solution_statement ?? '<p>No solution statement provided.</p>' !!}
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="col-lg-4">

            {{-- Donation Card --}}
            <div class="card donation-card p-4">
                <h5 class="fw-bold mb-3">Donate to this cause</h5>

                <form action="{{ route('donation.initiate', $fundraiser->slug) }}" method="GET">
                    @csrf

                    <div class="input-group mb-3">
                        <span class="input-group-text">{{ $fundraiser->currency }}</span>
                        <input type="number" name="amount" class="form-control" value="50" min="1" required>
                    </div>

                    <div class="d-flex gap-2 mb-3 flex-wrap">
                        @foreach([10,25,50,100] as $amt)
                            <button type="button"
                                    class="btn btn-outline-primary quick-amount"
                                    data-amount="{{ $amt }}">
                                {{ $fundraiser->currency }} {{ $amt }}
                            </button>
                        @endforeach
                    </div>

                    <input type="text" name="donor_name" class="form-control mb-3" placeholder="Your name" required>
                    <input type="email" name="donor_email" class="form-control mb-3" placeholder="Email address" required>

                    <button class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-heart me-2"></i> Donate Now
                    </button>

                    <p class="text-muted small text-center mt-3">
                        <i class="fas fa-lock me-1"></i> Secure payment
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- ================= STYLES ================= --}}
<style>
.fundraiser-hero-card {
    border-radius: 20px;
    overflow: hidden;
}

.fundraiser-hero-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.progress-bar {
    background: linear-gradient(135deg,#667eea,#764ba2);
}

.card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 12px 30px rgba(0,0,0,.08);
}

.donation-card {
    position: sticky;
    top: 100px;
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg,#667eea,#764ba2);
}

.quick-amount.active {
    background: #667eea;
    color: #fff;
}
</style>

{{-- ================= SCRIPT ================= --}}
<script>
document.querySelectorAll('.quick-amount').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelector('[name="amount"]').value = btn.dataset.amount;
        document.querySelectorAll('.quick-amount').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    });
});
</script>
@endsection
