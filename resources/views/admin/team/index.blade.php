@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body px-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center px-3 pb-3">
                    <div class="filters d-flex flex-wrap gap-2">
                        <!-- Category Filter -->
                        <select name="category" class="select2-auto-trigger" data-placeholder="Filter by Category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request()->category == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Status Filter -->
                        <select name="status" class="select2-auto-trigger" data-placeholder="Filter by Status">
                            <option value="">All Status</option>
                            @foreach($statuses as $key => $status)
                                <option value="{{ $key }}" {{ request()->status == $key ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button" class="btn btn--primary btn-sm apply-filter">
                            <i class="las la-filter"></i> Apply
                        </button>
                        <a href="{{ route('admin.team.index') }}" class="btn btn--dark btn-sm">
                            <i class="las la-sync"></i> Reset
                        </a>
                    </div>
                    
                    <div>
                        <button type="button" class="btn btn-outline--success btn-sm bulk-action-btn" data-action="active">
                            <i class="las la-check-circle"></i> Activate Selected
                        </button>
                        <button type="button" class="btn btn-outline--warning btn-sm bulk-action-btn" data-action="inactive">
                            <i class="las la-ban"></i> Deactivate Selected
                        </button>
                        <button type="button" class="btn btn-outline--danger btn-sm bulk-action-btn" data-action="delete">
                            <i class="las la-trash"></i> Delete Selected
                        </button>
                    </div>
                </div>

                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input select-all" type="checkbox">
                                    </div>
                                </th>
                                <th>@lang('Image')</th>
                                <th>@lang('Name')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Position')</th>
                                <th>@lang('Email')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Joined')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $member)
                            <tr data-id="{{ $member->id }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input member-checkbox" type="checkbox" value="{{ $member->id }}">
                                    </div>
                                </td>
                                <td>
                                    <div class="avatar avatar--lg">
                                        <img src="{{ $member->image_url }}" alt="{{ $member->name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ $member->name }}</span>
                                </td>
                                <td>
                                    @if($member->category)
                                        <span class="badge badge--primary">{{ $member->category->name }}</span>
                                    @else
                                        <span class="badge badge--dark">Uncategorized</span>
                                    @endif
                                </td>
                                <td>{{ $member->position ?? 'N/A' }}</td>
                                <td>{{ $member->email ?? 'N/A' }}</td>
                                <td>
                                    {!! $member->status_badge !!}
                                </td>
                                <td>
                                    {{ showDateTime($member->created_at) }}<br>
                                    <span class="small">{{ diffForHumans($member->created_at) }}</span>
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.team.edit', $member->id) }}" 
                                           class="btn btn-sm btn-outline--primary">
                                            <i class="las la-edit"></i> @lang('Edit')
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline--danger delete-btn"
                                                data-id="{{ $member->id }}"
                                                data-name="{{ $member->name }}">
                                            <i class="las la-trash"></i> @lang('Delete')
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline--{{ $member->status == 'active' ? 'warning' : 'success' }} toggle-status"
                                                data-id="{{ $member->id }}"
                                                data-status="{{ $member->status }}">
                                            <i class="las la-{{ $member->status == 'active' ? 'ban' : 'check' }}"></i>
                                            @lang($member->status == 'active' ? 'Deactivate' : 'Activate')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">
                                    <div class="empty-state py-5">
                                        <i class="las la-users la-4x text-muted"></i>
                                        <h4 class="mt-3">@lang('No team members found')</h4>
                                        <p class="text-muted">@lang('Get started by adding your first team member.')</p>
                                        <a href="{{ route('admin.team.create') }}" class="btn btn--primary mt-3">
                                            <i class="las la-plus"></i> @lang('Add Team Member')
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($members->hasPages())
            <div class="card-footer py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="showing-text">
                        @lang('Showing') {{ $members->firstItem() }} @lang('to') {{ $members->lastItem() }} @lang('of') {{ $members->total() }} @lang('entries')
                    </div>
                    {{ paginateLinks($members) }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirm Deletion')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-0">@lang('Are you sure you want to delete this team member? This action cannot be undone.')</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn--dark" data-bs-dismiss="modal">
                    <i class="las la-times"></i> @lang('Cancel')
                </button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn--danger">
                        <i class="las la-trash"></i> @lang('Delete')
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<div class="d-flex flex-wrap gap-2 align-items-center">
    <x-search-form placeholder="Search by name, email or position" />
    
    <a href="{{ route('admin.team.categories.index') }}" class="btn btn-outline--info h-45">
        <i class="las la-tags"></i> @lang('Categories')
    </a>
    
    <a href="{{ route('admin.team.create') }}" class="btn btn-outline--primary h-45">
        <i class="las la-plus"></i> @lang('Add New Member')
    </a>
</div>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        // Handle filter application
        $('.apply-filter').on('click', function() {
            let category = $('select[name="category"]').val();
            let status = $('select[name="status"]').val();
            let search = $('input[name="search"]').val();
            
            let url = new URL(window.location.href);
            
            if (category) url.searchParams.set('category', category);
            else url.searchParams.delete('category');
            
            if (status) url.searchParams.set('status', status);
            else url.searchParams.delete('status');
            
            if (search) url.searchParams.set('search', search);
            
            window.location.href = url.toString();
        });

        // Select all checkbox
        $('.select-all').on('change', function() {
            $('.member-checkbox').prop('checked', $(this).prop('checked'));
        });

        // Individual checkbox change
        $(document).on('change', '.member-checkbox', function() {
            let allChecked = $('.member-checkbox:checked').length === $('.member-checkbox').length;
            $('.select-all').prop('checked', allChecked);
        });

        // Bulk action
        $('.bulk-action-btn').on('click', function() {
            let action = $(this).data('action');
            let selectedIds = [];
            
            $('.member-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                notify('error', 'Please select at least one member');
                return;
            }

            let message = '';
            if (action === 'delete') {
                message = 'Are you sure you want to delete the selected members?';
            } else if (action === 'active') {
                message = 'Are you sure you want to activate the selected members?';
            } else if (action === 'inactive') {
                message = 'Are you sure you want to deactivate the selected members?';
            }

            if (!confirm(message)) return;

            $.ajax({
                url: "{{ route('admin.team.bulk-action') }}",
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    action: action,
                    ids: selectedIds
                },
                success: function(response) {
                    if (response.success) {
                        notify('success', response.message);
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    notify('error', 'Something went wrong');
                }
            });
        });

        // Delete confirmation
        $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let url = "{{ route('admin.team.destroy', ':id') }}".replace(':id', id);
            
            $('#deleteForm').attr('action', url);
            $('#deleteModal .modal-body p').html(`Are you sure you want to delete <strong>${name}</strong>? This action cannot be undone.`);
            $('#deleteModal').modal('show');
        });

        // Toggle status
        $('.toggle-status').on('click', function() {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let action = status === 'active' ? 'deactivate' : 'activate';
            
            if (!confirm(`Are you sure you want to ${action} this member?`)) return;
            
            let url = "{{ route('admin.team.toggle-status', ':id') }}".replace(':id', id);
            
            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    notify('success', 'Status updated successfully');
                    window.location.reload();
                }
            });
        });

    })(jQuery);
</script>
@endpush