<div class="header" style="background-color: hsl(var(--dark)); position: sticky; z-index: 999; width: 100%; top: 0;">
    <div class="container">
        <div class="header-bottom" style="width: 100%; padding: 15px 0;">
            <div class="header-bottom-area align-items-center" style="display: flex; flex-wrap: wrap; position: relative;">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ siteLogo() }}" alt="@lang('logo')" style="max-width: 165px; max-height: 35px;">
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <ul class="menu" style="display: flex; flex-wrap: wrap; align-items: center; margin: 0; position: relative; margin-left: auto;">
                    <!-- Home -->
                    <li style="position: relative; margin-right: 20px;">
                        <a href="{{ route('home') }}" 
                           style="display: block; padding: 4px 10px; font-size: 15px; font-weight: 500; color: hsl(var(--light)); text-transform: uppercase; transition: all 0.3s;">
                            @lang('HOME')
                        </a>
                    </li>
                    
                    <!-- Who We Are Dropdown -->
                    <li class="has-sub-menu" style="position: relative; margin-right: 20px;">
                        <a href="#0" 
                           style="display: flex; justify-content: space-between; padding: 4px 10px; font-size: 15px; font-weight: 500; color: hsl(var(--light)); text-transform: uppercase; transition: all 0.3s;">
                            @lang('WHO WE ARE')
                            <span class="dropdown-indicator" style="margin-left: 5px; font-weight: normal;">~</span>
                        </a>
                        <ul class="sub-menu" 
                            style="position: absolute; top: 100%; left: 0px; opacity: 0; visibility: hidden; min-width: 200px; transition: all ease 0.3s; transform: translateY(15px); box-shadow: 0 3px 12px 3px hsl(var(--base)/0.15); overflow: hidden; z-index: 11; padding: 10px; background-color: hsl(var(--dark));">
                            <li>
                                <a href="{{ route('pages', ['about-us']) }}" 
                                   style="display: block; padding: 7px 15px; font-size: 14px; color: hsl(var(--white)); transition: all 0.3s;">
                                    @lang('About Us')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['team']) }}" 
                                   style="display: block; padding: 7px 15px; font-size: 14px; color: hsl(var(--white)); transition: all 0.3s;">
                                    @lang('Our Team')
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Resources Dropdown -->
                    <li class="has-sub-menu" style="position: relative; margin-right: 20px;">
                        <a href="#0" 
                           style="display: flex; justify-content: space-between; padding: 4px 10px; font-size: 15px; font-weight: 500; color: hsl(var(--light)); text-transform: uppercase; transition: all 0.3s;">
                            @lang('RESOURCES')
                            <span class="dropdown-indicator" style="margin-left: 5px; font-weight: normal;">~</span>
                        </a>
                        <ul class="sub-menu" 
                            style="position: absolute; top: 100%; left: 0px; opacity: 0; visibility: hidden; min-width: 200px; transition: all ease 0.3s; transform: translateY(15px); box-shadow: 0 3px 12px 3px hsl(var(--base)/0.15); overflow: hidden; z-index: 11; padding: 10px; background-color: hsl(var(--dark));">
                            <li>
                                <a href="{{ route('pages', ['get-help']) }}" 
                                   style="display: block; padding: 7px 15px; font-size: 14px; color: hsl(var(--white)); transition: all 0.3s;">
                                    @lang('Get Help')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['events']) }}" 
                                   style="display: block; padding: 7px 15px; font-size: 14px; color: hsl(var(--white)); transition: all 0.3s;">
                                    @lang('Events')
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('pages', ['faq']) }}" 
                                   style="display: block; padding: 7px 15px; font-size: 14px; color: hsl(var(--white)); transition: all 0.3s;">
                                    @lang('FAQ')
                                </a>
                            </li>
                        </ul>
                    </li>
                    
                    <!-- Our Work -->
                    <li style="position: relative; margin-right: 20px;">
                        <a href="{{ route('pages', ['services']) }}" 
                           style="display: block; padding: 4px 10px; font-size: 15px; font-weight: 500; color: hsl(var(--light)); text-transform: uppercase; transition: all 0.3s;">
                            @lang('OUR WORK')
                        </a>
                    </li>
                    
                    <!-- Blog -->
                    <li style="position: relative; margin-right: 20px;">
                        <a href="{{ route('blogs') }}" 
                           style="display: block; padding: 4px 10px; font-size: 15px; font-weight: 500; color: hsl(var(--light)); text-transform: uppercase; transition: all 0.3s;">
                            @lang('BLOG')
                        </a>
                    </li>
                    
                    <!-- Events -->
                    <li style="position: relative; margin-right: 20px;">
                        <a href="{{ route('pages', ['events']) }}" 
                           style="display: block; padding: 4px 10px; font-size: 15px; font-weight: 500; color: hsl(var(--light)); text-transform: uppercase; transition: all 0.3s;">
                            @lang('EVENTS')
                        </a>
                    </li>
                    
                    <!-- Contact Us -->
                    <li style="position: relative; margin-right: 20px;">
                        <a href="{{ route('contact') }}" 
                           style="display: block; padding: 4px 10px; font-size: 15px; font-weight: 500; color: hsl(var(--light)); text-transform: uppercase; transition: all 0.3s;">
                            @lang('CONTACT US')
                        </a>
                    </li>
                    
                    <!-- Donate Button -->
                    <li class="menu-btn" style="margin-left: 15px;">
                        <a href="#donate" 
                           class="btn--base"
                           style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 8px 20px; background-color: hsl(var(--base)); border: 2px solid hsl(var(--base)); color: hsl(var(--white)); border-radius: 4px; font-weight: 600; transition: all 0.3s; text-transform: uppercase;">
                            <span>@lang('DONATE')</span>
                            <i class="las la-arrow-right"></i>
                        </a>
                    </li>
                </ul>
                
                <!-- Right side elements - Language selector only -->
                <div class="d-flex align-items-center" style="margin-left: auto;">
                    <x-language />
                </div>
                
                <!-- Mobile menu trigger -->
                <div class="header-trigger-wrapper d-flex d-xl-none align-items-center" style="display: flex;">
                    <div class="header-trigger" style="cursor: pointer;">
                        <div class="header-trigger__icon" style="color: hsl(var(--base)); font-size: 35px; line-height: 1;">
                            <i class="las la-bars"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Menu Styles (Inline CSS for hover effects that can't be done with inline styles) -->
<style>
    /* Desktop hover effects for submenus */
    @media (min-width: 1200px) {
        .has-sub-menu:hover > .sub-menu {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }
        
        .sub-menu li a:hover {
            padding-left: 20px !important;
            background-color: hsl(var(--base)) !important;
            color: hsl(var(--white)) !important;
        }
    }
    
    /* Menu item hover effects */
    .menu > li > a:hover {
        color: hsl(var(--white)) !important;
    }
    
    /* Donate button hover */
    .menu-btn a:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px hsl(var(--dark)/0.15);
        background-color: hsl(var(--base-600)) !important;
        border-color: hsl(var(--base-600)) !important;
    }
    
    /* Mobile responsiveness */
    @media (max-width: 1199px) {
        .menu {
            position: absolute;
            top: 0;
            left: 0;
            padding: 20px;
            max-height: calc(100vh - 50px);
            min-width: 200px;
            width: 100%;
            visibility: hidden;
            transform-origin: top;
            transform: translateY(-100px) scaleY(0.6);
            opacity: 0;
            overflow-y: auto;
            transition: all ease 0.3s;
            color: hsl(var(--white));
            background: hsl(var(--dark));
            z-index: 999;
            flex-direction: column;
            align-items: flex-start;
        }
        
        .menu.active {
            opacity: 1;
            transform: translateY(0) scaleY(1);
            visibility: visible;
            z-index: 999;
            top: 100%;
            margin-top: 15px;
        }
        
        .menu > li {
            margin-right: 0 !important;
            width: 100%;
            padding: 3px 0;
            border-bottom: 1px solid hsl(var(--white)/0.2);
        }
        
        .menu > li > a {
            padding-left: 0 !important;
        }
        
        .sub-menu {
            display: none;
            padding-left: 25px;
            position: static !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }
        
        .has-sub-menu.open .sub-menu {
            display: block !important;
        }
        
        .menu-btn {
            margin-left: 0 !important;
            width: 100%;
        }
        
        .menu-btn a {
            width: 100%;
            justify-content: center;
            margin-top: 10px;
        }
        
        .header-trigger.change-icon .header-trigger__icon i::before {
            content: "\f00d";
        }
    }
    
    /* Small screens adjustments */
    @media (max-width: 575px) {
        .logo img {
            max-width: 100px !important;
        }
    }
    
    @media (max-width: 450px) {
        .header-trigger__icon {
            font-size: 30px !important;
        }
    }
</style>

<!-- Mobile Menu Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.querySelector('.header-trigger');
        const menu = document.querySelector('.menu');
        const overlay = document.querySelector('.overlay') || createOverlay();
        
        function createOverlay() {
            const overlay = document.createElement('div');
            overlay.className = 'overlay';
            overlay.style.cssText = 'position: fixed; width: 100%; height: 100%; left: 0; top: 0; z-index: 9991; background-color: hsl(var(--dark)/0.8); visibility: hidden; opacity: 0; transition: .3s linear;';
            document.body.appendChild(overlay);
            return overlay;
        }
        
        trigger.addEventListener('click', function() {
            menu.classList.toggle('active');
            overlay.classList.toggle('active');
            this.classList.toggle('change-icon');
        });
        
        overlay.addEventListener('click', function() {
            menu.classList.remove('active');
            overlay.classList.remove('active');
            trigger.classList.remove('change-icon');
        });
        
        // Handle submenu toggles on mobile
        const hasSubMenus = document.querySelectorAll('.has-sub-menu > a');
        hasSubMenus.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 1199) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    parent.classList.toggle('open');
                }
            });
        });
    });
</script>