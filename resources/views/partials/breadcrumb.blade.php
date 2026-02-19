@php
    $breadcrumb = @getContent('breadcrumb.content', null, true)->first();
    $siteName = gs()->site_name ?? 'TNTCEO';
@endphp

<section class="w-100 py-4 d-flex align-items-center position-relative"
    style="
        min-height: 200px;
        @if(isset($breadcrumb->data_values->background_type) && $breadcrumb->data_values->background_type == 'color')
            background-color: {{ $breadcrumb->data_values->background_value ?? '#ffffff' }};
        @elseif(isset($breadcrumb->data_values->background_type) && $breadcrumb->data_values->background_type == 'image')
            background-image: url('{{ getImage('assets/images/frontend/breadcrumb/' . $breadcrumb->data_values->background_value) }}');
            background-size: cover;
            background-position: center;
        @else
            background-color: hsl(var(--base));
        @endif
    ">
    
    {{-- Video Background --}}
    @if(isset($breadcrumb->data_values->background_type) && $breadcrumb->data_values->background_type == 'video')
    <video class="position-absolute top-0 start-0 w-100 h-100" 
           style="object-fit: cover; opacity: 0.5;"
           autoplay loop muted playsinline>
        <source src="{{ $breadcrumb->data_values->background_value ?? '' }}" type="video/mp4">
    </video>
    @endif
    
    {{-- Dark Overlay --}}
    <div class="position-absolute top-0 start-0 w-100 h-100" 
         style="background: linear-gradient(to right, hsl(var(--dark)/0.8), hsl(var(--dark)/0.4)); z-index: 1;"></div>
    
    <div class="container position-relative" style="z-index: 2;">
        <div class="row">
            <div class="col-12">
                {{-- Breadcrumb Navigation --}}
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb m-0 p-0" style="background: transparent;">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}" style="color: hsl(var(--white)/0.7); text-decoration: none; font-size: 14px;">
                                <i class="las la-home me-1"></i>Home
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page" 
                            style="color: hsl(var(--white)); font-size: 14px;">
                            {{ __($breadcrumb->data_values->title ?? $pageTitle ?? 'Page Title') }}
                        </li>
                    </ol>
                </nav>
                
                {{-- Page Title --}}
                <h1 style="color: hsl(var(--white)); font-size: 28px; font-weight: 600; margin: 0;">
                    {{ __($breadcrumb->data_values->title ?? $pageTitle ?? 'Page Title') }}
                </h1>
                
                {{-- Optional subtitle --}}
                @if(isset($breadcrumb->data_values->subtitle))
                <p style="color: hsl(var(--white)/0.8); font-size: 14px; margin-top: 5px; margin-bottom: 0;">
                    {{ __($breadcrumb->data_values->subtitle) }}
                </p>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.breadcrumb-item + .breadcrumb-item::before {
    content: "/";
    color: hsl(var(--white)/0.5) !important;
    padding: 0 8px;
}
.breadcrumb-item a:hover {
    color: hsl(var(--white)) !important;
}
</style>