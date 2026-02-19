@php
    $footer = @getContent('footer.content', true);
    $policyPages = @getContent('policy_pages.element', orderById:true);
    $teamMembers = \App\Models\Member::active()->limit(4)->get();
@endphp 

<!-- Footer Section -->
<footer class="footer pt-5 pb-3" 
        style="background-color: hsl(var(--dark)); margin-top: auto;">
    <div class="container">
        <div class="row gy-4">
            <!-- Column 1: About & Logo -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <a href="{{ route('home') }}" class="footer-logo mb-3 d-inline-block">
                        <img src="{{ siteLogo() }}" alt="@lang('logo')" 
                             style="max-height: 50px; width: auto;">
                    </a>
                    <p class="footer-text mb-3" 
                       style="color: hsl(var(--white)/0.7);">
                        {{ __(@$footer->data_values->description) }}
                    </p>
                    <div class="social-links d-flex gap-3">
                        @if(@$footer->data_values->facebook)
                        <a href="{{ $footer->data_values->facebook }}" target="_blank" 
                           style="display: inline-flex; align-items: center; justify-content: center; 
                                  width: 36px; height: 36px; background-color: hsl(var(--white)/0.1); 
                                  color: hsl(var(--white)); border-radius: 50%; 
                                  transition: all 0.3s ease; text-decoration: none;">
                            <i class="lab la-facebook-f"></i>
                        </a>
                        @endif
                        @if(@$footer->data_values->twitter)
                        <a href="{{ $footer->data_values->twitter }}" target="_blank" 
                           style="display: inline-flex; align-items: center; justify-content: center; 
                                  width: 36px; height: 36px; background-color: hsl(var(--white)/0.1); 
                                  color: hsl(var(--white)); border-radius: 50%; 
                                  transition: all 0.3s ease; text-decoration: none;">
                            <i class="lab la-twitter"></i>
                        </a>
                        @endif
                        @if(@$footer->data_values->instagram)
                        <a href="{{ $footer->data_values->instagram }}" target="_blank" 
                           style="display: inline-flex; align-items: center; justify-content: center; 
                                  width: 36px; height: 36px; background-color: hsl(var(--white)/0.1); 
                                  color: hsl(var(--white)); border-radius: 50%; 
                                  transition: all 0.3s ease; text-decoration: none;">
                            <i class="lab la-instagram"></i>
                        </a>
                        @endif
                        @if(@$footer->data_values->linkedin)
                        <a href="{{ $footer->data_values->linkedin }}" target="_blank" 
                           style="display: inline-flex; align-items: center; justify-content: center; 
                                  width: 36px; height: 36px; background-color: hsl(var(--white)/0.1); 
                                  color: hsl(var(--white)); border-radius: 50%; 
                                  transition: all 0.3s ease; text-decoration: none;">
                            <i class="lab la-linkedin-in"></i>
                        </a>
                        @endif
                        @if(@$footer->data_values->youtube)
                        <a href="{{ $footer->data_values->youtube }}" target="_blank" 
                           style="display: inline-flex; align-items: center; justify-content: center; 
                                  width: 36px; height: 36px; background-color: hsl(var(--white)/0.1); 
                                  color: hsl(var(--white)); border-radius: 50%; 
                                  transition: all 0.3s ease; text-decoration: none;">
                            <i class="lab la-youtube"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h5 class="mb-4" 
                        style="color: hsl(var(--white)); font-size: 1.2rem; font-weight: 600; 
                               position: relative; padding-bottom: 10px;">
                        @lang('Quick Links')
                        <span style="position: absolute; left: 0; bottom: 0; width: 50px; 
                                   height: 2px; background: linear-gradient(to right, 
                                   hsl(var(--base)) 0%, hsl(var(--base-400)) 100%);"></span>
                    </h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('home') }}" 
                               style="color: hsl(var(--white)/0.7); text-decoration: none; 
                                      transition: all 0.3s ease; display: inline-block;">
                                <i class="las la-angle-right me-2" style="color: hsl(var(--base));"></i>
                                @lang('Home')
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('team.index') }}" 
                               style="color: hsl(var(--white)/0.7); text-decoration: none; 
                                      transition: all 0.3s ease; display: inline-block;">
                                <i class="las la-angle-right me-2" style="color: hsl(var(--base));"></i>
                                @lang('Our Team')
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('blogs') }}" 
                               style="color: hsl(var(--white)/0.7); text-decoration: none; 
                                      transition: all 0.3s ease; display: inline-block;">
                                <i class="las la-angle-right me-2" style="color: hsl(var(--base));"></i>
                                @lang('Announcements')
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('fundraisers.index') }}" 
                               style="color: hsl(var(--white)/0.7); text-decoration: none; 
                                      transition: all 0.3s ease; display: inline-block;">
                                <i class="las la-angle-right me-2" style="color: hsl(var(--base));"></i>
                                @lang('Fundraisers')
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('contact') }}" 
                               style="color: hsl(var(--white)/0.7); text-decoration: none; 
                                      transition: all 0.3s ease; display: inline-block;">
                                <i class="las la-angle-right me-2" style="color: hsl(var(--base));"></i>
                                @lang('Contact')
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Column 3: Recent Team Members -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h5 class="mb-4" 
                        style="color: hsl(var(--white)); font-size: 1.2rem; font-weight: 600; 
                               position: relative; padding-bottom: 10px;">
                        @lang('Our Team')
                        <span style="position: absolute; left: 0; bottom: 0; width: 50px; 
                                   height: 2px; background: linear-gradient(to right, 
                                   hsl(var(--base)) 0%, hsl(var(--base-400)) 100%);"></span>
                    </h5>
                    @if($teamMembers->count() > 0)
                    <ul class="list-unstyled">
                        @foreach($teamMembers as $member)
                        <li class="mb-3">
                            <a href="{{ route('team.member', ['id' => $member->id, 'slug' => Str::slug($member->name)]) }}" 
                               class="d-flex align-items-center text-decoration-none">
                                <div class="me-3" 
                                     style="border: 2px solid hsl(var(--white)/0.1); border-radius: 50%; 
                                            transition: all 0.3s ease;">
                                    <img src="{{ $member->image_url }}" 
                                         alt="{{ $member->name }}" 
                                         style="width: 40px; height: 40px; border-radius: 50%; 
                                                object-fit: cover; display: block;">
                                </div>
                                <div>
                                    <h6 style="color: hsl(var(--white)); margin-bottom: 0;">
                                        {{ $member->name }}
                                    </h6>
                                    <small style="color: hsl(var(--white)/0.7);">
                                        {{ $member->position }}
                                    </small>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p style="color: hsl(var(--white)/0.7);">@lang('No team members found')</p>
                    @endif
                </div>
            </div>

            <!-- Column 4: Contact & Newsletter -->
            <div class="col-lg-3 col-md-6">
                <div class="footer-widget">
                    <h5 class="mb-4" 
                        style="color: hsl(var(--white)); font-size: 1.2rem; font-weight: 600; 
                               position: relative; padding-bottom: 10px;">
                        @lang('Get In Touch')
                        <span style="position: absolute; left: 0; bottom: 0; width: 50px; 
                                   height: 2px; background: linear-gradient(to right, 
                                   hsl(var(--base)) 0%, hsl(var(--base-400)) 100%);"></span>
                    </h5>
                    
                    <ul class="list-unstyled">
                        @if(@$footer->data_values->address)
                        <li class="d-flex mb-3">
                            <i class="las la-map-marker me-3 mt-1" 
                               style="color: hsl(var(--base));"></i>
                            <span style="color: hsl(var(--white)/0.7);">
                                {{ @$footer->data_values->address }}
                            </span>
                        </li>
                        @endif
                        
                        @if(@$footer->data_values->phone)
                        <li class="d-flex mb-3">
                            <i class="las la-phone me-3 mt-1" 
                               style="color: hsl(var(--base));"></i>
                            <a href="tel:{{ @$footer->data_values->phone }}" 
                               style="color: hsl(var(--white)/0.7); text-decoration: none;">
                                {{ @$footer->data_values->phone }}
                            </a>
                        </li>
                        @endif
                        
                        @if(@$footer->data_values->email)
                        <li class="d-flex mb-3">
                            <i class="las la-envelope me-3 mt-1" 
                               style="color: hsl(var(--base));"></i>
                            <a href="mailto:{{ @$footer->data_values->email }}" 
                               style="color: hsl(var(--white)/0.7); text-decoration: none;">
                                {{ @$footer->data_values->email }}
                            </a>
                        </li>
                        @endif
                    </ul>

                    <!-- Newsletter Subscription -->
                    <div class="mt-4">
                        <h6 style="color: hsl(var(--white)); margin-bottom: 0.75rem;">
                            @lang('Subscribe to Newsletter')
                        </h6>
                        <form class="newsletter-form" id="newsletterForm" 
                              method="POST" action="{{ route('subscribe') }}">
                            @csrf
                            <div style="background-color: hsl(var(--white)/0.05); 
                                      border-radius: 30px; overflow: hidden;">
                                <div style="display: flex;">
                                    <input type="email" name="email" 
                                           style="flex: 1; border: 1px solid hsl(var(--white)/0.1); 
                                                  border-right: none; padding: 10px 20px; 
                                                  background-color: transparent; 
                                                  color: hsl(var(--white)); outline: none;"
                                           placeholder="@lang('Your email address')" required>
                                    <button type="submit" id="subscribeBtn"
                                            style="background: linear-gradient(to right, 
                                                   hsl(var(--base)) 0%, hsl(var(--base-400)) 100%); 
                                                   border: none; padding: 10px 20px; 
                                                   border-radius: 0 30px 30px 0; 
                                                   color: hsl(var(--white)); cursor: pointer;
                                                   transition: all 0.3s ease;">
                                        <i class="las la-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="newsletter-message mt-2"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="mt-4 pt-3" 
             style="border-top: 1px solid hsl(var(--white)/0.1);">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-md-0 text-center text-md-start" 
                       style="color: hsl(var(--white)/0.7);">
                        {{ gs('site_name') }} &copy; {{ date('Y') }}. @lang('All Rights Reserved')
                    </p>
                </div>
                <div class="col-md-6">
                    <ul style="display: flex; flex-wrap: wrap; gap: 1rem; 
                               justify-content: center; justify-content-md-end; 
                               list-style: none; margin: 0; padding: 0;">
                        @foreach($policyPages as $policyPage)
                        <li style="position: relative;">
                            <a href="{{ route('policy.pages', ['slug'=>slug($policyPage->data_values->title)]) }}" 
                               style="color: hsl(var(--white)/0.7); text-decoration: none; 
                                      font-size: 0.875rem; padding: 0 5px;">
                                {{ __(@$policyPage->data_values->title) }}
                            </a>
                            @if(!$loop->last)
                            <span style="position: absolute; right: -10px; 
                                       color: hsl(var(--white)/0.3);">|</span>
                            @endif
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
<style>
/* Keep keyframe animations separate as they can't be inline */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.la-spin {
    animation: spin 2s linear infinite;
}

/* Hover styles that can't be done inline */
.social-links a:hover {
    background: linear-gradient(to right, 
                hsl(var(--base)) 0%, 
                hsl(var(--base-400)) 100%) !important;
    transform: translateY(-3px);
    color: hsl(var(--white)) !important;
}

.footer-links li a:hover {
    transform: translateX(5px);
    color: hsl(var(--base)) !important;
}

.team-list .me-3:hover {
    border-color: hsl(var(--base)) !important;
    transform: scale(1.05);
}

.contact-info a:hover,
.footer-bottom-links li a:hover {
    color: hsl(var(--base)) !important;
}

.newsletter button:hover {
    opacity: 0.9;
    transform: scale(1.02);
}

.newsletter button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .footer-widget {
        text-align: center;
    }
    
    .footer-widget h5 span {
        left: 50% !important;
        transform: translateX(-50%);
    }
    
    .team-list .d-flex,
    .contact-info .d-flex,
    .social-links {
        justify-content: center !important;
    }
}

.newsletter-message .alert {
    margin-top: 10px;
    margin-bottom: 0;
    border-radius: 10px;
    border: none;
    color: hsl(var(--white));
}

.newsletter-message .alert-success {
    background-color: hsl(var(--success)/0.2);
    border-left: 3px solid hsl(var(--success));
    padding: 0.5rem;
}

.newsletter-message .alert-danger {
    background-color: hsl(var(--danger)/0.2);
    border-left: 3px solid hsl(var(--danger));
    padding: 0.5rem;
}

.newsletter-message .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    font-size: 0.8rem;
    float: right;
    background: transparent;
    border: 0;
    cursor: pointer;
}
</style>

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
                        messageDiv.html('<div class="alert alert-success alert-dismissible fade show small" role="alert">' + 
                            response.message + 
                            '<button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                        form[0].reset();
                    } else {
                        messageDiv.html('<div class="alert alert-danger alert-dismissible fade show small" role="alert">' + 
                            response.error + 
                            '<button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.error;
                        let errorMessage = Array.isArray(errors) ? errors[0] : 'Validation error';
                        messageDiv.html('<div class="alert alert-danger alert-dismissible fade show small" role="alert">' + 
                            errorMessage + 
                            '<button type="button" class="btn-close p-2" data-bs-dismiss="alert" aria-label="Close"></button>' +
                            '</div>');
                    } else {
                        messageDiv.html('<div class="alert alert-danger alert-dismissible fade show small" role="alert">' + 
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