{{-- resources/views/admin/fundraisers/categories/edit.blade.php --}}
@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-body">
                <form action="{{ route('admin.fundraisers.categories.update', $category->id) }}" 
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>@lang('Name') <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" 
                                       value="{{ old('name', $category->name) }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label>@lang('Service')</label>
                                <select name="service_id" class="form-control select2">
                                    <option value="">@lang('Select Service')</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" 
                                                @selected(old('service_id', $category->service_id) == $service->id)>
                                            {{ $service->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>@lang('Description')</label>
                                <textarea name="description" class="form-control nicEdit" rows="5">{{ old('description', $category->description) }}</textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Status') <span class="text-danger">*</span></label>
                                        <select name="status" class="form-control" required>
                                            <option value="active" @selected(old('status', $category->status) == 'active')>@lang('Active')</option>
                                            <option value="inactive" @selected(old('status', $category->status) == 'inactive')>@lang('Inactive')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Sort Order')</label>
                                        <input type="number" name="sort_order" class="form-control" 
                                               value="{{ old('sort_order', $category->sort_order) }}" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <!-- Image Upload -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">@lang('Category Image')</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview" 
                                                         style="background-image: url({{ $category->image ? asset($category->image) : asset('assets/images/default.png') }})">
                                                    </div>
                                                </div>
                                                <div class="avatar-edit">
                                                    <input type="file" class="profilePicUpload" 
                                                           name="image" id="image" accept=".png, .jpg, .jpeg, .gif">
                                                    <label for="image">@lang('Upload Image')</label>
                                                </div>
                                            </div>
                                            @if($category->image)
                                            <small class="text-success">@lang('Current image uploaded')</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- SEO Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title">@lang('SEO Information')</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>@lang('Meta Title')</label>
                                        <input type="text" name="meta_title" class="form-control" 
                                               value="{{ old('meta_title', $category->meta_title) }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>@lang('Meta Description')</label>
                                        <textarea name="meta_description" class="form-control" 
                                                  rows="3">{{ old('meta_description', $category->meta_description) }}</textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>@lang('Meta Keywords')</label>
                                        <input type="text" name="meta_keywords" class="form-control" 
                                               value="{{ old('meta_keywords', $category->meta_keywords) }}">
                                        <small class="text-muted">@lang('Separate keywords with commas')</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn--primary w-100">
                                @lang('Update Category')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    (function ($) {
        "use strict";
        
        // Image preview
        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = $(input).closest('.image-upload').find('.profilePicPreview');
                    preview.css('background-image', 'url(' + e.target.result + ')');
                    preview.addClass('has-image');
                    preview.hide();
                    preview.fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        $("body").on('change', '.profilePicUpload', function() {
            proPicURL(this);
        });
        
    })(jQuery);
</script>
@endpush