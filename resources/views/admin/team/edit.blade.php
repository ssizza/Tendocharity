@extends('admin.layouts.app')

@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form id="teamMemberForm" method="POST" action="{{ route('admin.team.update', $member->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Left Column - Basic Info -->
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-none">
                                <div class="card-header bg-transparent border-bottom">
                                    <h5 class="card-title mb-0">
                                        <i class="las la-user me-2"></i>@lang('Basic Information')
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required">@lang('Full Name')</label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $member->name) }}" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Email Address')</label>
                                                <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required">@lang('Category')</label>
                                                <select name="category_id" class="form-control select2" required>
                                                    <option value="">@lang('Select Category')</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category_id', $member->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('Position/Title')</label>
                                                <input type="text" name="position" class="form-control" value="{{ old('position', $member->position) }}" placeholder="e.g. Founder, Director, Coordinator">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="required">@lang('Status')</label>
                                                <select name="status" class="form-control select2" required>
                                                    <option value="active" {{ old('status', $member->status) == 'active' ? 'selected' : '' }}>@lang('Active')</option>
                                                    <option value="inactive" {{ old('status', $member->status) == 'inactive' ? 'selected' : '' }}>@lang('Inactive')</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>@lang('Biography')</label>
                                                <textarea name="bio" class="form-control nicEdit" rows="10">{{ old('bio', $member->bio) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Social Media Section -->
                            <div class="card border-0 shadow-none mt-4">
                                <div class="card-header bg-transparent border-bottom">
                                    <h5 class="card-title mb-0">
                                        <i class="las la-share-alt me-2"></i>@lang('Social Media Links')
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $socialPlatforms = [
                                                'facebook' => ['icon' => 'facebook', 'color' => '#1877f2', 'placeholder' => 'https://facebook.com/username'],
                                                'twitter' => ['icon' => 'twitter', 'color' => '#1da1f2', 'placeholder' => 'https://twitter.com/username'],
                                                'instagram' => ['icon' => 'instagram', 'color' => '#e4405f', 'placeholder' => 'https://instagram.com/username'],
                                                'linkedin' => ['icon' => 'linkedin', 'color' => '#0a66c2', 'placeholder' => 'https://linkedin.com/in/username'],
                                                'youtube' => ['icon' => 'youtube', 'color' => '#ff0000', 'placeholder' => 'https://youtube.com/@channel'],
                                                'tiktok' => ['icon' => 'tiktok', 'color' => '#000000', 'placeholder' => 'https://tiktok.com/@username'],
                                                'github' => ['icon' => 'github', 'color' => '#333', 'placeholder' => 'https://github.com/username'],
                                                'website' => ['icon' => 'globe', 'color' => '#6c757d', 'placeholder' => 'https://example.com']
                                            ];
                                        @endphp
                                        
                                        @foreach($socialPlatforms as $key => $platform)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    <i class="lab la-{{ $platform['icon'] }}" style="color: {{ $platform['color'] }}"></i>
                                                    @lang(ucfirst($key))
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text"><i class="lab la-{{ $platform['icon'] }}"></i></span>
                                                    <input type="url" 
                                                           name="social_{{ $key }}" 
                                                           class="form-control" 
                                                           value="{{ old('social_' . $key, $socialMedia[$key] ?? '') }}"
                                                           placeholder="{{ $platform['placeholder'] }}">
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Image -->
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-none">
                                <div class="card-header bg-transparent border-bottom">
                                    <h5 class="card-title mb-0">
                                        <i class="las la-image me-2"></i>@lang('Profile Image')
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="image-upload-preview text-center mb-3">
                                            <img src="{{ $member->image_url }}" 
                                                 alt="{{ $member->name }}" 
                                                 class="preview-image rounded-circle border"
                                                 style="width: 200px; height: 200px; object-fit: cover;">
                                        </div>
                                        
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input" id="imageUpload" accept=".jpg,.jpeg,.png,.gif">
                                            <label class="custom-file-label" for="imageUpload">@lang('Change image')</label>
                                        </div>
                                        
                                        @if($member->image)
                                        <div class="mt-2 text-center">
                                            <small class="text-muted">
                                                <i class="las la-info-circle"></i>
                                                @lang('Leave empty to keep current image')
                                            </small>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Metadata -->
                                    <div class="metadata mt-4 pt-3 border-top">
                                        <h6 class="mb-3">@lang('Metadata')</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">@lang('Created'):</span>
                                            <span>{{ showDateTime($member->created_at) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">@lang('Last updated'):</span>
                                            <span>{{ showDateTime($member->updated_at) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="border-top pt-4 d-flex justify-content-end gap-3">
                                <a href="{{ route('admin.team.index') }}" class="btn btn--dark">
                                    <i class="las la-times"></i> @lang('Cancel')
                                </a>
                                <button type="submit" class="btn btn--primary">
                                    <i class="las la-save"></i> @lang('Update Member')
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('breadcrumb-plugins')
<a href="{{ route('admin.team.index') }}" class="btn btn-sm btn-outline--primary">
    <i class="las la-arrow-left"></i> @lang('Back to Members')
</a>
@endpush

@push('script')
<script>
    (function($) {
        "use strict";

        // Image preview
        $('#imageUpload').on('change', function() {
            let file = this.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('.preview-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
                $(this).next('.custom-file-label').html(file.name);
            }
        });

        // Form submission with AJAX
        $('#teamMemberForm').on('submit', function(e) {
            e.preventDefault();
            
            let formData = new FormData(this);
            let url = $(this).attr('action');
            
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        notify('success', response.message);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
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

    })(jQuery);
</script>
@endpush