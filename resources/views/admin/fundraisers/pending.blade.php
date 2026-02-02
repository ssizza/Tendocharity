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
                                <th>@lang('Urgency')</th>
                                <th>@lang('Created By')</th>
                                <th>@lang('Target Amount')</th>
                                <th>@lang('Created At')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fundraisers as $fundraiser)
                            <tr>
                                <td>
                                    <div class="user">
                                        @if($fundraiser->featured_image)
                                        <div class="thumb">
                                            <img src="{{ asset($fundraiser->featured_image) }}" 
                                                 alt="@lang('image')" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                        </div>
                                        @endif
                                        <span class="name">{{ $fundraiser->title }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $urgencyBadge = $fundraiser->urgency_level == 'normal' ? 'info' : 
                                                       ($fundraiser->urgency_level == 'urgent' ? 'warning' : 'danger');
                                    @endphp
                                    <span class="badge badge--{{ $urgencyBadge }}">@lang(ucfirst($fundraiser->urgency_level))</span>
                                </td>
                                <td>
                                    {{ $fundraiser->createdBy->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $fundraiser->currency }} {{ number_format($fundraiser->target_amount, 2) }}
                                </td>
                                <td>
                                    {{ showDateTime($fundraiser->created_at) }}<br>
                                    {{ diffForHumans($fundraiser->created_at) }}
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.fundraisers.edit', $fundraiser->id) }}" 
                                           class="btn btn-sm btn--primary">
                                            <i class="las la-eye"></i> @lang('View')
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn--success approveBtn" 
                                                data-id="{{ $fundraiser->id }}">
                                            <i class="las la-check-circle"></i> @lang('Approve')
                                        </button>
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn--danger rejectBtn" 
                                                data-id="{{ $fundraiser->id }}">
                                            <i class="las la-times-circle"></i> @lang('Reject')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="6">@lang('No pending causes found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($fundraisers->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($fundraisers) }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $('.approveBtn').on('click', function () {
            var id = $(this).data('id');
            var url = '{{ route("admin.fundraisers.approve", ":id") }}'.replace(':id', id);
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });
        
        $('.rejectBtn').on('click', function () {
            var id = $(this).data('id');
            var url = '{{ route("admin.fundraisers.reject", ":id") }}'.replace(':id', id);
            
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                }
            });
        });
        
    })(jQuery);
</script>
@endpush