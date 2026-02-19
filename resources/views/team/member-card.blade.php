@props(['member'])

<div class="team-member-card h-100">
    <div class="card border-0 shadow-sm h-100 transition-all custom--card">
        {{-- Member Image --}}
        @if($member->image)
        <div class="card-img-top position-relative overflow-hidden rounded-top">
            <img src="{{ $member->image_url }}" 
                 alt="{{ $member->name }}" 
                 class="img-fluid w-100 object-cover"
                 style="height: 280px;">
            
            {{-- Social Overlay --}}
            <div class="card-overlay position-absolute top-0 start-0 w-100 h-100 
                       d-flex align-items-center justify-content-center bg--base"
                 style="opacity: 0; transition: opacity 0.3s ease; background-color: hsl(var(--base)/0.9) !important;">
                <div class="social-icons d-flex gap-2">
                    @foreach($member->social_media_links as $platform => $link)
                    <a href="{{ $link['url'] }}" 
                       target="_blank" 
                       class="social-icon rounded-circle bg-white d-flex 
                              align-items-center justify-content-center text-decoration-none"
                       style="width: 45px; height: 45px; transform: scale(0); transition: all 0.3s ease;">
                        <i class="fab fa-{{ $platform }} fa-lg text--base"></i>
                    </a>
                    @endforeach
                    
                    {{-- View Profile Link --}}
                    <a href="{{ route('team.member', ['id' => $member->id, 'slug' => Str::slug($member->name)]) }}"
                       class="social-icon rounded-circle bg-white d-flex 
                              align-items-center justify-content-center text-decoration-none"
                       style="width: 45px; height: 45px; transform: scale(0); transition: all 0.3s ease;">
                        <i class="fas fa-user fa-lg text--base"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif
        
        {{-- Card Body --}}
        <div class="card-body text-center p-4">
            <h5 class="card-title mb-2">
                <a href="{{ route('team.member', ['id' => $member->id, 'slug' => Str::slug($member->name)]) }}"
                   class="text-decoration-none fw-bold title-link">
                    {{ $member->name }}
                </a>
            </h5>
            
            @if($member->position)
            <p class="card-subtitle text--base mb-2">
                {{ $member->position }}
            </p>
            @endif
            
            @if($member->category)
            <span class="badge bg--light text--secondary px-3 py-2 rounded-pill mb-3">
                {{ $member->category->name }}
            </span>
            @endif
            
            @if($member->bio)
            <p class="card-text text--body small mb-3">
                {{ Str::limit(strip_tags($member->bio), 80) }}
            </p>
            @endif
            
            <a href="{{ route('team.member', ['id' => $member->id, 'slug' => Str::slug($member->name)]) }}"
               class="btn btn--outline-base btn-sm rounded-pill px-4">
                View Full Profile
                <i class="fas fa-arrow-right ms-2 icon-transition"></i>
            </a>
        </div>
    </div>
</div>

<style>
.team-member-card .custom--card {
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.team-member-card .custom--card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px hsl(var(--dark)/0.1) !important;
}

.team-member-card .card-img-top {
    border-radius: 15px 15px 0 0;
    height: 280px;
    overflow: hidden;
}

.team-member-card .object-cover {
    object-fit: cover;
    width: 100%;
    height: 100%;
    transition: transform 0.5s ease;
}

.team-member-card .custom--card:hover .object-cover {
    transform: scale(1.05);
}

.team-member-card .card-overlay {
    opacity: 0;
    background-color: hsl(var(--base)/0.9) !important;
    backdrop-filter: blur(3px);
}

.team-member-card .custom--card:hover .card-overlay {
    opacity: 1;
}

.team-member-card .custom--card:hover .social-icon {
    transform: scale(1) !important;
}

.team-member-card .social-icon {
    transition: all 0.3s ease;
}

.team-member-card .social-icon:hover {
    background-color: hsl(var(--base-600)) !important;
}

.team-member-card .social-icon:hover i {
    color: hsl(var(--white)) !important;
}

.team-member-card .title-link {
    color: hsl(var(--heading));
    transition: color 0.3s ease;
}

.team-member-card .title-link:hover {
    color: hsl(var(--base)) !important;
}

.team-member-card .btn--outline-base {
    border-width: 2px;
    transition: all 0.3s ease;
}

.team-member-card .btn--outline-base:hover {
    background: linear-gradient(to right, hsl(var(--base-400)) 0%, hsl(var(--base-600)) 100%);
    border-color: transparent;
    color: hsl(var(--white));
    transform: translateY(-2px);
    box-shadow: 0 5px 15px hsl(var(--base)/0.3);
}

.team-member-card .btn--outline-base:hover .icon-transition {
    transform: translateX(3px);
}

.team-member-card .icon-transition {
    transition: transform 0.3s ease;
    display: inline-block;
}

.team-member-card .bg--light {
    background-color: hsl(var(--light)) !important;
}

.team-member-card .text--body {
    color: hsl(var(--body)) !important;
    line-height: 1.6;
}

/* Animation delay for social icons */
@keyframes fadeInScale {
    0% {
        opacity: 0;
        transform: scale(0);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

.team-member-card .custom--card:hover .social-icon {
    animation: fadeInScale 0.3s ease forwards;
}

.team-member-card .custom--card:hover .social-icon:nth-child(1) {
    animation-delay: 0.1s;
}

.team-member-card .custom--card:hover .social-icon:nth-child(2) {
    animation-delay: 0.2s;
}

.team-member-card .custom--card:hover .social-icon:nth-child(3) {
    animation-delay: 0.3s;
}

.team-member-card .custom--card:hover .social-icon:nth-child(4) {
    animation-delay: 0.4s;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .team-member-card .card-img-top {
        height: 220px;
    }
    
    .team-member-card .social-icon {
        width: 35px !important;
        height: 35px !important;
    }
    
    .team-member-card .social-icon i {
        font-size: 14px;
    }
}
</style>