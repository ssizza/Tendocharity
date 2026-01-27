@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Title')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Location')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Applicants')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                            <tr>
                                <td>
                                    <div class="user">
                                        @if($event->image)
                                        <div class="thumb">
                                            <img src="{{ $event->getImagePath() }}" alt="@lang('image')" style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>
                                        @endif
                                        <span class="name">{{ $event->title }}</span>
                                    </div>
                                </td>
                                <td>
                                    {{ showDateTime($event->startDate, 'd M Y') }}<br>
                                    <small>to {{ showDateTime($event->endDate, 'd M Y') }}</small>
                                </td>
                                <td>{{ $event->location }}</td>
                                <td>
                                    @if($event->type == 'virtual')
                                        <span class="badge badge--primary">@lang('Virtual')</span>
                                    @else
                                        <span class="badge badge--success">@lang('Physical')</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $status = $event->status;
                                        $badge = [
                                            'upcoming' => 'primary',
                                            'ongoing' => 'success',
                                            'completed' => 'info',
                                            'cancelled' => 'danger'
                                        ][$status];
                                    @endphp
                                    <span class="badge badge--{{ $badge }}">@lang(ucfirst($status))</span>
                                </td>
                                <td>
                                    <span class="badge badge--info">{{ $event->applicants_count }}</span>
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn--primary">
                                            <i class="las la-edit"></i> @lang('Edit')
                                        </a>
                                        <a href="{{ route('admin.events.applicants', ['eventId' => $event->id]) }}" class="btn btn-sm btn--info">
                                            <i class="las la-users"></i> 
                                        </a>
                                        <a href="{{ route('admin.events.gallery', ['eventId' => $event->id]) }}" class="btn btn-sm btn--dark">
                                            <i class="las la-images"></i> 
                                        </a>
                                        <button class="btn btn-sm btn--danger confirmationBtn" data-question="@lang('Are you sure to delete this event?')" data-action="{{ route('admin.events.delete', $event->id) }}">
                                            <i class="las la-trash"></i> 
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="7">@lang('No events found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($events->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($events) }}
            </div>
            @endif
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection