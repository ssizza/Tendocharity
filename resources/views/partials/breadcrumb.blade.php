@php
    $breadcrumb = @getContent('breadcrumb.content', null, true)->first();
    $siteName = gs()->site_name ?? 'TNTCEO'; // Using site_name from general_settings
@endphp

<section class="w-full min-vh-50 py-5 py-lg-5 d-flex align-items-center position-relative overflow-hidden"
    style="
        @if(isset($breadcrumb->data_values->background_type) && $breadcrumb->data_values->background_type == 'color')
            background-color: {{ $breadcrumb->data_values->background_value ?? '#ffffff' }};
        @elseif(isset($breadcrumb->data_values->background_type) && $breadcrumb->data_values->background_type == 'image')
            background-image: url('{{ getImage('assets/images/frontend/breadcrumb/' . $breadcrumb->data_values->background_value) }}');
            background-size: cover;
            background-position: center;
        @else
            background-color: #ffffff;
        @endif
    ">
    
    {{-- Video Background --}}
    @if(isset($breadcrumb->data_values->background_type) && $breadcrumb->data_values->background_type == 'video')
    <video class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" 
           autoplay loop muted playsinline
           style="opacity: {{ isset($breadcrumb->data_values->video_loaded) && $breadcrumb->data_values->video_loaded ? '1' : '0' }}; transition: opacity 0.5s;">
        <source src="{{ $breadcrumb->data_values->background_value ?? '' }}" type="video/mp4">
    </video>
    @endif
    
    <div class="container px-4 px-sm-5 px-lg-4 px-xl-5 w-100">
        <div class="row">
            <div class="col-lg-8">
                {{-- Dynamic Site Name subtitle from gs()->site_name --}}
                @if($siteName)
                <h2 class="small fw-semibold text-white-50 mb-2 text-uppercase tracking-wider">
                    {{ $siteName }}
                </h2>
                @endif
                
                {{-- Optional subtitle from database --}}
                @if(isset($breadcrumb->data_values->subtitle))
                <h2 class="small fw-semibold text-white-50 mb-2 text-uppercase tracking-wider">
                    {{ __($breadcrumb->data_values->subtitle) }}
                </h2>
                @endif
                
                {{-- Main Title - Use from breadcrumb data or fallback to page title --}}
                <h1 class="display-4 display-lg-3 fw-bold text-white mb-4">
                    {{ __($breadcrumb->data_values->title ?? $pageTitle ?? 'Page Title') }}
                </h1>
                
                {{-- Optional description (currently hidden) --}}
                @if(isset($breadcrumb->data_values->description) && false) {{-- Hidden for now --}}
                <p class="lead text-white-50">
                    {{ __($breadcrumb->data_values->description) }}
                </p>
                @endif
                
                {{-- Breadcrumb trail --}}
                @if (!request()->routeIs('home'))
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mt-4 mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}" class="text-white text-decoration-none">
                                @lang('Home')
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">
                            {{ __($pageTitle) }}
                        </li>
                    </ol>
                </nav>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.min-vh-50 {
    min-height: 30vh;
}

.tracking-wider {
    letter-spacing: 0.05em;
}

.object-fit-cover {
    object-fit: cover;
}

/* Overlay for better text readability on images/videos */
section.position-relative::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
    z-index: 1;
}

section.position-relative > * {
    position: relative;
    z-index: 2;
}

.breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255,255,255,0.5);
    content: "/";
}

.breadcrumb-item a:hover {
    color: white !important;
    text-decoration: underline;
}
</style>