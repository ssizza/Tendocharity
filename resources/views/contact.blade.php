@extends('layouts.frontend')

@php
    $contact = @getContent('contact_us.content', true);
@endphp

@section('content')
    <div class="contact-section pt-60 pb-60 bg--light">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="contact-form card custom--card border-0">
                        <div class="card-body">
                            <h3 class="title mb-2">{{ __(@$contact->data_values->heading) }}</h3>
                            <p class="mb-3">{{ __(@$contact->data_values->description) }}</p>
                            <form method="post" action="" class="verify-gcaptcha contact-form">
                                @csrf
                                <div class="row gy-4">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Name') <span class="text--danger">*</span></label>
                                            <input name="name" type="text" class="form-control form--control h-45" value="{{ old('name', @$user->fullname) }}" @if(@$user) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>@lang('Email') <span class="text--danger">*</span></label>
                                            <input name="email" type="email" class="form-control form--control h-45" value="{{ old('email',@$user->email) }}" @if(@$user) readonly @endif required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>@lang('Subject') <span class="text--danger">*</span></label>
                                            <input name="subject" type="text" class="form-control form--control h-45" value="{{ old('subject') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>@lang('Message') <span class="text--danger">*</span></label>
                                            <textarea name="message" wrap="off" class="form-control form--control" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>

                                    <x-captcha />

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
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
