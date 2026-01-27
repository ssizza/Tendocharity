@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">@lang('Add New Image to Gallery')</h5>
                <form action="{{ route('admin.events.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Select Event')</label>
                                <select class="form-control" name="eventId" required>
                                    <option value="">@lang('Select One')</option>
                                    @foreach($events as $event)
                                    <option value="{{ $event->id }}" @if(request('eventId') == $event->id) selected @endif>
                                        {{ $event->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Image')</label>
                                <input type="file" class="form-control" name="image" accept=".png, .jpg, .jpeg" required>
                                <small class="form-text text-muted">@lang('Max size: 2MB')</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Alt Text')</label>
                                <input type="text" class="form-control" name="alt" placeholder="@lang('Image description')">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn--primary">@lang('Add to Gallery')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    @forelse($gallery as $image)
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <img src="{{ $image->getImagePath() }}" 
                     class="img-fluid rounded" 
                     alt="{{ $image->alt ?? 'Gallery Image' }}"
                     style="max-height: 200px; width: 100%; object-fit: cover;">
                <div class="mt-3">
                    <p class="mb-1"><strong>@lang('Event'):</strong> {{ $image->event->title ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>@lang('Alt Text'):</strong> {{ $image->alt ?? 'N/A' }}</p>
                    <p class="mb-3"><small class="text-muted">@lang('Added'): {{ showDateTime($image->created_at) }}</small></p>
                    <button class="btn btn-sm btn--danger btn-block confirmationBtn"
                            data-question="@lang('Are you sure to delete this image?')"
                            data-action="{{ route('admin.events.gallery.delete', $image->id) }}">
                        <i class="las la-trash"></i> @lang('Delete')
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="alert alert-warning text-center">
            @lang('No images found in gallery')
        </div>
    </div>
    @endforelse
</div>

@if($gallery->hasPages())
<div class="row mt-4">
    <div class="col-12">
        {{ paginateLinks($gallery) }}
    </div>
</div>
@endif

<x-confirmation-modal />
@endsection