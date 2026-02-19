@extends('layouts.frontend')

@php
    $help = @getContent('help.content', null, true)->first();
@endphp

@section('content')
    <div class="contact-section pt-120 pb-120">
        <div class="container">
            
            @if($help)
            <!-- Contact Header -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title">{{ __($help->data_values->heading ?? 'Get in Touch') }}</h2>
                    <p class="section-description mt-2">{{ __($help->data_values->description ?? 'Please do not hesitate to contact our experts') }}</p>
                </div>
            </div>
            
            <!-- Contact Cards -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="contact-card-wrapper">
                        <div class="row gy-4 justify-content-center">
                            <div class="col-md-4 col-sm-6 col-xsm-6">
                                <div class="contact-card">
                                    <div class="contact-card__icon">
                                        <i class="las la-envelope"></i>
                                    </div>
                                    <div class="contact-card__content">
                                        <h5 class="contact-card__title">@lang('Email')</h5>
                                        <p class="contact-card__desc">{{ $help->data_values->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xsm-6">
                                <div class="contact-card">
                                    <div class="contact-card__icon">
                                        <i class="las la-phone"></i>
                                    </div>
                                    <div class="contact-card__content">
                                        <h5 class="contact-card__title">@lang('Phone')</h5>
                                        <p class="contact-card__desc">{{ $help->data_values->phone }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xsm-6">
                                <div class="contact-card">
                                    <div class="contact-card__icon">
                                        <i class="las la-map-marker"></i>
                                    </div>
                                    <div class="contact-card__content">
                                        <h5 class="contact-card__title">@lang('Address')</h5>
                                        <p class="contact-card__desc">{{ __($help->data_values->address) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
        </div>
    </div>
@endsection

@push('style')
<style>
/* Additional contact card styles if needed - using CSS variables */
.contact-card-wrapper {
    margin-top: 30px;
}

/* Ensure contact cards use theme variables */
.contact-card {
    background-color: hsl(var(--white));
    border-radius: 8px;
    padding: 30px 20px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    box-shadow: 0 5px 20px hsl(var(--base)/0.05);
    border: 1px solid hsl(var(--border));
}

.contact-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px hsl(var(--base)/0.1);
}

.contact-card__icon {
    width: 70px;
    height: 70px;
    line-height: 70px;
    text-align: center;
    border-radius: 50%;
    background-color: hsl(var(--base)/0.1);
    color: hsl(var(--base));
    font-size: 30px;
    margin: 0 auto 20px;
    transition: all 0.3s ease;
}

.contact-card:hover .contact-card__icon {
    background-color: hsl(var(--base));
    color: hsl(var(--white));
}

.contact-card__title {
    margin-bottom: 10px;
    color: hsl(var(--heading));
    font-size: 18px;
    font-weight: 600;
}

.contact-card__desc {
    color: hsl(var(--body));
    font-size: 14px;
    word-break: break-word;
    margin-bottom: 0;
}

.section-title {
    color: hsl(var(--heading));
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 15px;
    position: relative;
    padding-bottom: 15px;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: hsl(var(--base));
}

.section-description {
    color: hsl(var(--body));
    font-size: 16px;
    max-width: 700px;
    margin: 0 auto;
}

@media (max-width: 767px) {
    .section-title {
        font-size: 28px;
    }
    
    .contact-card {
        padding: 25px 15px;
    }
    
    .contact-card__icon {
        width: 60px;
        height: 60px;
        line-height: 60px;
        font-size: 26px;
    }
}

@media (max-width: 575px) {
    .col-xsm-6 {
        width: 50%;
    }
}
</style>
@endpush