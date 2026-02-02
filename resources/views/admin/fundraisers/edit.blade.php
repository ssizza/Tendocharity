@extends('admin.layouts.app')
@section('panel')
<div class="row">
    <div class="col-lg-12">
        <div class="card b-radius--10">
            <div class="card-header">
                <h5 class="card-title">@lang('Edit Cause')</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.fundraisers.update', $fundraiser->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-lg-8">
                            <!-- Basic Information -->
                            <div class="card mb-3">
                                <div class="card-header bg--primary">
                                    <h6 class="card-title text-white mb-0">@lang('Basic Information')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Urgency Level') <span class="text-danger">*</span></label>
                                                <select name="urgency_level" class="form-select" required>
                                                    <option value="normal" @selected($fundraiser->urgency_level == 'normal')>@lang('Normal')</option>
                                                    <option value="urgent" @selected($fundraiser->urgency_level == 'urgent')>@lang('Urgent')</option>
                                                    <option value="critical" @selected($fundraiser->urgency_level == 'critical')>@lang('Critical')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Status') <span class="text-danger">*</span></label>
                                                <select name="status" class="form-select" required>
                                                    <option value="draft" @selected($fundraiser->status == 'draft')>@lang('Draft')</option>
                                                    <option value="pending" @selected($fundraiser->status == 'pending')>@lang('Pending')</option>
                                                    <option value="active" @selected($fundraiser->status == 'active')>@lang('Active')</option>
                                                    <option value="completed" @selected($fundraiser->status == 'completed')>@lang('Completed')</option>
                                                    <option value="cancelled" @selected($fundraiser->status == 'cancelled')>@lang('Cancelled')</option>
                                                    <option value="rejected" @selected($fundraiser->status == 'rejected')>@lang('Rejected')</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Service')</label>
                                                <select name="service_id" class="form-select">
                                                    <option value="">@lang('Select Service')</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" @selected($fundraiser->service_id == $service->id)>
                                                            {{ $service->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Category')</label>
                                                <select name="category_id" class="form-select">
                                                    <option value="">@lang('Select Category')</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" @selected($fundraiser->category_id == $category->id)>
                                                            {{ $category->name }}
                                                            @if($category->service)
                                                                ({{ $category->service->title }})
                                                            @endif
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Title') <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $fundraiser->title) }}" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Tagline')</label>
                                        <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $fundraiser->tagline) }}">
                                        <small class="text-muted">@lang('Short catchy phrase for your cause')</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Short Description') <span class="text-danger">*</span></label>
                                        <textarea name="short_description" class="form-control" rows="3" maxlength="500" required>{{ old('short_description', $fundraiser->short_description) }}</textarea>
                                        <div class="text-end">
                                            <small class="text-muted">@lang('Maximum 500 characters')</small>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Description') <span class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control" rows="10" id="description-editor">{{ old('description', $fundraiser->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Funding Information -->
                            <div class="card mb-3">
                                <div class="card-header bg--primary">
                                    <h6 class="card-title text-white mb-0">@lang('Funding Information')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Target Amount') <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="number" name="target_amount" class="form-control" step="0.01" min="0" value="{{ old('target_amount', $fundraiser->target_amount) }}" required>
                                                    <span class="input-group-text">{{ $fundraiser->currency }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Currency') <span class="text-danger">*</span></label>
                                                <input type="text" name="currency" class="form-control" value="{{ old('currency', $fundraiser->currency) }}" maxlength="3" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Start Date')</label>
                                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $fundraiser->start_date) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('End Date')</label>
                                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $fundraiser->end_date) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cause Details -->
                            <div class="card mb-3">
                                <div class="card-header bg--primary">
                                    <h6 class="card-title text-white mb-0">@lang('Cause Details')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Project Leader') <span class="text-danger">*</span></label>
                                                <input type="text" name="project_leader" class="form-control" value="{{ old('project_leader', $fundraiser->project_leader) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Organization Name')</label>
                                                <input type="text" name="organization_name" class="form-control" value="{{ old('organization_name', $fundraiser->organization_name) }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Organization Type')</label>
                                        <input type="text" name="organization_type" class="form-control" value="{{ old('organization_type', $fundraiser->organization_type) }}">
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Problem Statement')</label>
                                                <textarea name="problem_statement" class="form-control" rows="5">{{ old('problem_statement', $fundraiser->problem_statement) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Solution Statement')</label>
                                                <textarea name="solution_statement" class="form-control" rows="5">{{ old('solution_statement', $fundraiser->solution_statement) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Beneficiaries')</label>
                                        <textarea name="beneficiaries" class="form-control" rows="3">{{ old('beneficiaries', $fundraiser->beneficiaries) }}</textarea>
                                        <small class="text-muted">@lang('Who will benefit from this cause?')</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Total Beneficiaries Target')</label>
                                        <input type="number" name="total_beneficiaries_target" class="form-control" value="{{ old('total_beneficiaries_target', $fundraiser->total_beneficiaries_target) }}" min="0">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Project Scope')</label>
                                        <textarea name="project_scope" class="form-control" rows="5">{{ old('project_scope', $fundraiser->project_scope) }}</textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Risks & Challenges')</label>
                                        <textarea name="risks_challenges" class="form-control" rows="5">{{ old('risks_challenges', $fundraiser->risks_challenges) }}</textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Sustainability Plan')</label>
                                        <textarea name="sustainability_plan" class="form-control" rows="5">{{ old('sustainability_plan', $fundraiser->sustainability_plan) }}</textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Project Timeline')</label>
                                        <div id="timeline-container">
                                            @php
                                                $timeline = old('timeline', $fundraiser->timeline ? json_decode($fundraiser->timeline, true) : [['phase' => '', 'duration' => '', 'description' => '']]);
                                            @endphp
                                            @foreach($timeline as $index => $item)
                                            <div class="timeline-item mb-3 p-3 border rounded">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('Phase')</label>
                                                            <input type="text" name="timeline[{{ $index }}][phase]" class="form-control" value="{{ $item['phase'] ?? '' }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('Duration')</label>
                                                            <input type="text" name="timeline[{{ $index }}][duration]" class="form-control" value="{{ $item['duration'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="form-label">@lang('Description')</label>
                                                            <textarea name="timeline[{{ $index }}][description]" class="form-control" rows="2">{{ $item['description'] ?? '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($index > 0)
                                                <button type="button" class="btn btn-sm btn--danger remove-timeline mt-2">
                                                    <i class="las la-trash"></i> @lang('Remove')
                                                </button>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                        <button type="button" id="add-timeline" class="btn btn-sm btn--primary mt-2">
                                            <i class="las la-plus"></i> @lang('Add Timeline Phase')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Left Column -->
                        
                        <!-- Right Column -->
                        <div class="col-lg-4">
                            <!-- Featured Image -->
                            <div class="card mb-3">
                                <div class="card-header bg--primary">
                                    <h6 class="card-title text-white mb-0">@lang('Featured Image')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="image-upload">
                                            <div class="thumb">
                                                <div class="avatar-preview">
                                                    <div class="profilePicPreview" style="background-image: url({{ $fundraiser->featured_image ? asset($fundraiser->featured_image) : asset('assets/images/default.png') }})"></div>
                                                </div>
                                                <div class="avatar-edit">
                                                    <input type="file" class="profilePicUpload d-none" name="featured_image" id="featured_image" accept=".png, .jpg, .jpeg, .gif">
                                                    <label for="featured_image" class="btn btn--primary btn-sm w-100">@lang('Upload Image')</label>
                                                </div>
                                            </div>
                                            @if($fundraiser->featured_image)
                                            <small class="text-success d-block mt-2">@lang('Current image uploaded')</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gallery Images -->
                            <div class="card mb-3">
                                <div class="card-header bg--primary">
                                    <h6 class="card-title text-white mb-0">@lang('Gallery Images')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="file" class="form-control" name="gallery_images[]" multiple accept=".png, .jpg, .jpeg, .gif">
                                        <small class="text-muted d-block mt-2">@lang('You can select multiple images')</small>
                                    </div>
                                    
                                    @if($fundraiser->gallery_images)
                                        <div class="row mt-3">
                                            @foreach(json_decode($fundraiser->gallery_images) as $image)
                                            <div class="col-4 mb-2">
                                                <img src="{{ asset($image) }}" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Location -->
                            <div class="card mb-3">
                                <div class="card-header bg--primary">
                                    <h6 class="card-title text-white mb-0">@lang('Location')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Location')</label>
                                        <input type="text" name="location" class="form-control" value="{{ old('location', $fundraiser->location) }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Country')</label>
                                                <input type="text" name="location_country" class="form-control" value="{{ old('location_country', $fundraiser->location_country) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Region')</label>
                                                <input type="text" name="location_region" class="form-control" value="{{ old('location_region', $fundraiser->location_region) }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Latitude')</label>
                                                <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $fundraiser->latitude) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('Longitude')</label>
                                                <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $fundraiser->longitude) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Settings -->
                            <div class="card mb-3">
                                <div class="card-header bg--primary">
                                    <h6 class="card-title text-white mb-0">@lang('Settings')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" @checked($fundraiser->is_featured)>
                                            <label class="form-check-label" for="is_featured">@lang('Mark as Featured')</label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Priority')</label>
                                        <input type="number" name="priority" class="form-control" value="{{ old('priority', $fundraiser->priority) }}" min="0">
                                        <small class="text-muted">@lang('Higher number means higher priority')</small>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Video URL')</label>
                                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $fundraiser->video_url) }}">
                                        <small class="text-muted">@lang('YouTube, Vimeo, etc.')</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- SEO Information -->
                            <div class="card mb-3">
                                <div class="card-header bg--primary">
                                    <h6 class="card-title text-white mb-0">@lang('SEO Information')</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">@lang('Meta Title')</label>
                                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $fundraiser->meta_title) }}">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Meta Description')</label>
                                        <textarea name="meta_description" class="form-control" rows="3">{{ old('meta_description', $fundraiser->meta_description) }}</textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">@lang('Meta Keywords')</label>
                                        <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords', $fundraiser->meta_keywords) }}">
                                        <small class="text-muted">@lang('Separate keywords with commas')</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Right Column -->
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn--primary btn-lg w-100">
                                <i class="las la-save"></i> @lang('Update Cause')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    /* Fix scrolling issues */
    body {
        overflow-x: hidden;
    }
    
    .card {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        margin-bottom: 1.5rem;
    }
    
    .card-header.bg--primary {
        background-color: #007bff !important;
        border-bottom: 1px solid #006fe6;
    }
    
    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .form-select {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 0.75rem 2.25rem 0.75rem 1rem;
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    /* Image upload styles */
    .avatar-preview {
        width: 100%;
        height: 200px;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 1rem;
        border: 2px dashed #dee2e6;
        background-color: #f8f9fa;
    }
    
    .profilePicPreview {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .has-image {
        border: 2px solid #28a745;
    }
    
    /* Timeline item styles */
    .timeline-item {
        background-color: #f8f9fa;
        transition: all 0.3s;
    }
    
    .timeline-item:hover {
        background-color: #e9ecef;
    }
    
    /* Fix for textarea scrolling */
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
        max-height: 400px;
        overflow-y: auto !important;
    }
</style>
@endpush

@push('script')
<script>
    (function ($) {
        "use strict";
        
        // Image preview function
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
        
        // Handle image upload change
        $(document).on('change', '.profilePicUpload', function() {
            proPicURL(this);
        });
        
        // Add timeline phase
        $('#add-timeline').on('click', function () {
            var container = $('#timeline-container');
            var index = container.children().length;
            var html = `
                <div class="timeline-item mb-3 p-3 border rounded">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('Phase')</label>
                                <input type="text" name="timeline[${index}][phase]" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('Duration')</label>
                                <input type="text" name="timeline[${index}][duration]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">@lang('Description')</label>
                                <textarea name="timeline[${index}][description]" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn--danger remove-timeline mt-2">
                        <i class="las la-trash"></i> @lang('Remove')
                    </button>
                </div>
            `;
            container.append(html);
        });
        
        // Remove timeline phase
        $(document).on('click', '.remove-timeline', function () {
            $(this).closest('.timeline-item').remove();
            // Re-index the timeline items
            $('#timeline-container .timeline-item').each(function(index) {
                $(this).find('input[name^="timeline["], textarea[name^="timeline["]').each(function() {
                    var name = $(this).attr('name');
                    name = name.replace(/timeline\[\d+\]/, 'timeline[' + index + ']');
                    $(this).attr('name', name);
                });
            });
        });
        
        // Initialize form controls
        $(document).ready(function() {
            // Remove nicEdit and use simple textarea
            $('#description-editor').removeClass('nicEdit');
            
            // Character counter for short description
            $('textarea[name="short_description"]').on('input', function() {
                var length = $(this).val().length;
                var maxLength = 500;
                if (length > maxLength) {
                    $(this).val($(this).val().substring(0, maxLength));
                }
            });
            
            // Prevent scrolling issues on textareas
            $('textarea').on('mouseenter', function(e) {
                $(this).css('overflow-y', 'auto');
            }).on('mouseleave', function(e) {
                $(this).css('overflow-y', 'hidden');
            });
        });
        
    })(jQuery);
</script>
@endpush