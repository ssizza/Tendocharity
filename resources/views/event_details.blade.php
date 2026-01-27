@extends('layouts.frontend')

@section('content')
<div class="pt-120 pb-80">
    <div class="container">
        <!-- Success Message Alert -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="las la-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Error Message Alert -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="las la-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Validation Errors -->
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="las la-exclamation-circle"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Event Banner -->
        <div class="event-banner mb-4 position-relative">
            <img src="{{ asset('assets/images/events/' . $event->image) }}" 
                 alt="{{ $event->title }}" 
                 class="w-100 rounded" 
                 style="height: 400px; object-fit: cover;">
            @if($event->status === 'upcoming')
            <span class="event-status-badge bg-primary">Upcoming</span>
            @elseif($event->status === 'ongoing')
            <span class="event-status-badge bg-success">Ongoing</span>
            @elseif($event->status === 'completed')
            <span class="event-status-badge bg-secondary">Completed</span>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Event Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h1 class="event-title mb-4">{{ $event->title }}</h1>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light p-3 rounded">
                                        <i class="las la-calendar text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Date & Time</h6>
                                        <p class="mb-0 text-muted">
                                            {{ $event->startDate->format('l, F d, Y') }}<br>
                                            {{ $event->startDate->format('h:i A') }} - {{ $event->endDate->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light p-3 rounded">
                                        <i class="las la-map-marker text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold">Location</h6>
                                        <p class="mb-0 text-muted">{{ $event->location }}</p>
                                        <small class="text-muted">{{ $event->type === 'virtual' ? 'Virtual Event' : 'In-person Event' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="event-description mb-4">
                            <h4 class="mb-3 border-bottom pb-2">About This Event</h4>
                            <div class="content">
                                @if(isset($description['short_description']))
                                    <p class="lead">{{ $description['short_description'] }}</p>
                                @endif
                                
                                @if(isset($description['full_description']))
                                    {!! $description['full_description'] !!}
                                @else
                                    <p>{{ $event->title }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 flex-wrap mb-4">
                            @if($isOpenForBooking)
                            <a href="#booking" class="btn btn-primary">
                                <i class="las la-ticket-alt"></i> Book Your Spot
                            </a>
                            @endif
                            
                            <a href="{{ route('event.calendar', $event->id) }}" class="btn btn-outline-primary" target="_blank">
                                <i class="las la-calendar-plus"></i> Add to Calendar
                            </a>
                            
                            <button class="btn btn-outline-secondary" onclick="shareEvent()">
                                <i class="las la-share-alt"></i> Share Event
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Event Gallery -->
                @if($gallery->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="mb-4 border-bottom pb-2">Event Gallery</h4>
                        <div class="row g-3" id="eventGallery">
                            @foreach($gallery as $image)
                            <div class="col-md-4 col-sm-6">
                                <div class="gallery-item-wrapper">
                                    <a href="{{ asset('assets/images/events/gallery/' . $image->image_url) }}" 
                                       class="gallery-item d-block position-relative"
                                       data-lightbox="event-gallery"
                                       data-title="{{ $image->alt ?? 'Event Image' }}"
                                       data-alt="{{ $image->alt ?? 'Event Image' }}">
                                        <img src="{{ asset('assets/images/events/gallery/' . $image->image_url) }}" 
                                             alt="{{ $image->alt ?? 'Event Image' }}" 
                                             class="img-fluid rounded gallery-thumbnail"
                                             loading="lazy">
                                        <div class="gallery-overlay">
                                            <i class="las la-search-plus"></i>
                                        </div>
                                    </a>
                                    @if($image->alt)
                                    <p class="text-center small text-muted mt-2 mb-0">{{ Str::limit($image->alt, 30) }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Booking Form -->
                @if($isOpenForBooking)
                <div class="card border-0 shadow-sm" id="booking">
                    <div class="card-body">
                        <h4 class="mb-4 border-bottom pb-2">Book Your Spot</h4>
                        <p class="text-muted mb-4">Fill out the form below to register for this event. We'll send you a confirmation email with all the details.</p>
                        
                        <form method="POST" action="{{ route('event.book', $event->id) }}" id="bookingForm">
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label required">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                        @error('name')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label required">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                        @error('email')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label required">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone', auth()->user()->mobile ?? '') }}" required>
                                        @error('phone')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to receive updates about this event via email
                                        </label>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="las la-paper-plane"></i> Submit Registration
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="las la-info-circle"></i> 
                    @if($event->status === 'completed')
                        This event has already been completed. Thank you to everyone who participated!
                    @elseif($event->status === 'cancelled')
                        This event has been cancelled.
                    @elseif($event->status === 'ongoing')
                        This event is currently ongoing. Registration may be closed.
                    @endif
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Event Stats -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Event Information</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Status</span>
                                <span class="badge bg-{{ $event->status === 'upcoming' ? 'primary' : ($event->status === 'ongoing' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Type</span>
                                <span>{{ ucfirst($event->type) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Registrations</span>
                                <span>{{ $applicantsCount }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Date</span>
                                <span>{{ $event->startDate->format('M d, Y') }}</span>
                            </li>
                            <li class="list-group-item">
                                <small class="text-muted">
                                    <i class="las la-clock"></i> 
                                    {{ $event->startDate->diffForHumans() }}
                                </small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Share Event -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Share This Event</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-outline-primary flex-fill" onclick="shareFacebook()">
                                <i class="lab la-facebook-f"></i> Facebook
                            </button>
                            <button class="btn btn-sm btn-outline-info flex-fill" onclick="shareTwitter()">
                                <i class="lab la-twitter"></i> Twitter
                            </button>
                            <button class="btn btn-sm btn-outline-danger flex-fill" onclick="shareEmail()">
                                <i class="las la-envelope"></i> Email
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Related Events -->
                @if($relatedEvents && $relatedEvents->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Upcoming Events</h5>
                        <div class="related-events">
                            @foreach($relatedEvents as $relatedEvent)
                            <div class="related-event-item mb-3 pb-3 border-bottom">
                                <div class="d-flex gap-3">
                                    <img src="{{ asset('assets/images/events/' . $relatedEvent->image) }}" 
                                         alt="{{ $relatedEvent->title }}" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('event.details', ['id' => $relatedEvent->id, 'slug' => Str::slug($relatedEvent->title)]) }}" 
                                               class="text-dark text-decoration-none">
                                                {{ Str::limit($relatedEvent->title, 40) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">
                                            <i class="las la-calendar"></i> 
                                            {{ $relatedEvent->startDate->format('M d, Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="lightbox-modal" id="lightboxModal">
    <div class="lightbox-modal-content">
        <button class="lightbox-close" id="lightboxClose">
            <i class="las la-times"></i>
        </button>
        <button class="lightbox-nav lightbox-prev" id="lightboxPrev">
            <i class="las la-angle-left"></i>
        </button>
        <button class="lightbox-nav lightbox-next" id="lightboxNext">
            <i class="las la-angle-right"></i>
        </button>
        <div class="lightbox-image-container">
            <img id="lightboxImage" src="" alt="">
            <div class="lightbox-loader" id="lightboxLoader">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div class="lightbox-caption">
            <p id="lightboxCaption"></p>
            <div class="lightbox-counter">
                <span id="currentIndex">1</span> / <span id="totalImages">{{ $gallery->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.event-banner {
    position: relative;
}

.event-status-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    color: #fff;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    z-index: 1;
}

.event-title {
    color: #333;
    font-weight: 700;
    font-size: 2rem;
}

/* Gallery Styles */
.gallery-item-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.gallery-item {
    position: relative;
    display: block;
    overflow: hidden;
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.gallery-thumbnail {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    color: white;
    font-size: 2rem;
}

.gallery-item:hover .gallery-overlay {
    opacity: 1;
}

.gallery-item:hover .gallery-thumbnail {
    transform: scale(1.05);
}

/* Lightbox Modal Styles */
.lightbox-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

.lightbox-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-modal-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
    width: auto;
    height: auto;
}

.lightbox-image-container {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 200px;
}

#lightboxImage {
    max-width: 100%;
    max-height: 70vh;
    object-fit: contain;
    opacity: 0;
    transition: opacity 0.3s ease;
}

#lightboxImage.loaded {
    opacity: 1;
}

.lightbox-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    padding: 5px;
    transition: color 0.3s ease;
}

.lightbox-close:hover {
    color: #f8f9fa;
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    font-size: 2rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.3s ease;
}

.lightbox-nav:hover {
    background: rgba(255, 255, 255, 0.3);
}

.lightbox-prev {
    left: 20px;
}

.lightbox-next {
    right: 20px;
}

.lightbox-caption {
    position: absolute;
    bottom: -50px;
    left: 0;
    width: 100%;
    text-align: center;
    color: white;
    padding: 10px;
}

.lightbox-counter {
    margin-top: 5px;
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.8);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Responsive */
@media (max-width: 768px) {
    .lightbox-nav {
        width: 40px;
        height: 40px;
        font-size: 1.5rem;
    }
    
    .lightbox-prev {
        left: 10px;
    }
    
    .lightbox-next {
        right: 10px;
    }
    
    .lightbox-close {
        top: -35px;
        font-size: 1.5rem;
    }
    
    .gallery-thumbnail {
        height: 150px;
    }
}

/* Related Events */
.related-event-item {
    padding: 10px;
    border-radius: 8px;
    transition: background 0.3s ease;
}

.related-event-item:hover {
    background: #f8f9fa;
}

.related-event-item h6 a {
    text-decoration: none;
    color: #333;
}

.related-event-item h6 a:hover {
    color: var(--base-color);
}

.required::after {
    content: " *";
    color: #dc3545;
}
</style>
@endpush

@push('script')
<script>
// Gallery Lightbox Functionality
document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const lightboxClose = document.getElementById('lightboxClose');
    const lightboxPrev = document.getElementById('lightboxPrev');
    const lightboxNext = document.getElementById('lightboxNext');
    const currentIndexSpan = document.getElementById('currentIndex');
    const totalImagesSpan = document.getElementById('totalImages');
    const lightboxLoader = document.getElementById('lightboxLoader');
    
    let currentIndex = 0;
    const images = Array.from(galleryItems).map(item => ({
        src: item.href,
        alt: item.getAttribute('data-alt') || item.querySelector('img').alt,
        title: item.getAttribute('data-title') || ''
    }));
    
    totalImagesSpan.textContent = images.length;
    
    // Open Lightbox
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            currentIndex = index;
            openLightbox();
        });
    });
    
    // Open Lightbox Function
    function openLightbox() {
        lightboxModal.classList.add('active');
        document.body.style.overflow = 'hidden';
        loadImage(currentIndex);
        updateCounter();
    }
    
    // Close Lightbox
    lightboxClose.addEventListener('click', closeLightbox);
    lightboxModal.addEventListener('click', function(e) {
        if (e.target === lightboxModal) {
            closeLightbox();
        }
    });
    
    // Keyboard Navigation
    document.addEventListener('keydown', function(e) {
        if (!lightboxModal.classList.contains('active')) return;
        
        switch(e.key) {
            case 'Escape':
                closeLightbox();
                break;
            case 'ArrowLeft':
                navigate(-1);
                break;
            case 'ArrowRight':
                navigate(1);
                break;
        }
    });
    
    // Navigation
    lightboxPrev.addEventListener('click', () => navigate(-1));
    lightboxNext.addEventListener('click', () => navigate(1));
    
    // Navigation Function
    function navigate(direction) {
        currentIndex += direction;
        
        if (currentIndex < 0) {
            currentIndex = images.length - 1;
        } else if (currentIndex >= images.length) {
            currentIndex = 0;
        }
        
        loadImage(currentIndex);
        updateCounter();
    }
    
    // Load Image Function
    function loadImage(index) {
        const image = images[index];
        
        // Show loader
        lightboxImage.classList.remove('loaded');
        lightboxLoader.style.display = 'flex';
        
        // Create new image for preloading
        const img = new Image();
        img.src = image.src;
        img.alt = image.alt;
        
        img.onload = function() {
            // Set image source and alt
            lightboxImage.src = image.src;
            lightboxImage.alt = image.alt;
            
            // Set caption
            if (image.title) {
                lightboxCaption.textContent = image.title;
                lightboxCaption.style.display = 'block';
            } else {
                lightboxCaption.style.display = 'none';
            }
            
            // Hide loader and show image
            setTimeout(() => {
                lightboxLoader.style.display = 'none';
                lightboxImage.classList.add('loaded');
            }, 300);
        };
        
        img.onerror = function() {
            lightboxLoader.style.display = 'none';
            lightboxImage.src = '{{ asset("assets/images/default.png") }}';
            lightboxImage.alt = 'Image not found';
            lightboxCaption.textContent = 'Image could not be loaded';
            lightboxImage.classList.add('loaded');
        };
    }
    
    // Update Counter
    function updateCounter() {
        currentIndexSpan.textContent = currentIndex + 1;
    }
    
    // Close Lightbox Function
    function closeLightbox() {
        lightboxModal.classList.remove('active');
        document.body.style.overflow = 'auto';
        
        // Reset image state
        setTimeout(() => {
            lightboxImage.src = '';
            lightboxImage.alt = '';
            lightboxCaption.textContent = '';
            lightboxImage.classList.remove('loaded');
        }, 300);
    }
    
    // Smooth scroll for booking anchor
    const bookingLink = document.querySelector('a[href="#booking"]');
    if (bookingLink) {
        bookingLink.addEventListener('click', function(e) {
            e.preventDefault();
            const bookingSection = document.getElementById('booking');
            if (bookingSection) {
                bookingSection.scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    }
});

// Share Functions
function shareEvent() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $event->title }}',
            text: 'Join me at this event!',
            url: window.location.href,
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        showToast('Link copied to clipboard!', 'success');
    }
}

function shareFacebook() {
    window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank', 'width=600,height=400');
}

function shareTwitter() {
    window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent('{{ $event->title }}') + '&url=' + encodeURIComponent(window.location.href), '_blank', 'width=600,height=400');
}

function shareEmail() {
    window.location.href = 'mailto:?subject=' + encodeURIComponent('{{ $event->title }}') + '&body=' + encodeURIComponent('Check out this event: ' + window.location.href);
}

// Toast Notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${type === 'success' ? '#28a745' : '#007bff'};
        color: white;
        border-radius: 5px;
        z-index: 10000;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Form Submission Feedback
const bookingForm = document.getElementById('bookingForm');
if (bookingForm) {
    bookingForm.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="las la-spinner la-spin"></i> Processing...';
            submitBtn.disabled = true;
        }
    });
}
</script>
@endpush