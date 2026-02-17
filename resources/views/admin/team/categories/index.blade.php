@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive--md table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('Name')</th>
                                <th>@lang('Slug')</th>
                                <th>@lang('Description')</th>
                                <th>@lang('Members')</th>
                                <th>@lang('Order')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $category->name }}</span>
                                </td>
                                <td>
                                    <code>{{ $category->slug }}</code>
                                </td>
                                <td>
                                    {{ Str::limit($category->description, 50) ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="badge badge--primary">{{ $category->members_count }}</span>
                                </td>
                                <td>
                                    <span class="badge badge--dark">{{ $category->sort_order ?? 0 }}</span>
                                </td>
                                <td>
                                    @if($category->status)
                                        <span class="badge badge--success">@lang('Active')</span>
                                    @else
                                        <span class="badge badge--danger">@lang('Inactive')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        <button type="button" 
                                                class="btn btn-sm btn-outline--primary edit-btn"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}"
                                                data-description="{{ $category->description }}"
                                                data-status="{{ $category->status }}"
                                                data-order="{{ $category->sort_order }}">
                                            <i class="las la-edit"></i> @lang('Edit')
                                        </button>
                                        
                                        @if($category->members_count == 0)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline--danger delete-btn"
                                                data-id="{{ $category->id }}"
                                                data-name="{{ $category->name }}">
                                            <i class="las la-trash"></i> @lang('Delete')
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">
                                    <div class="empty-state py-5">
                                        <i class="las la-tags la-4x text-muted"></i>
                                        <h4 class="mt-3">@lang('No categories found')</h4>
                                        <p class="text-muted">@lang('Create your first team category to organize members.')</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($categories->hasPages())
            <div class="card-footer py-3">
                {{ paginateLinks($categories) }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Add New Category')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="categoryForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="required">@lang('Category Name')</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>@lang('Description')</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('Sort Order')</label>
                                <input type="number" name="sort_order" class="form-control" value="0" min="0">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">@lang('Status')</label>
                                <select name="status" class="form-control">
                                    <option value="1">@lang('Active')</option>
                                    <option value="0">@lang('Inactive')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-bs-dismiss="modal">
                        <i class="las la-times"></i> @lang('Cancel')
                    </button>
                    <button type="submit" class="btn btn--primary">
                        <i class="las la-save"></i> @lang('Save Category')
                    </button>
                </div>
            </form>
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
                <p>@lang('Are you sure you want to delete this category? This action cannot be undone.')</p>
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
    <x-search-form placeholder="Search categories" />
    
    <button type="button" class="btn btn-outline--primary h-45" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i class="las la-plus"></i> @lang('Add New Category')
    </button>
    
    <a href="{{ route('admin.team.index') }}" class="btn btn-outline--info h-45">
        <i class="las la-users"></i> @lang('Team Members')
    </a>
</div>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        // Reset modal on open
        $('#categoryModal').on('show.bs.modal', function(e) {
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let modal = $(this);
            
            if (id) {
                // Edit mode
                modal.find('.modal-title').text('@lang('Edit Category')');
                modal.find('form').attr('action', "{{ route('admin.team.categories.update', '') }}/" + id);
                modal.find('form').append('<input type="hidden" name="_method" value="PUT">');
                modal.find('input[name="name"]').val(button.data('name'));
                modal.find('textarea[name="description"]').val(button.data('description'));
                modal.find('input[name="sort_order"]').val(button.data('order'));
                modal.find('select[name="status"]').val(button.data('status'));
            } else {
                // Add mode
                modal.find('.modal-title').text('@lang('Add New Category')');
                modal.find('form').attr('action', "{{ route('admin.team.categories.store') }}");
                modal.find('form').find('input[name="_method"]').remove();
                modal.find('form')[0].reset();
                modal.find('select[name="status"]').val(1);
            }
        });

        // Clear modal on hide
        $('#categoryModal').on('hide.bs.modal', function() {
            $(this).find('form')[0].reset();
            $(this).find('form').find('input[name="_method"]').remove();
        });

        // Form submission
        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();
            
            let form = $(this);
            let url = form.attr('action');
            let data = form.serialize();
            
            $.ajax({
                url: url,
                method: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        notify('success', response.message);
                        $('#categoryModal').modal('hide');
                        window.location.reload();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            notify('error', value[0]);
                        });
                    } else {
                        notify('error', 'Something went wrong');
                    }
                }
            });
        });

        // Edit button click
        $('.edit-btn').on('click', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let description = $(this).data('description');
            let status = $(this).data('status');
            let order = $(this).data('order');
            
            $('#categoryModal').modal('show');
            $('#categoryModal').find('.modal-title').text('@lang('Edit Category')');
            $('#categoryModal').find('form').attr('action', "{{ route('admin.team.categories.update', '') }}/" + id);
            $('#categoryModal').find('form').append('<input type="hidden" name="_method" value="PUT">');
            $('#categoryModal').find('input[name="name"]').val(name);
            $('#categoryModal').find('textarea[name="description"]').val(description);
            $('#categoryModal').find('input[name="sort_order"]').val(order);
            $('#categoryModal').find('select[name="status"]').val(status ? 1 : 0);
        });

        // Delete confirmation
        $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let url = "{{ route('admin.team.categories.destroy', '') }}/" + id;
            
            $('#deleteForm').attr('action', url);
            $('#deleteModal .modal-body p').html(`Are you sure you want to delete <strong>${name}</strong>?`);
            $('#deleteModal').modal('show');
        });

    })(jQuery);
</script>
@endpush