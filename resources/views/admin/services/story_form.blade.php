@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ isset($story) ? route('admin.services.stories.update', $story->id) : route('admin.services.stories.store') }}" 
                      method="POST" 
                      enctype="multipart/form-data">
                    @csrf
                    @if(isset($story))
                        @method('PUT')
                    @endif
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>@lang('Title')</label>
                                <input type="text" 
                                       class="form-control" 
                                       name="title" 
                                       value="{{ old('title', $story->title ?? '') }}" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label>@lang('Content')</label>
                                <textarea class="form-control nicEdit" 
                                          name="content" 
                                          rows="10" 
                                          required>{{ old('content', $story->content ?? '') }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>@lang('Video URL')</label>
                                <input type="url" 
                                       class="form-control" 
                                       name="video_url" 
                                       value="{{ old('video_url', $story->video_url ?? '') }}" 
                                       placeholder="@lang('Optional video link')">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Author Name')</label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="author_name" 
                                               value="{{ old('author_name', $story->author_name ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>@lang('Author Position')</label>
                                        <input type="text" 
                                               class="form-control" 
                                               name="author_position" 
                                               value="{{ old('author_position', $story->author_position ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card border--primary">
                                <div class="card-header bg--primary">
                                    <h5 class="text-white">@lang('Settings')</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>@lang('Service')</label>
                                        <select name="service_id" class="form-control" required>
                                            <option value="">@lang('Select Service')</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" 
                                                        @selected(old('service_id', $story->service_id ?? '') == $service->id)>
                                                    {{ $service->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>@lang('Type')</label>
                                        <select name="type" class="form-control" required>
                                            <option value="story" @selected(old('type', $story->type ?? '') == 'story')>@lang('Story')</option>
                                            <option value="case_study" @selected(old('type', $story->type ?? '') == 'case_study')>@lang('Case Study')</option>
                                            <option value="testimonial" @selected(old('type', $story->type ?? '') == 'testimonial')>@lang('Testimonial')</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>@lang('Sort Order')</label>
                                        <input type="number" 
                                               class="form-control" 
                                               name="sort_order" 
                                               value="{{ old('sort_order', $story->sort_order ?? 0) }}" 
                                               min="0">
                                    </div>
                                    
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="featured" 
                                               id="featured" 
                                               value="1" 
                                               @checked(old('featured', $story->featured ?? false))>
                                        <label class="form-check-label" for="featured">@lang('Featured')</label>
                                    </div>
                                    
                                    <div class="form-group mt-3">
                                        <label>@lang('Story Image')</label>
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    @if(isset($story) && $story->image)
                                                    <div class="profilePicPreview" 
                                                         style="background-image: url('{{ getImage(getFilePath('service_story') . '/' . $story->image) }}')">
                                                    </div>
                                                    @else
                                                    <div class="profilePicPreview" 
                                                         style="background-image: url('{{ asset('assets/images/default.png') }}')">
                                                    </div>
                                                    @endif
                                                    <div class="avatar-edit">
                                                        <input type="file" 
                                                               class="profilePicUpload" 
                                                               name="image" 
                                                               id="image" 
                                                               accept=".png, .jpg, .jpeg">
                                                        <label for="image" class="bg--primary"><i class="la la-pencil"></i></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn--primary w-100 h-45">
                            @if(isset($story))
                                @lang('Update Story')
                            @else
                                @lang('Create Story')
                            @endif
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