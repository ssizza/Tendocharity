@props(['member'])

<div class="team-member-card h-100">
    <div class="card border-0 shadow-sm h-100 transition-all">
        {{-- Member Image --}}
        @if($member->image)
        <div class="card-img-top position-relative overflow-hidden">
            <img src="{{ $member->image_url }}" 
                 alt="{{ $member->name }}" 
                 class="img-fluid w-100"
                 style="height: 280px; object-fit: cover;">
            
            {{-- Social Overlay --}}
            <div class="card-overlay position-absolute top-0 start-0 w-100 h-100 
                       d-flex align-items-center justify-content-center bg-primary bg-opacity-90">
                <div class="social-icons d-flex gap-2">
                    @foreach($member->social_media_links as $platform => $link)
                    <a href="{{ $link['url'] }}" 
                       target="_blank" 
                       class="social-icon rounded-circle bg-white text-primary d-flex 
                              align-items-center justify-content-center text-decoration-none"
                       style="width: 45px; height: 45px;">
                        <i class="fab fa-{{ $platform }} fa-lg"></i>
                    </a>
                    @endforeach
                    
                    {{-- View Profile Link --}}
                    <a href="{{ route('team.member', ['id' => $member->id, 'slug' => Str::slug($member->name)]) }}"
                       class="social-icon rounded-circle bg-white text-primary d-flex 
                              align-items-center justify-content-center text-decoration-none"
                       style="width: 45px; height: 45px;">
                        <i class="fas fa-user fa-lg"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif
        
        {{-- Card Body --}}
        <div class="card-body text-center p-4">
            <h5 class="card-title mb-2">
                <a href="{{ route('team.member', ['id' => $member->id, 'slug' => Str::slug($member->name)]) }}"
                   class="text-decoration-none text-dark fw-bold">
                    {{ $member->name }}
                </a>
            </h5>
            
            @if($member->position)
            <p class="card-subtitle text-primary mb-2">
                {{ $member->position }}
            </p>
            @endif
            
            @if($member->category)
            <span class="badge bg-light-subtle text-secondary px-3 py-2 rounded-pill mb-3">
                {{ $member->category->name }}
            </span>
            @endif
            
            @if($member->bio)
            <p class="card-text text-muted small mb-3">
                {{ Str::limit(strip_tags($member->bio), 80) }}
            </p>
            @endif
            
            <a href="{{ route('team.member', ['id' => $member->id, 'slug' => Str::slug($member->name)]) }}"
               class="btn btn-outline-primary btn-sm rounded-pill px-4">
                View Full Profile
                <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</div>

<style>
.team-member-card .card {
    border-radius: 15px;
    transition: all 0.3s ease;
}

.team-member-card .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
}

.team-member-card .card-img-top {
    border-radius: 15px 15px 0 0;
    height: 280px;
}

.team-member-card .card-overlay {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.team-member-card .card:hover .card-overlay {
    opacity: 1;
}

.team-member-card .social-icon {
    transition: all 0.3s ease;
    transform: scale(0);
}

.team-member-card .card:hover .social-icon {
    transform: scale(1);
}

.team-member-card .social-icon:hover {
    background: #333 !important;
    color: white !important;
    transform: scale(1.1) !important;
}

.team-member-card .card-title a:hover {
    color: #4facfe !important;
}

.team-member-card .btn-outline-primary {
    border-width: 2px;
    transition: all 0.3s ease;
}

.team-member-card .btn-outline-primary:hover {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    border-color: transparent;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
}

.team-member-card .btn-outline-primary:hover i {
    transform: translateX(3px);
}

.team-member-card .btn-outline-primary i {
    transition: transform 0.3s ease;
}
</style>