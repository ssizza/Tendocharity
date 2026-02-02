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
                                <th>@lang('Service/Category')</th>
                                <th>@lang('Target/Raised')</th>
                                <th>@lang('Progress')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Featured')</th>
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
                                        <br>
                                        <small class="text--muted">{{ Str::limit($fundraiser->short_description, 50) }}</small>
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
                                    <div>
                                        <strong>Service:</strong> {{ $fundraiser->service->title ?? 'N/A' }}<br>
                                        <strong>Category:</strong> {{ $fundraiser->category->name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>Target:</strong> {{ $fundraiser->currency }} {{ number_format($fundraiser->target_amount, 2) }}<br>
                                        <strong>Raised:</strong> {{ $fundraiser->currency }} {{ number_format($fundraiser->raised_amount, 2) }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $progress = $fundraiser->progress;
                                        $progressColor = $progress >= 100 ? 'success' : ($progress >= 50 ? 'primary' : 'warning');
                                    @endphp
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg--{{ $progressColor }}" 
                                             role="progressbar" 
                                             style="width: {{ $progress }}%;" 
                                             aria-valuenow="{{ $progress }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="mt-1 d-block">{{ $progress }}%</small>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'draft' => 'secondary',
                                            'pending' => 'warning',
                                            'active' => 'success',
                                            'completed' => 'info',
                                            'cancelled' => 'danger',
                                            'rejected' => 'dark'
                                        ];
                                    @endphp
                                    <span class="badge badge--{{ $statusColors[$fundraiser->status] ?? 'secondary' }}">
                                        @lang(ucfirst($fundraiser->status))
                                    </span>
                                </td>
                                <td>
                                    @if($fundraiser->is_featured)
                                        <span class="badge badge--success">@lang('Yes')</span>
                                    @else
                                        <span class="badge badge--secondary">@lang('No')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.fundraisers.edit', $fundraiser->id) }}" 
                                           class="btn btn-sm btn--primary">
                                            <i class="las la-edit"></i> @lang('Edit')
                                        </a>
                                        
                                        @if($fundraiser->is_featured)
                                            <button type="button" 
                                                    class="btn btn-sm btn--warning toggleFeaturedBtn" 
                                                    data-id="{{ $fundraiser->id }}">
                                                <i class="las la-star"></i> @lang('Unfeature')
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-sm btn--success toggleFeaturedBtn" 
                                                    data-id="{{ $fundraiser->id }}">
                                                <i class="las la-star"></i> @lang('Feature')
                                            </button>
                                        @endif
                                        
                                        <button type="button" 
                                                class="btn btn-sm btn--info updateStatusBtn" 
                                                data-id="{{ $fundraiser->id }}" 
                                                data-status="{{ $fundraiser->status }}">
                                            <i class="las la-sync"></i> @lang('Status')
                                        </button>
                                        
                                        <button class="btn btn-sm btn--danger confirmationBtn" 
                                                data-question="@lang('Are you sure to delete this cause?')" 
                                                data-action="{{ route('admin.fundraisers.delete', $fundraiser->id) }}">
                                            <i class="las la-trash"></i> 
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="8">@lang('No causes found')</td>
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

<div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Update Cause Status')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Status')</label>
                        <select name="status" class="form-control" required>
                            <option value="draft">@lang('Draft')</option>
                            <option value="pending">@lang('Pending')</option>
                            <option value="active">@lang('Active')</option>
                            <option value="completed">@lang('Completed')</option>
                            <option value="cancelled">@lang('Cancelled')</option>
                            <option value="rejected">@lang('Rejected')</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn--primary">@lang('Update')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<x-confirmation-modal />
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        
        $('.updateStatusBtn').on('click', function () {
            var modal = $('#statusModal');
            var id = $(this).data('id');
            var status = $(this).data('status');
            
            var actionUrl = '{{ route("admin.fundraisers.status", ":id") }}'.replace(':id', id);
            modal.find('form').attr('action', actionUrl);
            modal.find('select[name="status"]').val(status);
            modal.modal('show');
        });
        
        $('.toggleFeaturedBtn').on('click', function () {
            var id = $(this).data('id');
            var url = '{{ route("admin.fundraisers.toggle.featured", ":id") }}'.replace(':id', id);
            
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