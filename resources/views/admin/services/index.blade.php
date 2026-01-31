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
                                <th>@lang('Mission')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Campaigns')</th>
                                <th>@lang('Stories')</th>
                                <th>@lang('Order')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                            <tr>
                                <td>
                                    <div class="user">
                                        @if($service->featured_image)
                                        <div class="thumb">
                                            <img src="{{ asset($service->featured_image) }}" 
                                                 alt="@lang('image')" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                        </div>
                                        @endif
                                        <span class="name">{{ $service->title }}</span>
                                    </div>
                                </td>
                                <td>{{ Str::limit($service->mission, 50) }}</td>
                                <td>
                                    @php
                                        $status = $service->status;
                                        $badge = [
                                            'active' => 'success',
                                            'inactive' => 'warning',
                                            'draft' => 'secondary'
                                        ][$status];
                                    @endphp
                                    <span class="badge badge--{{ $badge }}">@lang(ucfirst($status))</span>
                                </td>
                                <td>
                                    <span class="badge badge--info">{{ $service->campaigns_count }}</span>
                                </td>
                                <td>
                                    <span class="badge badge--primary">{{ $service->stories_count }}</span>
                                </td>
                                <td>{{ $service->sort_order }}</td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.services.edit', $service->id) }}" 
                                           class="btn btn-sm btn--primary">
                                            <i class="las la-edit"></i> @lang('Edit')
                                        </a>
                                        
                                        @if($service->status == 'active')
                                            <button type="button" 
                                                    class="btn btn-sm btn--warning updateStatusBtn" 
                                                    data-id="{{ $service->id }}" 
                                                    data-status="inactive">
                                                <i class="las la-ban"></i> @lang('Inactive')
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-sm btn--success updateStatusBtn" 
                                                    data-id="{{ $service->id }}" 
                                                    data-status="active">
                                                <i class="las la-check-circle"></i> @lang('Active')
                                            </button>
                                        @endif
                                        
                                        <button class="btn btn-sm btn--danger confirmationBtn" 
                                                data-question="@lang('Are you sure to delete this service?')" 
                                                data-action="{{ route('admin.services.delete', $service->id) }}">
                                            <i class="las la-trash"></i> 
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="7">@lang('No services found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($services->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($services) }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Status Update Modal --}}
<div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Update Service Status')</h5>
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
                            <option value="active">@lang('Active')</option>
                            <option value="inactive">@lang('Inactive')</option>
                            <option value="draft">@lang('Draft')</option>
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
            
            // FIXED: Use proper route with parameter
            var actionUrl = '{{ route("admin.services.status", ":id") }}'.replace(':id', id);
            modal.find('form').attr('action', actionUrl);
            modal.find('select[name="status"]').val(status);
            modal.modal('show');
        });
        
    })(jQuery);
</script>
@endpush