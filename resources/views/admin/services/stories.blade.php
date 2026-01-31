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
                                <th>@lang('Service')</th>
                                <th>@lang('Type')</th>
                                <th>@lang('Author')</th>
                                <th>@lang('Featured')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stories as $story)
                            <tr>
                                <td>
                                    <div class="user">
                                        @if($story->image)
                                        <div class="thumb">
                                            <img src="{{ getImage(getFilePath('service_story') . '/' . $story->image) }}" 
                                                 alt="@lang('image')" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        </div>
                                        @endif
                                        <span class="name">{{ $story->title }}</span>
                                    </div>
                                </td>
                                <td>{{ $story->service->title }}</td>
                                <td>
                                    @php
                                        $type = $story->type;
                                        $badge = [
                                            'story' => 'primary',
                                            'case_study' => 'info',
                                            'testimonial' => 'success'
                                        ][$type];
                                    @endphp
                                    <span class="badge badge--{{ $badge }}">@lang(ucfirst(str_replace('_', ' ', $type)))</span>
                                </td>
                                <td>
                                    @if($story->author_name)
                                        {{ $story->author_name }}
                                        @if($story->author_position)
                                            <br><small class="text-muted">{{ $story->author_position }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">@lang('Anonymous')</span>
                                    @endif
                                </td>
                                <td>
                                    @if($story->featured)
                                        <span class="badge badge--success">@lang('Yes')</span>
                                    @else
                                        <span class="badge badge--warning">@lang('No')</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="button--group">
                                        <a href="{{ route('admin.services.stories.edit', $story->id) }}" 
                                           class="btn btn-sm btn--primary">
                                            <i class="las la-edit"></i> @lang('Edit')
                                        </a>
                                        <button class="btn btn-sm btn--danger confirmationBtn" 
                                                data-question="@lang('Are you sure to delete this story?')" 
                                                data-action="{{ route('admin.services.stories.delete', $story->id) }}">
                                            <i class="las la-trash"></i> 
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="text-muted text-center" colspan="6">@lang('No stories found')</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($stories->hasPages())
            <div class="card-footer py-4">
                {{ paginateLinks($stories) }}
            </div>
            @endif
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <a href="{{ route('admin.services.stories.create') }}" class="btn btn--primary btn-lg">
            <i class="las la-plus"></i> @lang('Add New Story')
        </a>
    </div>
</div>

<x-confirmation-modal />
@endsection