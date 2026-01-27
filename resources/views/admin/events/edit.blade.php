@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Event Title')</label>
                                <input type="text" class="form-control" name="title" value="{{ $event->title }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Event Image')</label>
                                <input type="file" class="form-control" name="image" accept=".png, .jpg, .jpeg">
                                <small class="form-text text-muted">@lang('Leave empty to keep current image')</small>
                                @if($event->image)
                                <div class="mt-2">
                                    <img src="{{ $event->getImagePath() }}" alt="Current Image" style="max-width: 200px; border-radius: 5px;">
                                    <p class="text-muted mt-1">@lang('Current Image')</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>@lang('Short Description')</label>
                                <textarea class="form-control" name="short_description" rows="3" placeholder="@lang('Brief summary of the event')" maxlength="500">{{ $description['short_description'] ?? '' }}</textarea>
                                <small class="form-text text-muted">@lang('Maximum 500 characters. This appears on event cards.')</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Start Date & Time')</label>
                                <input type="datetime-local" class="form-control" name="startDate" value="{{ $event->startDate->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('End Date & Time')</label>
                                <input type="datetime-local" class="form-control" name="endDate" value="{{ $event->endDate->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Location/Venue')</label>
                                <input type="text" class="form-control" name="location" value="{{ $event->location }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Event Type')</label>
                                        <select class="form-control" name="type" required>
                                            <option value="virtual" @if($event->type == 'virtual') selected @endif>@lang('Virtual')</option>
                                            <option value="physical" @if($event->type == 'physical') selected @endif>@lang('Physical')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Status')</label>
                                        <select class="form-control" name="status" required>
                                            <option value="upcoming" @if($event->status == 'upcoming') selected @endif>@lang('Upcoming')</option>
                                            <option value="ongoing" @if($event->status == 'ongoing') selected @endif>@lang('Ongoing')</option>
                                            <option value="completed" @if($event->status == 'completed') selected @endif>@lang('Completed')</option>
                                            <option value="cancelled" @if($event->status == 'cancelled') selected @endif>@lang('Cancelled')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>@lang('Full Description')</label>
                        <textarea class="form-control nicEdit" name="full_description" rows="10" placeholder="@lang('Detailed information about the event')">{{ $description['full_description'] ?? '' }}</textarea>
                        <small class="form-text text-muted">@lang('This appears on the event details page.')</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn--primary w-100">@lang('Update Event')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection