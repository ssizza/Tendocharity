@extends('layouts.frontend')

@section('content')
<div class="container pt-60 pb-60">
    <div class="row gy-4">
        @foreach($services as $service)
        <div class="col-md-6 col-lg-4">
            <div class="custom--card h-100">
                <div class="position-relative">
                    <img src="{{ $service->image_url }}" class="w-100" alt="{{ $service->title }}" style="height: 200px; object-fit: cover; border-radius: 5px 5px 0 0;">
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge badge--base">{{ $service->campaigns_count }} @lang('Campaigns')</span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ __($service->title) }}</h5>
                    <p class="card-text text--body">{{ Str::limit($service->mission, 100) }}</p>
                    <div class="mb-3">
                        <small class="text--base">
                            <i class="las la-bullseye me-1"></i> {{ Str::limit($service->vision, 80) }}
                        </small>
                    </div>
                    <a href="{{ route('service.details', $service->slug) }}" class="cmn--btn btn--outline w-100 text-center">
                        @lang('Learn More') <i class="las la-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection