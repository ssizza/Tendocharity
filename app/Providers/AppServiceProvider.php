<?php

namespace App\Providers;

use App\Constants\Status;
use App\Lib\Searchable;
use App\Models\{AdminNotification, Frontend, SupportTicket, User};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\{Blade, Cache, View, Route};
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Builder::mixin(new Searchable);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();
        
        // 1. Fix the @permit directive issue
        Blade::directive('permit', function ($expression) {
            return "<?php if (auth()->guard('admin')->check() && auth()->guard('admin')->user()->can({$expression})): ?>";
        });

        Blade::directive('endpermit', function () {
            return "<?php endif; ?>";
        });

        // 2. Installation Check (Simplified)
        if (!Cache::get('SystemInstalled')) {
            if (!file_exists(base_path('.env')) || empty(file_get_contents(base_path('.env')))) {
                header('Location: install');
                exit;
            }
            Cache::put('SystemInstalled', true);
        }

        View::share('emptyMessage', 'Data not found');

        // 3. Admin Sidebar Data
        View::composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount'  => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount' => User::mobileUnverified()->count(),
                'kycUnverifiedUsersCount'    => User::kycUnverified()->count(),
                'kycPendingUsersCount'       => User::kycPending()->count(),
                'pendingTicketCount'         => SupportTicket::whereIn('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'countAutomationError'       => AdminNotification::where('api_response', true)->where('is_read', Status::NO)->count(),
                'updateAvailable'            => version_compare(gs('available_version'), systemDetails()['version'], '>') ? 'v' . gs('available_version') : false,
            ]);
        });

        // 4. Admin Topnav Data
        View::composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });

        // 5. SEO Data
        View::composer('partials.seo', function ($view) {
            $seo = Frontend::where('data_keys', 'seo.data')->first();
            $view->with(['seo' => $seo?->data_values ?? $seo]);
        });

        // 6. Breadcrumb Visibility for Frontend Layout
        View::composer('layouts.frontend', function ($view) {
            $currentRoute = Route::currentRouteName();
            
            // Define routes where breadcrumb should be hidden
            $noBreadcrumbRoutes = [
                'home',                  // Home page
                'fundraisers.index',     // Fundraisers list
                'fundraisers.show',      // Single fundraiser page
                'service.details',       // Service details page
                'blog.details',          // Blog details page
                'event.details',        // Event details page
                // Add more routes here as needed
                // 'event.index',         // Events page if needed
                // 'blogs',               // Blog page if needed
            ];
            
            $hideBreadcrumb = in_array($currentRoute, $noBreadcrumbRoutes);
            
            $view->with('hideBreadcrumb', $hideBreadcrumb);
        });
    }
}