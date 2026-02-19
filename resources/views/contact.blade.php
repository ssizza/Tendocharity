@extends('layouts.frontend')

@php
    $contact = @getContent('contact_us.content', true);
@endphp

@section('content')
    <div class="contact-section pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="contact-form card custom--card border-0">
                        <div class="card-body">
                            <h3 class="title mb-2">{{ __(@$contact->data_values->heading) }}</h3>
                            <p class="text--body mb-4">{{ __(@$contact->data_values->description) }}</p>
                            
                            <form method="post" action="" class="verify-gcaptcha">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Name') <span class="text--danger">*</span></label>
                                            <input 
                                                name="name" 
                                                type="text" 
                                                class="form-control form--control" 
                                                value="{{ old('name', @$user->fullname) }}" 
                                                @if(@$user) readonly @endif 
                                                required
                                            >
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Email') <span class="text--danger">*</span></label>
                                            <input 
                                                name="email" 
                                                type="email" 
                                                class="form-control form--control" 
                                                value="{{ old('email',@$user->email) }}" 
                                                @if(@$user) readonly @endif 
                                                required
                                            >
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Subject') <span class="text--danger">*</span></label>
                                            <input 
                                                name="subject" 
                                                type="text" 
                                                class="form-control form--control" 
                                                value="{{ old('subject') }}" 
                                                required
                                            >
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form--label">@lang('Message') <span class="text--danger">*</span></label>
                                            <textarea 
                                                name="message" 
                                                wrap="off" 
                                                class="form-control form--control" 
                                                rows="5" 
                                                required
                                            >{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <x-captcha />
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn--base w-100">
                                            <i class="las la-paper-plane me-2"></i>@lang('Send Message')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include('sections.' . $sec)
        @endforeach
    @endif
@endsection

@push('style')
<style>
    /* Form label styling */
    .form--label {
        font-size: 0.9375rem;
        font-weight: 500;
        color: hsl(var(--heading));
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Form control enhancements */
    .form--control {
        height: 50px;
        border: 1px solid hsl(var(--border));
        border-radius: 0.375rem;
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
        background-color: hsl(var(--white));
        color: hsl(var(--body));
        width: 100%;
    }

    .form--control:focus {
        border-color: hsl(var(--base));
        outline: 0;
        box-shadow: 0 0 0 0.2rem hsl(var(--base)/0.1);
    }

    .form--control[readonly] {
        background-color: hsl(var(--light));
        cursor: not-allowed;
        opacity: 0.8;
    }

    textarea.form--control {
        height: auto;
        min-height: 120px;
        resize: vertical;
    }

    /* Contact form specific styling */
    .contact-form .custom--card {
        background: hsl(var(--white));
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1.5rem hsl(var(--dark)/0.05);
    }

    .contact-form .card-body {
        padding: 2.5rem;
    }

    @media (max-width: 767px) {
        .contact-form .card-body {
            padding: 1.5rem;
        }
    }

    .contact-form .title {
        color: hsl(var(--heading));
        font-size: 2rem;
        font-weight: 600;
        line-height: 1.2;
    }

    @media (max-width: 575px) {
        .contact-form .title {
            font-size: 1.5rem;
        }
    }

    /* Button enhancements */
    .btn--base {
        background-color: hsl(var(--base));
        border: 2px solid hsl(var(--base));
        color: hsl(var(--white));
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn--base:hover {
        background-color: hsl(var(--base-600));
        border-color: hsl(var(--base-600));
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem hsl(var(--base)/0.2);
    }

    .btn--base:active {
        transform: translateY(0);
    }

    /* Error states */
    .text--danger {
        color: hsl(var(--danger)) !important;
    }

    /* Captcha container spacing */
    .captcha-container {
        margin-top: 1rem;
    }

    /* Form group spacing */
    .form-group {
        margin-bottom: 0;
    }

    .g-4 > [class*="col-"] {
        margin-bottom: 0;
    }

    /* Responsive adjustments */
    @media (max-width: 575px) {
        .btn--base {
            padding: 0.625rem 1.25rem;
            font-size: 0.9375rem;
        }
    }
</style>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";
        
        // Form validation enhancement
        $('form.verify-gcaptcha').on('submit', function(e) {
            var form = $(this);
            var requiredInputs = form.find('[required]');
            var isValid = true;
            
            requiredInputs.each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                toastNotify('error', 'Please fill in all required fields');
            }
        });
        
        // Remove error class on input
        $('.form--control').on('input change', function() {
            $(this).removeClass('is-invalid');
        });
        
    })(jQuery);
</script>
@endpush