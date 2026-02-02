{{-- resources/views/admin/fundraisers/categories/index.blade.php --}}
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
                                <th>@lang('Name')</th>
                                <th>@lang('Service')</th>
                                <th>@lang('Fundraisers')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Order')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>
                                    <div class="user">
                                        @if($category->image)
                                        <div class="thumb">
                                            <img src="{{ asset($category->image) }}" 
                                                 alt="@lang('image')" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('assets/images/default.png') }}'">
                                        </div>
                                        @endif
                                        <span class="name">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $category->service->title ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge badge--info">{{ $category->fundraisers->count() }}</span>
                                </td>
                                <td>
                                    @php
                                        $badge = $category->status == 'active' ? 'success' : 'warning';
                                    @endphp
                                    <span class="badge badge--{{ $badge }}">@lang(ucfirst($category->status))</span>
                                </td>
                                <td>{{ $category->sort_order }}</td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.fundraisers.categories.edit', $category->id) }}" 
                                           class="btn btn-sm btn--primary">
                                            <i class="las la-edit"></i> @lang('Edit')
                                        </a>
                                        
                                        @if($category->status == 'active')
                                            <button type="button" 
                                                    class="btn btn-sm btn--warning updateStatusBtn" 
                                                    data-id="{{ $category->id }}" 
                                                    data-status="inactive">
                                                <i class="las la-ban"></i> @lang('Inactive')
                                            </button>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-sm btn--success updateStatusBtn" 
                                                    data-id="{{ $category->id }}" 
                                                    data-status="active">
                                                <i class="las la-check-circle"></i> @lang('Active')
                                            </button>
                                        @endif
                                        
                                        <button class="btn btn-sm btn--danger confirmationBtn" 
                                                data-question="@lang('Are you sure to delete this category?')" 
                                                data-action="{{ route('admin.fundraisers.categories.delete', $category->id) }}">
                                            <i class="las la-trash"></i> 
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="6">@lang('No categories found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($categories->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($categories) }}
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
                <h5 class="modal-title">@lang('Update Category Status')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.fundraisers.categories.status', ':id') }}" method="POST" id="statusForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('Status')</label>
                        <select name="status" class="form-control" required>
                            <option value="active">@lang('Active')</option>
                            <option value="inactive">@lang('Inactive')</option>
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
            
            var actionUrl = '{{ route("admin.fundraisers.categories.status", ":id") }}'.replace(':id', id);
            $('#statusForm').attr('action', actionUrl);
            modal.find('select[name="status"]').val(status);
            modal.modal('show');
        });
        
    })(jQuery);
</script>
@endpush