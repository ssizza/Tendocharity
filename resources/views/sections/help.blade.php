@extends('layouts.frontend')

@php
    $help = @getContent('help.content', null, true)->first();
@endphp

@section('content')
    <div class="contact-section pt-60 pb-60 bg--light">
        <div class="container">
            
            @if($help)
            <!-- Contact Header -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <h2 class="section-title mb-3">{{ __($help->data_values->heading ?? 'Get in Touch') }}</h2>
                    <p class="section-description">{{ __($help->data_values->description ?? 'Please do not hesitate to contact our experts') }}</p>
                </div>
            </div>
            
            <!-- Contact Cards -->
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="pb-60">
                        <div class="row gy-3 justify-content-center">
                            <div class="col-md-4 col-sm-6 col-xsm-6">
                                <div class="contact-card">
                                    <div class="contact-card__icon"><i class="las la-envelope"></i> </div>
                                    <div class="contact-card__content">
                                        <h5 class="contact-card__title">@lang('Email')</h5>
                                        <p class="contact-card__desc">{{ $help->data_values->email }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xsm-6">
                                <div class="contact-card">
                                    <div class="contact-card__icon"><i class="las la-phone"></i> </div>
                                    <div class="contact-card__content">
                                        <h5 class="contact-card__title">@lang('Phone')</h5>
                                        <p class="contact-card__desc">{{ $help->data_values->phone }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xsm-6">
                                <div class="contact-card">
                                    <div class="contact-card__icon"><i class="las la-map-marker"></i> </div>
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