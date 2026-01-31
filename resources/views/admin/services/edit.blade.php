@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.services.update', $service->id) }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>@lang('Title')</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="title" 
                                       value="{{ old('title', $service->title) }}" 
                                       required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Mission')</label>
                                        <textarea class="form-control nicEdit" 
                                                  name="mission" 
                                                  rows="4" 
                                                  required>{{ old('mission', $service->mission) }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Vision')</label>
                                        <textarea class="form-control nicEdit" 
                                                  name="vision" 
                                                  rows="4" 
                                                  required>{{ old('vision', $service->vision) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>@lang('Description')</label>
                                <textarea class="form-control nicEdit" 
                                          name="description" 
                                          rows="8">{{ old('description', $service->description) }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>@lang('Impact Summary')</label>
                                <textarea class="form-control" 
                                          name="impact_summary" 
                                          rows="3">{{ old('impact_summary', $service->impact_summary) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card border--primary">
                                <div class="card-header bg--primary">
                                    <h5 class="text-white">@lang('Settings')</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>@lang('Status')</label>
                                        <select name="status" class="form-control" required>
                                            <option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>@lang('Active')</option>
                                            <option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>@lang('Inactive')</option>
                                            <option value="draft" {{ old('status', $service->status) == 'draft' ? 'selected' : '' }}>@lang('Draft')</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>@lang('Sort Order')</label>
                                        <input type="number" 
                                               class="form-control" 
                                               name="sort_order" 
                                               value="{{ old('sort_order', $service->sort_order) }}" 
                                               min="0">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>@lang('Featured Image')</label>
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    @if($service->featured_image)
                                                    <div class="profilePicPreview" 
                                                         style="background-image: url('{{ getImage(getFilePath('service') . '/' . $service->featured_image) }}')">
                                                    </div>
                                                    @else
                                                    <div class="profilePicPreview" 
                                                         style="background-image: url('{{ asset('assets/images/default.png') }}')">
                                                    </div>
                                                    @endif
                                                    <div class="avatar-edit">
                                                        <input type="file" 
                                                               class="profilePicUpload" 
                                                               name="featured_image" 
                                                               id="featured_image" 
                                                               accept=".png, .jpg, .jpeg">
                                                        <label for="featured_image" class="bg--primary"><i class="la la-pencil"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($service->featured_image)
                                            <div class="mt-2">
                                                <small class="text--info">
                                                    <i class="las la-info-circle"></i> 
                                                    @lang('Current image will be replaced if you upload a new one.')
                                                </small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>@lang('Gallery Images')</label>
                                        <input type="file" 
                                               class="form-control" 
                                               name="gallery_images[]" 
                                               multiple 
                                               accept=".png, .jpg, .jpeg">
                                        @if($service->gallery_images)
                                        <div class="mt-2">
                                            <small class="text--info">
                                                <i class="las la-info-circle"></i> 
                                                @lang('Current images will be kept. New images will be added to the gallery.')
                                            </small>
                                            <div class="mt-1">
                                                <small>
                                                    <strong>@lang('Current Gallery:')</strong> 
                                                    {{ count(json_decode($service->gallery_images)) }} @lang('images')
                                                </small>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-lg-12">
                            <div class="card border--dark">
                                <div class="card-header bg--dark">
                                    <h5 class="text-white">@lang('SEO Settings')</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Meta Title')</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="meta_title" 
                                                       value="{{ old('meta_title', $service->meta_title) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Meta Keywords')</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="meta_keywords" 
                                                       value="{{ old('meta_keywords', $service->meta_keywords) }}" 
                                                       placeholder="@lang('Comma separated')">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>@lang('Meta Description')</label>
                                        <textarea class="form-control" 
                                                  name="meta_description" 
                                                  rows="3">{{ old('meta_description', $service->meta_description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn--primary w-100 h-45">
                            @lang('Update Service')
                        </button>
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
        
        function proPicURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var preview = $(input).parents('.image-upload').find('.profilePicPreview');
                    preview.css('background-image', 'url(' + e.target.result + ')');
                    preview.addClass('has-image');
                    preview.hide();
                    preview.fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        $(".profilePicUpload").on('change', function() {
            proPicURL(this);
        });
        
    })(jQuery);
</script>
@endpush