@extends('layouts.frontend')

@section('content')
    <div class="contact-section pt-60 pb-60 bg--light section-full">
        <div class="container">
            {{-- Breadcrumb --}}
            <div class="row mb-4">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text--base">@lang('Home')</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('blogs') }}" class="text--base">@lang('Blog')</a>
                            </li>
                            <li class="breadcrumb-item active text--body" aria-current="page">
                                {{ strLimit(__($blog->data_values->title), 30) }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row g-4">
                {{-- Main Content --}}
                <div class="col-lg-8">
                    <div class="card custom--card blog-detail-card">
                        @if($blog->data_values->image ?? false)
                            <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '750x450') }}" 
                                 class="card-img-top blog-detail-img" 
                                 alt="{{ __(@$blog->data_values->title) }}">
                        @endif
                        
                        <div class="card-body">
                            {{-- Blog Header --}}
                            <div class="blog-header mb-4">
                                <h2 class="blog-detail-title mb-3">{{ __(@$blog->data_values->title) }}</h2>
                                
                                <div class="blog-meta d-flex flex-wrap gap-4">
                                    <span class="text-muted">
                                        <i class="far fa-calendar-alt me-2"></i>
                                        {{ showDateTime(@$blog->created_at, 'l, d F, Y') }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="far fa-clock me-2"></i>
                                        {{ showDateTime(@$blog->created_at, 'h:i A') }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="far fa-user me-2"></i>
                                        @lang('Admin')
                                    </span>
                                </div>
                            </div>

                            {{-- Blog Content --}}
                            <div class="blog-content mb-5">
                                @php echo $blog->data_values->description; @endphp
                            </div>

                            {{-- Share Buttons --}}
                            <div class="blog-share mb-4">
                                <h6 class="mb-3">@lang('Share this article:')</h6>
                                <div class="d-flex gap-2">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.details', slug(@$blog->data_values->title))) }}" 
                                       target="_blank" 
                                       class="btn btn--base btn--sm" 
                                       style="background: #3b5998; border-color: #3b5998;">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.details', slug(@$blog->data_values->title))) }}&text={{ urlencode($blog->data_values->title) }}" 
                                       target="_blank" 
                                       class="btn btn--base btn--sm" 
                                       style="background: #1da1f2; border-color: #1da1f2;">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.details', slug(@$blog->data_values->title))) }}" 
                                       target="_blank" 
                                       class="btn btn--base btn--sm" 
                                       style="background: #0077b5; border-color: #0077b5;">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->data_values->title . ' - ' . route('blog.details', slug(@$blog->data_values->title))) }}" 
                                       target="_blank" 
                                       class="btn btn--base btn--sm" 
                                       style="background: #25d366; border-color: #25d366;">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                </div>
                            </div>

                            {{-- Facebook Comments --}}
                            <div class="fb-comments mb-4" 
                                 data-href="{{ route('blog.details', slug(@$blog->data_values->title)) }}" 
                                 data-numposts="5" 
                                 data-width="100%">
                            </div>

                            {{-- Navigation Buttons --}}
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top" 
                                 style="border-color: hsl(var(--border)) !important;">
                                <a href="{{ route('blogs') }}" class="btn btn--outline-base">
                                    <i class="fas fa-angle-double-left me-2"></i>@lang('Back to Blog')
                                </a>
                                
                                <a href="{{ route('home') }}" class="btn btn--base">
                                    @lang('Go Home') <i class="fas fa-home ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    {{-- Recent Posts --}}
                    @php $recentBlogs = App\Models\Frontend::where('data_keys', 'blog.element')
                                            ->where('id', '!=', $blog->id)
                                            ->orderBy('id', 'DESC')
                                            ->limit(5)
                                            ->get(); @endphp
                    
                    @if($recentBlogs->count() > 0)
                        <div class="card custom--card mb-4">
                            <div class="card-header bg--base text-white">
                                <h5 class="card-title mb-0 text-white">@lang('Recent Posts')</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    @foreach($recentBlogs as $recent)
                                        <a href="{{ route('blog.details', slug(@$recent->data_values->title)) }}" 
                                           class="list-group-item list-group-item-action d-flex align-items-start gap-3 p-3">
                                            @if($recent->data_values->image ?? false)
                                                <img src="{{ getImage('assets/images/frontend/blog/' . @$recent->data_values->image, '60x60') }}" 
                                                     class="rounded" 
                                                     alt="{{ __(@$recent->data_values->title) }}"
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ strLimit(__($recent->data_values->title), 40) }}</h6>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ showDateTime(@$recent->created_at, 'd M, Y') }}
                                                </small>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Categories Widget (if you have categories) --}}
                    {{-- @include('sections.blog_categories') --}}

                
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
<style>
    /* Blog Detail Styles */
    .blog-detail-card {
        border: none;
        overflow: hidden;
    }
    
    .blog-detail-img {
        height: 400px;
        object-fit: cover;
        border-radius: 10px 10px 0 0;
    }
    
    @media (max-width: 767px) {
        .blog-detail-img {
            height: 250px;
        }
    }
    
    .blog-detail-title {
        color: hsl(var(--heading));
        font-size: 2.2rem;
        line-height: 1.3;
    }
    
    @media (max-width: 767px) {
        .blog-detail-title {
            font-size: 1.8rem;
        }
    }
    
    .blog-content {
        color: hsl(var(--body));
        line-height: 1.8;
        font-size: 1.1rem;
    }
    
    .blog-content h1,
    .blog-content h2,
    .blog-content h3,
    .blog-content h4,
    .blog-content h5,
    .blog-content h6 {
        color: hsl(var(--heading));
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        margin: 1.5rem 0;
    }
    
    .blog-content blockquote {
        background: hsl(var(--light));
        border-left: 4px solid hsl(var(--base));
        padding: 1.5rem;
        margin: 1.5rem 0;
        font-style: italic;
        border-radius: 0 10px 10px 0;
    }
    
    .blog-content blockquote p {
        margin-bottom: 0;
    }
    
    .blog-content ul,
    .blog-content ol {
        padding-left: 1.5rem;
        margin: 1rem 0;
    }
    
    .blog-content li {
        margin-bottom: 0.5rem;
    }
    
    /* Sidebar Styles */
    .list-group-item {
        border-color: hsl(var(--border));
        color: hsl(var(--body));
        transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
        background: hsl(var(--base)/0.05);
        color: hsl(var(--base));
    }
    
    .list-group-item h6 {
        color: hsl(var(--heading));
        transition: color 0.3s ease;
    }
    
    .list-group-item:hover h6 {
        color: hsl(var(--base));
    }
    
    /* Breadcrumb Styles */
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
    
    .breadcrumb-item + .breadcrumb-item::before {
        color: hsl(var(--body)/0.5);
        content: "›";
        font-size: 1.2rem;
        line-height: 1;
    }
    
    .breadcrumb-item a:hover {
        text-decoration: underline;
    }
    
    .breadcrumb-item.active {
        color: hsl(var(--body));
    }
    
    /* Newsletter Form */
    .newsletter-form .form--control {
        border-right: none;
        border-radius: 5px 0 0 5px;
    }
    
    .newsletter-form .btn--base {
        border-radius: 0 5px 5px 0;
        padding: 0 20px;
    }
    
    .newsletter-form .btn--base:hover {
        background: hsl(var(--base-600));
    }
</style>
@endpush

@push('fbComment')
    @php echo loadExtension('fb-comment') @endphp
@endpush