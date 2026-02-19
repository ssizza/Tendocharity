@extends('layouts.frontend')

@section('content')
<div class="pt-120 pb-80">
    <div class="container">
        <!-- Success Message Alert -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="las la-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Error Message Alert -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="las la-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Validation Errors -->
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="las la-exclamation-circle me-2"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <!-- Event Banner -->
        <div class="position-relative mb-4">
            <img src="{{ asset('assets/images/events/' . $event->image) }}" 
                 alt="{{ $event->title }}" 
                 class="w-100 rounded" 
                 style="height: 400px; object-fit: cover;">
            @if($event->status === 'upcoming')
            <span class="position-absolute top-0 end-0 m-3 badge bg-primary rounded-pill py-2 px-3">Upcoming</span>
            @elseif($event->status === 'ongoing')
            <span class="position-absolute top-0 end-0 m-3 badge bg-success rounded-pill py-2 px-3">Ongoing</span>
            @elseif($event->status === 'completed')
            <span class="position-absolute top-0 end-0 m-3 badge bg-secondary rounded-pill py-2 px-3">Completed</span>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Event Details -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h1 class="mb-4" style="color: hsl(var(--heading)); font-size: 2rem;">{{ $event->title }}</h1>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-3 rounded" style="background-color: hsl(var(--light))">
                                        <i class="las la-calendar" style="color: hsl(var(--base)); font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold" style="color: hsl(var(--heading))">Date & Time</h6>
                                        <p class="mb-0" style="color: hsl(var(--body))">
                                            {{ $event->startDate->format('l, F d, Y') }}<br>
                                            {{ $event->startDate->format('h:i A') }} - {{ $event->endDate->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="p-3 rounded" style="background-color: hsl(var(--light))">
                                        <i class="las la-map-marker" style="color: hsl(var(--base)); font-size: 1.5rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-semibold" style="color: hsl(var(--heading))">Location</h6>
                                        <p class="mb-0" style="color: hsl(var(--body))">{{ $event->location }}</p>
                                        <small style="color: hsl(var(--body))">{{ $event->type === 'virtual' ? 'Virtual Event' : 'In-person Event' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h4 class="mb-3 pb-2" style="border-bottom: 1px solid hsl(var(--border)); color: hsl(var(--heading))">About This Event</h4>
                            <div class="content" style="color: hsl(var(--body))">
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
                        <h4 class="mb-4 pb-2" style="border-bottom: 1px solid hsl(var(--border)); color: hsl(var(--heading))">Event Gallery</h4>
                        <div class="row g-3" id="eventGallery">
                            @foreach($gallery as $image)
                            <div class="col-md-4 col-sm-6">
                                <div class="position-relative overflow-hidden rounded">
                                    <a href="{{ asset('assets/images/events/gallery/' . $image->image_url) }}" 
                                       class="d-block position-relative gallery-item"
                                       data-lightbox="event-gallery"
                                       data-title="{{ $image->alt ?? 'Event Image' }}"
                                       data-alt="{{ $image->alt ?? 'Event Image' }}">
                                        <img src="{{ asset('assets/images/events/gallery/' . $image->image_url) }}" 
                                             alt="{{ $image->alt ?? 'Event Image' }}" 
                                             class="img-fluid w-100"
                                             style="height: 200px; object-fit: cover; transition: transform 0.3s ease;"
                                             loading="lazy">
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                             style="background: rgba(0,0,0,0.5); opacity: 0; transition: opacity 0.3s ease;">
                                            <i class="las la-search-plus text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    </a>
                                    @if($image->alt)
                                    <p class="text-center small mt-2 mb-0" style="color: hsl(var(--body))">{{ Str::limit($image->alt, 30) }}</p>
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
                        <h4 class="mb-4 pb-2" style="border-bottom: 1px solid hsl(var(--border)); color: hsl(var(--heading))">Book Your Spot</h4>
                        <p class="mb-4" style="color: hsl(var(--body))">Fill out the form below to register for this event. We'll send you a confirmation email with all the details.</p>
                        
                        <form method="POST" action="{{ route('event.book', $event->id) }}" id="bookingForm">
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label required fw-semibold" style="color: hsl(var(--heading))">Full Name</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                        @error('name')
                                            <span class="small" style="color: hsl(var(--danger))">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-label required fw-semibold" style="color: hsl(var(--heading))">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                        @error('email')
                                            <span class="small" style="color: hsl(var(--danger))">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-label required fw-semibold" style="color: hsl(var(--heading))">Phone Number</label>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone', auth()->user()->mobile ?? '') }}" required>
                                        @error('phone')
                                            <span class="small" style="color: hsl(var(--danger))">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms" style="color: hsl(var(--body))">
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
                <div class="alert alert-info d-flex align-items-center">
                    <i class="las la-info-circle me-2 fs-5"></i>
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
                        <h5 class="card-title mb-3" style="color: hsl(var(--heading))">Event Information</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span style="color: hsl(var(--body))">Status</span>
                                <span class="badge bg-{{ $event->status === 'upcoming' ? 'primary' : ($event->status === 'ongoing' ? 'success' : 'secondary') }} rounded-pill">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span style="color: hsl(var(--body))">Type</span>
                                <span style="color: hsl(var(--heading))">{{ ucfirst($event->type) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span style="color: hsl(var(--body))">Registrations</span>
                                <span style="color: hsl(var(--heading))">{{ $applicantsCount }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span style="color: hsl(var(--body))">Date</span>
                                <span style="color: hsl(var(--heading))">{{ $event->startDate->format('M d, Y') }}</span>
                            </li>
                            <li class="list-group-item px-0">
                                <small style="color: hsl(var(--body))">
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
                        <h5 class="card-title mb-3" style="color: hsl(var(--heading))">Share This Event</h5>
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
                        <h5 class="card-title mb-3" style="color: hsl(var(--heading))">Upcoming Events</h5>
                        <div class="related-events">
                            @foreach($relatedEvents as $relatedEvent)
                            <div class="mb-3 pb-3" style="border-bottom: 1px solid hsl(var(--border))">
                                <div class="d-flex gap-3">
                                    <img src="{{ asset('assets/images/events/' . $relatedEvent->image) }}" 
                                         alt="{{ $relatedEvent->title }}" 
                                         class="rounded" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1">
                                            <a href="{{ route('event.details', ['id' => $relatedEvent->id, 'slug' => Str::slug($relatedEvent->title)]) }}" 
                                               style="color: hsl(var(--heading)); text-decoration: none;">
                                                {{ Str::limit($relatedEvent->title, 40) }}
                                            </a>
                                        </h6>
                                        <small style="color: hsl(var(--body))">
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
<div class="position-fixed top-0 start-0 w-100 h-100" id="lightboxModal" 
     style="display: none; background: rgba(0,0,0,0.9); z-index: 9999;">
    <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center">
        <button class="position-absolute top-0 end-0 m-4 bg-transparent border-0 text-white" 
                id="lightboxClose" style="font-size: 2rem; cursor: pointer;">
            <i class="las la-times"></i>
        </button>
        <button class="position-absolute start-0 ms-4 bg-transparent border-0 text-white" 
                id="lightboxPrev" style="font-size: 2rem; cursor: pointer; transform: translateY(-50%); top: 50%;">
            <i class="las la-angle-left"></i>
        </button>
        <button class="position-absolute end-0 me-4 bg-transparent border-0 text-white" 
                id="lightboxNext" style="font-size: 2rem; cursor: pointer; transform: translateY(-50%); top: 50%;">
            <i class="las la-angle-right"></i>
        </button>
        <div class="position-relative">
            <img id="lightboxImage" src="" alt="" style="max-width: 90vw; max-height: 80vh; object-fit: contain; opacity: 0; transition: opacity 0.3s ease;">
            <div class="position-absolute top-50 start-50 translate-middle" id="lightboxLoader" style="display: none;">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div class="position-absolute bottom-0 start-0 w-100 text-center text-white p-4">
            <p id="lightboxCaption" style="color: hsl(var(--white))"></p>
            <div class="lightbox-counter" style="color: rgba(255,255,255,0.8);">
                <span id="currentIndex">1</span> / <span id="totalImages">{{ $gallery->count() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.gallery-item:hover img {
    transform: scale(1.05);
}

.gallery-item:hover .position-absolute {
    opacity: 1 !important;
}

.required::after {
    content: " *";
    color: hsl(var(--danger));
}

.related-event-item:hover {
    background-color: hsl(var(--light));
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.toast-notification {
    animation: slideIn 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
    
    if (totalImagesSpan) {
        totalImagesSpan.textContent = images.length;
    }
    
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
        lightboxModal.style.display = 'flex';
        lightboxModal.style.animation = 'fadeIn 0.3s ease';
        document.body.style.overflow = 'hidden';
        loadImage(currentIndex);
        updateCounter();
    }
    
    // Close Lightbox
    if (lightboxClose) {
        lightboxClose.addEventListener('click', closeLightbox);
    }
    
    lightboxModal.addEventListener('click', function(e) {
        if (e.target === lightboxModal) {
            closeLightbox();
        }
    });
    
    // Keyboard Navigation
    document.addEventListener('keydown', function(e) {
        if (lightboxModal.style.display !== 'flex') return;
        
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
    if (lightboxPrev) {
        lightboxPrev.addEventListener('click', () => navigate(-1));
    }
    if (lightboxNext) {
        lightboxNext.addEventListener('click', () => navigate(1));
    }
    
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
        lightboxImage.style.opacity = '0';
        lightboxLoader.style.display = 'block';
        
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
                lightboxImage.style.opacity = '1';
            }, 300);
        };
        
        img.onerror = function() {
            lightboxLoader.style.display = 'none';
            lightboxImage.src = '{{ asset("assets/images/default.png") }}';
            lightboxImage.alt = 'Image not found';
            lightboxCaption.textContent = 'Image could not be loaded';
            lightboxImage.style.opacity = '1';
        };
    }
    
    // Update Counter
    function updateCounter() {
        if (currentIndexSpan) {
            currentIndexSpan.textContent = currentIndex + 1;
        }
    }
    
    // Close Lightbox Function
    function closeLightbox() {
        lightboxModal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Reset image state
        setTimeout(() => {
            lightboxImage.src = '';
            lightboxImage.alt = '';
            lightboxCaption.textContent = '';
            lightboxImage.style.opacity = '0';
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
    toast.className = `toast-notification`;
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