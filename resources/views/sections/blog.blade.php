@php
    if (request()->routeIs('home')) {
        $blogs = @getContent('blog.element', limit: 3);
    } else {
        $blogs = App\Models\Frontend::where('data_keys', 'blog.element')->orderBy('id', 'DESC')->paginate(getPaginate(9)); // Show 9 blogs per page
    }
@endphp

<div class="contact-section section-full pt-60 pb-60 bg--light">
    <div class="container">
        {{-- Section Title for Blog Page --}}
        @if(!request()->routeIs('home'))
            <div class="row justify-content-center mb-5">
                <div class="col-lg-6 text-center">
                    <p class="section-subtitle text-muted">Stay informed with our latest news, events, and announcements</p>
                </div>
            </div>
        @endif

        @forelse($blogs as $blog)
            {{-- Homepage: Show first blog differently for featured layout, then grid --}}
            @if(request()->routeIs('home') && $loop->first)
                {{-- Featured Blog Post (Full Width) --}}
                <div class="row mb-5">
                    <div class="col-12">
                        <div class="card custom--card blog-card featured">
                            <div class="row g-0 align-items-center">
                                @if($blog->data_values->image ?? false)
                                    <div class="col-md-6">
                                        <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '750x450') }}" 
                                             alt="{{ __(@$blog->data_values->title) }}" 
                                             class="img-fluid rounded-start blog-featured-img">
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <div class="blog-meta mb-3">
                                            <span class="badge badge--base me-2">Featured</span>
                                            <span class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ showDateTime(@$blog->created_at, 'd M, Y') }}
                                            </span>
                                        </div>
                                        <h3 class="blog-title mb-3">
                                            <a href="{{ route('blog.details', slug(@$blog->data_values->title)) }}" 
                                               class="text-decoration-none text--heading">
                                                {{ strLimit(__($blog->data_values->title), 60) }}
                                            </a>
                                        </h3>
                                        <p class="blog-excerpt mb-4">
                                            {{ strLimit(strip_tags($blog->data_values->description), 200) }}
                                        </p>
                                        <a href="{{ route('blog.details', slug(@$blog->data_values->title)) }}" 
                                           class="btn btn--base">
                                            @lang('Read Full Article') <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Start Grid for remaining blogs --}}
                @if(!$loop->last)
                    <div class="row g-4">
                @endif
            @elseif(request()->routeIs('home') && !$loop->first)
                {{-- Blog Grid Items (3 per row) --}}
                <div class="col-lg-4 col-md-6">
                    <div class="card custom--card blog-card h-100">
                        @if($blog->data_values->image ?? false)
                            <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '400x250') }}" 
                                 class="card-img-top blog-card-img" 
                                 alt="{{ __(@$blog->data_values->title) }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <div class="blog-meta mb-2">
                                <small class="text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ showDateTime(@$blog->created_at, 'd M, Y') }}
                                </small>
                            </div>
                            <h5 class="blog-title mb-3">
                                <a href="{{ route('blog.details', slug(@$blog->data_values->title)) }}" 
                                   class="text-decoration-none text--heading">
                                    {{ strLimit(__($blog->data_values->title), 50) }}
                                </a>
                            </h5>
                            <p class="blog-excerpt flex-grow-1">
                                {{ strLimit(strip_tags($blog->data_values->description), 100) }}
                            </p>
                            <a href="{{ route('blog.details', slug(@$blog->data_values->title)) }}" 
                               class="btn-link text--base mt-3">
                                @lang('Read More') <i class="fas fa-arrow-right ms-1 fa-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- Close grid div after 3 items or on last item --}}
                @if($loop->iteration % 3 == 0 && !$loop->last)
                    </div><div class="row g-4 mt-0">
                @endif
                
                {{-- Close the grid div on last item --}}
                @if($loop->last)
                    </div>
                @endif
            @else
                {{-- Blog Listing Page - Standard Grid Layout (3 per row) --}}
                <div class="row g-4">
                    @foreach($blogs as $blogItem)
                        <div class="col-lg-4 col-md-6">
                            <div class="card custom--card blog-card h-100">
                                @if($blogItem->data_values->image ?? false)
                                    <img src="{{ getImage('assets/images/frontend/blog/' . @$blogItem->data_values->image, '400x250') }}" 
                                         class="card-img-top blog-card-img" 
                                         alt="{{ __(@$blogItem->data_values->title) }}">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <div class="blog-meta mb-2">
                                        <small class="text-muted">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            {{ showDateTime(@$blogItem->created_at, 'd M, Y') }}
                                        </small>
                                    </div>
                                    <h5 class="blog-title mb-3">
                                        <a href="{{ route('blog.details', slug(@$blogItem->data_values->title)) }}" 
                                           class="text-decoration-none text--heading">
                                            {{ strLimit(__($blogItem->data_values->title), 50) }}
                                        </a>
                                    </h5>
                                    <p class="blog-excerpt flex-grow-1">
                                        {{ strLimit(strip_tags($blogItem->data_values->description), 100) }}
                                    </p>
                                    <a href="{{ route('blog.details', slug(@$blogItem->data_values->title)) }}" 
                                       class="btn-link text--base mt-3">
                                        @lang('Read More') <i class="fas fa-arrow-right ms-1 fa-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @empty
            <div class="row">
                <div class="col-12">
                    <x-empty-message div="{{ true }}" message="No blog posts found" />
                </div>
            </div>
        @endforelse

        {{-- Pagination for blog listing page --}}
        @if(!request()->routeIs('home') && method_exists($blogs, 'links'))
            <div class="row mt-5">
                <div class="col-12">
                    {{ $blogs->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@push('style')
<style>
    /* Blog Card Styles - Using theme variables */
    .blog-card {
        transition: all 0.3s ease;
        border: 1px solid hsl(var(--border));
        overflow: hidden;
        height: 100%;
    }
    
    .blog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px hsl(var(--dark)/0.1);
    }
    
    .blog-card.featured {
        border: none;
        background: linear-gradient(135deg, hsl(var(--white)) 0%, hsl(var(--light)) 100%);
    }
    
    .blog-card-img {
        height: 200px;
        object-fit: cover;
        border-bottom: 3px solid hsl(var(--base)/0.3);
    }
    
    .blog-featured-img {
        height: 100%;
        min-height: 300px;
        object-fit: cover;
        border-radius: 10px 0 0 10px;
    }
    
    @media (max-width: 767px) {
        .blog-featured-img {
            border-radius: 10px 10px 0 0;
            min-height: 200px;
        }
    }
    
    .blog-title a {
        color: hsl(var(--heading));
        transition: color 0.3s ease;
    }
    
    .blog-title a:hover {
        color: hsl(var(--base));
    }
    
    .blog-excerpt {
        color: hsl(var(--body));
        line-height: 1.6;
        font-size: 0.95rem;
    }
    
    .blog-meta {
        color: hsl(var(--body)/0.8);
        font-size: 0.9rem;
    }
    
    .btn-link {
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s ease;
    }
    
    .btn-link:hover {
        gap: 10px;
        color: hsl(var(--base-600)) !important;
    }
    
    .badge--base {
        background: hsl(var(--base));
        color: hsl(var(--white));
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    /* Pagination styling */
    .pagination {
        justify-content: center;
        gap: 5px;
    }
    
    .page-item .page-link {
        color: hsl(var(--body));
        border: 1px solid hsl(var(--border));
        background: hsl(var(--white));
        padding: 8px 15px;
        border-radius: 5px;
        transition: all 0.3s ease;
    }
    
    .page-item.active .page-link,
    .page-item .page-link:hover {
        background: hsl(var(--base));
        color: hsl(var(--white));
        border-color: hsl(var(--base));
    }
    
    .page-item.disabled .page-link {
        background: hsl(var(--light));
        color: hsl(var(--body)/0.5);
        border-color: hsl(var(--border));
        cursor: not-allowed;
    }
    
    /* Section title styling */
    .section-title {
        color: hsl(var(--heading));
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .section-subtitle {
        color: hsl(var(--body));
        font-size: 1.1rem;
    }
    
    @media (max-width: 767px) {
        .section-title {
            font-size: 2rem;
        }
    }
</style>
@endpush