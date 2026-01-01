<div class="header bg--dark">
    <div class="container">
        <div class="header-bottom">
            <div class="header-bottom-area align-items-center">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo() }}" alt="@lang('logo')">
                    </a>
                </div>
                <ul class="menu">
                    <!-- Home -->
                    <li>
                        <a href="{{ route('home') }}">@lang('HOME')</a>
                    </li>
                    
                    <!-- Who We Are Dropdown -->
                    <li>
                        <a href="#0">@lang('WHO WE ARE') <span class="dropdown-indicator">~</span></a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('pages', ['about-us']) }}">@lang('About Us')</a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['our-story']) }}">@lang('Our Story')</a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['gallery']) }}">@lang('Our Gallery')</a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['team']) }}">@lang('Our Team')</a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Resources Dropdown -->
                    <li>
                        <a href="#0">@lang('RESOURCES') <span class="dropdown-indicator">~</span></a>
                        <ul class="sub-menu">
                            <li>
                                <a href="{{ route('pages', ['get-help']) }}">@lang('Get Help')</a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['events']) }}">@lang('Events')</a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['newsletter']) }}">@lang('Newsletter')</a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['faq']) }}">@lang('FAQ')</a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Our Work -->
                    <li>
                        <a href="{{ route('pages', ['our-work']) }}">@lang('OUR WORK')</a>
                    </li>
                    
                    <!-- Blog -->
                    <li>
                        <a href="{{ route('blogs') }}">@lang('BLOG')</a>
                    </li>
                    
                    <!-- Events -->
                    <li>
                        <a href="{{ route('pages', ['events']) }}">@lang('EVENTS')</a>
                    </li>
                    
                    <!-- Contact Us -->
                    <li>
                        <a href="{{ route('contact') }}">@lang('CONTACT US')</a>
                    </li>
                    
                    <!-- Donate Button - Using simple href for now -->
                    <li class="menu-btn ms-xl-2">
                        <a href="#donate" class="btn--base ps-2 d-inline-block donate-btn"> 
                            <span>@lang('DONATE')</span>
                            <i class="las la-arrow-right"></i>
                        </a>
                    </li>

                </ul>
                
                <!-- Right side elements - Language selector only -->
                <div class="d-flex align-items-center ms-xl-2 ms-auto me-xl-0 me-2">
                    <x-language />
                </div>
                
                <!-- Mobile menu trigger -->
                <div class="header-trigger-wrapper d-flex d-xl-none align-items-center">
                    <div class="header-trigger">
                        <div class="header-trigger__icon"> <i class="las la-bars"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Additional styles for the donate button */
.donate-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 20px !important;
    border-radius: 4px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.donate-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Ensure proper spacing for the donate button */
.menu .menu-btn.ms-xl-2 {
    margin-left: 15px;
}

/* Style for dropdown indicators */
.dropdown-indicator {
    margin-left: 5px;
    font-weight: normal;
}

/* Make all nav items uppercase */
.menu > li > a {
    text-transform: uppercase;
    font-weight: 500;
}

/* Mobile responsiveness adjustments */
@media (max-width: 1199px) {
    .donate-btn {
        margin-top: 10px;
        width: 100%;
        justify-content: center;
    }
}
</style>