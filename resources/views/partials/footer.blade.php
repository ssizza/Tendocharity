@php
    $footer = @getContent('footer.content', true);
    $policyPages = @getContent('policy_pages.element', orderById:true);
    $teamMembers = \App\Models\Member::active()->limit(4)->get();
@endphp 

<!-- Footer Section -->
<footer class="footer bg-dark-two pt-5 pb-3">
    <div class="container">
        <div class="row gy-4">
            <!-- Column 1: About & Logo -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <a href="{{ route('home') }}" class="footer-logo mb-3 d-inline-block">
                        <img src="{{ siteLogo() }}" alt="@lang('logo')" class="footer-logo-img">
                    </a>
                    <p class="footer-text text-white-50 mb-3">
                        {{ __(@$footer->data_values->description) }}
                    </p>
                    <div class="social-links d-flex gap-3">
                        @if(@$footer->data_values->facebook)
                        <a href="{{ $footer->data_values->facebook }}" target="_blank" class="social-link">
                            <i class="lab la-facebook-f"></i>
                        </a>
                        @endif
                        @if(@$footer->data_values->twitter)
                        <a href="{{ $footer->data_values->twitter }}" target="_blank" class="social-link">
                            <i class="lab la-twitter"></i>
                        </a>
                        @endif
                        @if(@$footer->data_values->instagram)
                        <a href="{{ $footer->data_values->instagram }}" target="_blank" class="social-link">
                            <i class="lab la-instagram"></i>
                        </a>
                        @endif
                        @if(@$footer->data_values->linkedin)
                        <a href="{{ $footer->data_values->linkedin }}" target="_blank" class="social-link">
                            <i class="lab la-linkedin-in"></i>
                        </a>
                        @endif
                        @if(@$footer->data_values->youtube)
                        <a href="{{ $footer->data_values->youtube }}" target="_blank" class="social-link">
                            <i class="lab la-youtube"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h5 class="footer-widget-title text-white mb-4">@lang('Quick Links')</h5>
                    <ul class="footer-links list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" class="text-white-50 text-decoration-none hover-text--base">
                                <i class="las la-angle-right me-2"></i>@lang('Home')
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('team.index') }}" class="text-white-50 text-decoration-none hover-text--base">
                                <i class="las la-angle-right me-2"></i>@lang('Our Team')
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('blogs') }}" class="text-white-50 text-decoration-none hover-text--base">
                                <i class="las la-angle-right me-2"></i>@lang('Announcements')
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('fundraisers.index') }}" class="text-white-50 text-decoration-none hover-text--base">
                                <i class="las la-angle-right me-2"></i>@lang('Fundraisers')
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('contact') }}" class="text-white-50 text-decoration-none hover-text--base">
                                <i class="las la-angle-right me-2"></i>@lang('Contact')
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Column 3: Recent Team Members -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h5 class="footer-widget-title text-white mb-4">@lang('Our Team')</h5>
                    @if($teamMembers->count() > 0)
                    <ul class="team-list list-unstyled">
                        @foreach($teamMembers as $member)
                        <li class="mb-3">
                            <a href="{{ route('team.member', ['id' => $member->id, 'slug' => Str::slug($member->name)]) }}" 
                               class="d-flex align-items-center text-decoration-none">
                                <div class="team-thumb me-3">
                                    <img src="{{ $member->image_url }}" 
                                         alt="{{ $member->name }}" 
                                         class="rounded-circle"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                                <div class="team-info">
                                    <h6 class="text-white mb-0">{{ $member->name }}</h6>
                                    <small class="text-white-50">{{ $member->position }}</small>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-white-50">@lang('No team members found')</p>
                    @endif
                </div>
            </div>

            <!-- Column 4: Contact & Newsletter -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h5 class="footer-widget-title text-white mb-4">@lang('Get In Touch')</h5>
                    
                    <ul class="contact-info list-unstyled">
                        @if(@$footer->data_values->address)
                        <li class="d-flex mb-3">
                            <i class="las la-map-marker text--base me-3 mt-1"></i>
                            <span class="text-white-50">{{ @$footer->data_values->address }}</span>
                        </li>
                        @endif
                        
                        @if(@$footer->data_values->phone)
                        <li class="d-flex mb-3">
                            <i class="las la-phone text--base me-3 mt-1"></i>
                            <a href="tel:{{ @$footer->data_values->phone }}" class="text-white-50 text-decoration-none hover-text--base">
                                {{ @$footer->data_values->phone }}
                            </a>
                        </li>
                        @endif
                        
                        @if(@$footer->data_values->email)
                        <li class="d-flex mb-3">
                            <i class="las la-envelope text--base me-3 mt-1"></i>
                            <a href="mailto:{{ @$footer->data_values->email }}" class="text-white-50 text-decoration-none hover-text--base">
                                {{ @$footer->data_values->email }}
                            </a>
                        </li>
                        @endif
                    </ul>

                    <!-- Newsletter Subscription -->
                    <div class="newsletter mt-4">
                        <h6 class="text-white mb-3">@lang('Subscribe to Newsletter')</h6>
                        <form class="newsletter-form" id="newsletterForm" method="POST" action="{{ route('subscribe') }}">
                            @csrf
                            <div class="input-group">
                                <input type="email" name="email" class="form-control bg-transparent text-white border-white-50" 
                                       placeholder="@lang('Your email address')" required>
                                <button class="btn btn--base" type="submit" id="subscribeBtn">
                                    <i class="las la-paper-plane"></i>
                                </button>
                            </div>
                            <div class="newsletter-message mt-2"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom border-top border-white-10 mt-4 pt-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-white-50 mb-md-0 text-center text-md-start">
                        {{ gs('site_name') }} &copy; {{ date('Y') }}. @lang('All Rights Reserved')
                    </p>
                </div>
                <div class="col-md-6">
                    <ul class="footer-bottom-links d-flex flex-wrap gap-3 justify-content-center justify-content-md-end">
                        @foreach($policyPages as $policyPage)
                        <li>
                            <a href="{{ route('policy.pages', ['slug'=>slug($policyPage->data_values->title)]) }}" 
                               class="text-white-50 text-decoration-none hover-text--base small">
                                {{ __(@$policyPage->data_values->title) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Add JavaScript for newsletter subscription -->
@push('script')
<script>
    (function($) {
        "use strict";
        
        $('#newsletterForm').on('submit', function(e) {
            e.preventDefault();
            
            let form = $(this);
            let url = form.attr('action');
            let data = form.serialize();
            let button = form.find('#subscribeBtn');
            let messageDiv = form.find('.newsletter-message');
            
            // Disable button and show loading
            button.prop('disabled', true);
            button.html('<i class="las la-spinner la-spin"></i>');
            
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        messageDiv.html('<div class="alert alert-success alert-dismissible fade show p-2 small" role="alert">' + 
                            response.message + 
                            '<button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                        form[0].reset();
                    } else {
                        messageDiv.html('<div class="alert alert-danger alert-dismissible fade show p-2 small" role="alert">' + 
                            response.error + 
                            '<button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.error;
                        let errorMessage = Array.isArray(errors) ? errors[0] : 'Validation error';
                        messageDiv.html('<div class="alert alert-danger alert-dismissible fade show p-2 small" role="alert">' + 
                            errorMessage + 
                            '<button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                    } else {
                        messageDiv.html('<div class="alert alert-danger alert-dismissible fade show p-2 small" role="alert">' + 
                            'Something went wrong. Please try again.' + 
                            '<button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                    }
                },
                complete: function() {
                    // Re-enable button
                    button.prop('disabled', false);
                    button.html('<i class="las la-paper-plane"></i>');
                    
                    // Auto hide alert after 5 seconds
                    setTimeout(function() {
                        messageDiv.find('.alert').alert('close');
                    }, 5000);
                }
            });
        });
    })(jQuery);
</script>
@endpush

<style>
.footer {
    background-color: #1a1f2e;
}

.footer-widget-title {
    font-size: 1.2rem;
    font-weight: 600;
    position: relative;
    padding-bottom: 10px;
}

.footer-widget-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 2px;
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
}

.footer-logo-img {
    max-height: 50px;
    width: auto;
}

.social-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-radius: 50%;
    transition: all 0.3s ease;
    text-decoration: none;
}

.social-link:hover {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    transform: translateY(-3px);
    color: #fff;
}

.hover-text--base:hover {
    color: #4facfe !important;
}

.footer-links li a {
    transition: all 0.3s ease;
    display: inline-block;
}

.footer-links li a:hover {
    transform: translateX(5px);
}

.team-thumb {
    border: 2px solid rgba(255,255,255,0.1);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.team-thumb:hover {
    border-color: #4facfe;
    transform: scale(1.05);
}

.newsletter .input-group {
    background: rgba(255,255,255,0.05);
    border-radius: 30px;
    overflow: hidden;
}

.newsletter .form-control {
    border: 1px solid rgba(255,255,255,0.1);
    border-right: none;
    padding: 10px 20px;
    color: #fff;
}

.newsletter .form-control::placeholder {
    color: rgba(255,255,255,0.5);
}

.newsletter .form-control:focus {
    box-shadow: none;
    border-color: #4facfe;
}

.newsletter .btn--base {
    background: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
    border: none;
    padding: 10px 20px;
    border-radius: 0 30px 30px 0;
}

.newsletter .btn--base:hover {
    opacity: 0.9;
    transform: scale(1.02);
}

.newsletter .btn--base:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.newsletter-message .alert {
    margin-top: 10px;
    margin-bottom: 0;
    border-radius: 10px;
    background: rgba(255,255,255,0.1);
    border: none;
    color: #fff;
}

.newsletter-message .alert-success {
    background: rgba(40, 167, 69, 0.2);
    border-left: 3px solid #28a745;
}

.newsletter-message .alert-danger {
    background: rgba(220, 53, 69, 0.2);
    border-left: 3px solid #dc3545;
}

.newsletter-message .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    font-size: 0.8rem;
}

.border-white-10 {
    border-color: rgba(255,255,255,0.1) !important;
}

.border-white-50 {
    border-color: rgba(255,255,255,0.5) !important;
}

.text-white-50 {
    color: rgba(255,255,255,0.7) !important;
}

.footer-bottom-links {
    list-style: none;
    margin: 0;
    padding: 0;
}

.footer-bottom-links li a {
    position: relative;
    padding: 0 5px;
}

.footer-bottom-links li:not(:last-child) a::after {
    content: '|';
    position: absolute;
    right: -10px;
    color: rgba(255,255,255,0.3);
}

@media (max-width: 768px) {
    .footer-widget {
        text-align: center;
    }
    
    .footer-widget-title::after {
        left: 50%;
        transform: translateX(-50%);
    }
    
    .team-list .d-flex {
        justify-content: center;
    }
    
    .contact-info .d-flex {
        justify-content: center;
    }
    
    .social-links {
        justify-content: center;
    }
}

/* Loading spinner animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.la-spin {
    animation: spin 2s linear infinite;
}
</style>