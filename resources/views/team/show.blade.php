@extends('layouts.frontend')

@section('content')
{{-- Breadcrumb --}}

<div class="team-member-page pt-120 pb-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- Member Profile Card --}}
                <div class="member-profile-card custom--card overflow-hidden mb-60">
                    <div class="row g-0">
                        {{-- Member Image --}}
                        @if($member->image)
                        <div class="col-lg-5">
                            <div class="member-image h-100">
                                <img src="{{ $member->image_url }}" 
                                     alt="{{ $member->name }}" 
                                     class="img-fluid w-100 h-100 object-fit-cover">
                            </div>
                        </div>
                        @endif
                        
                        {{-- Member Info --}}
                        <div class="{{ $member->image ? 'col-lg-7' : 'col-12' }}">
                            <div class="member-info p-40">
                                {{-- Category Badge --}}
                                @if($member->category)
                                <div class="mb-3">
                                    <span class="badge badge--base px-4 py-2 rounded-pill">
                                        <i class="fas fa-tag me-2"></i>
                                        {{ $member->category->name }}
                                    </span>
                                </div>
                                @endif
                                
                                {{-- Name --}}
                                <h1 class="member-name text--heading fw-bold mb-3">
                                    {{ $member->name }}
                                </h1>
                                
                                {{-- Position --}}
                                @if($member->position)
                                <h2 class="member-position text--base fw-semibold mb-4">
                                    {{ $member->position }}
                                </h2>
                                @endif
                                
                                {{-- Bio --}}
                                @if($member->bio)
                                <div class="member-bio mb-4">
                                    <h3 class="h5 fw-semibold text--heading mb-3">About</h3>
                                    <div class="bio-content text--body">
                                        {!! $member->bio !!}
                                    </div>
                                </div>
                                @endif
                                
                                {{-- Contact Info --}}
                                <div class="member-contact border-top pt-4">
                                    <h3 class="h5 fw-semibold text--heading mb-3">Contact Information</h3>
                                    
                                    @if($member->email)
                                    <div class="contact-item d-flex align-items-center mb-3">
                                        <div class="contact-icon me-3">
                                            <i class="fas fa-envelope text--base"></i>
                                        </div>
                                        <div>
                                            <small class="text--muted d-block">Email</small>
                                            <a href="mailto:{{ $member->email }}" 
                                               class="text-decoration-none text--base fw-medium">
                                                {{ $member->email }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    {{-- Social Media --}}
                                    @if(count($member->social_media_links) > 0)
                                    <div class="contact-item">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="contact-icon me-3">
                                                <i class="fas fa-share-alt text--base"></i>
                                            </div>
                                            <small class="text--muted">Social Media</small>
                                        </div>
                                        <div class="social-links d-flex gap-2 ms-4">
                                            @foreach($member->social_media_links as $platform => $link)
                                            <a href="{{ $link['url'] }}" 
                                               target="_blank" 
                                               class="social-link btn btn--outline-base rounded-circle p-2"
                                               title="{{ $link['label'] }}">
                                                <i class="fab fa-{{ $platform }}"></i>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Back to Team Button --}}
                <div class="text-center mt-40">
                    <a href="{{ route('team.index') }}" class="btn btn--outline-secondary px-5 py-3 rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Team
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
.team-member-page {
    background: linear-gradient(135deg, hsl(var(--white)) 0%, hsl(var(--light)) 100%);
    min-height: 60vh;
}

.member-profile-card {
    transition: transform 0.3s ease;
    border: 1px solid hsl(var(--border));
    box-shadow: 0 5px 20px hsl(var(--base)/0.05);
}

.member-image {
    min-height: 500px;
    position: relative;
    overflow: hidden;
    border-radius: 5px 0 0 5px;
}

.member-image img {
    transition: transform 0.5s ease;
}

.member-profile-card:hover .member-image img {
    transform: scale(1.05);
}

.member-info {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.member-name {
    line-height: 1.2;
}

.member-position {
    font-size: 1.5rem;
}

.bio-content {
    line-height: 1.8;
    font-size: 1rem;
}

.bio-content p:last-child {
    margin-bottom: 0;
}

.contact-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: hsl(var(--base)/0.1);
}

.social-link {
    width: 40px;
    height: 40px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    border-width: 1px;
}

.social-link:hover {
    background: linear-gradient(135deg, hsl(var(--base)) 0%, hsl(var(--base-600)) 100%);
    border-color: hsl(var(--base));
    color: hsl(var(--white)) !important;
    transform: translateY(-3px);
    box-shadow: 0 5px 15px hsl(var(--base)/0.3);
}

@media (max-width: 992px) {
    .member-image {
        min-height: 300px;
        border-radius: 5px 5px 0 0;
    }
    
    .member-info {
        padding: 30px !important;
    }
    
    .member-name {
        font-size: 2rem;
    }
    
    .member-position {
        font-size: 1.25rem;
    }
}

@media (max-width: 576px) {
    .member-info {
        padding: 20px !important;
    }
    
    .member-name {
        font-size: 1.75rem;
    }
}
</style>
@endpush