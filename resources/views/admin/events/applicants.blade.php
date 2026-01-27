@extends('admin.layouts.app')
@section('panel')
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.events.applicants') }}" method="GET">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>@lang('Filter by Event')</label>
                                <select class="form-control" name="eventId" onchange="this.form.submit()">
                                    <option value="">@lang('All Events')</option>
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
                                <label>&nbsp;</label>
                                <a href="{{ route('admin.events.applicants') }}" class="btn btn--secondary w-100">@lang('Clear Filter')</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Applicant')</th>
                                <th>@lang('Event')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Phone')</th>
                                <th>@lang('Applied At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($applicants as $applicant)
                            <tr>
                                <td>{{ $applicant->name }}</td>
                                <td>
                                    @if($applicant->event)
                                        {{ $applicant->event->title }}
                                    @else
                                        <span class="text-muted">@lang('N/A')</span>
                                    @endif
                                </td>
                                <td>{{ $applicant->email }}</td>
                                <td>{{ $applicant->phone ?? 'N/A' }}</td>
                                <td>{{ showDateTime($applicant->createdAt) }}</td>
                                <td>
                                    <button class="btn btn-sm btn--danger confirmationBtn" 
                                            data-question="@lang('Are you sure to remove this applicant?')" 
                                            data-action="{{ route('admin.events.applicants.delete', $applicant->id) }}">
                                        <i class="las la-trash"></i> @lang('Remove')
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="6">@lang('No applicants found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($applicants->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($applicants) }}
            </div>
            @endif
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection