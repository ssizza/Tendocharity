<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\Fundraisers\CategoryController;
use App\Http\Controllers\Admin\Fundraisers\FundraiserController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TeamCategoryController;

Route::namespace('Auth')->group(function () {
    Route::middleware('admin.guest')->group(function(){
        Route::controller('LoginController')->group(function () {
            Route::get('/', 'showLoginForm')->name('login');
            Route::post('/', 'login')->name('login');
            Route::get('logout', 'logout')->middleware('admin')->withoutMiddleware('admin.guest')->name('logout');
        });

        // Admin Password Reset
        Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function(){
            Route::get('reset', 'showLinkRequestForm')->name('reset');
            Route::post('reset', 'sendResetCodeEmail');
            Route::get('code-verify', 'codeVerify')->name('code.verify');
            Route::post('verify-code', 'verifyCode')->name('verify.code');
        });

        Route::controller('ResetPasswordController')->group(function(){
            Route::get('password/reset/{token}', 'showResetForm')->name('password.reset.form');
            Route::post('password/reset/change', 'reset')->name('password.change');
        });
    });
});

Route::middleware(['admin', 'admin.permission'])->group(function () {
    Route::controller('AdminController')->group(function(){
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('profile', 'profileUpdate')->name('profile.update');
        Route::get('password', 'password')->name('password');
        Route::post('password', 'passwordUpdate')->name('password.update');

        Route::get('order-statistics', 'orderStatistics')->name('order.statistics');

        //Notification
        Route::get('notifications','notifications')->name('notifications');
        Route::get('notification/read/{id}','notificationRead')->name('notification.read');
        Route::get('notifications/read-all','readAllNotification')->name('notifications.read.all');
        Route::post('notifications/delete-all','deleteAllNotification')->name('notifications.delete.all');
        Route::post('notifications/delete-single/{id}','deleteSingleNotification')->name('notifications.delete.single');

        //Report Bugs
        Route::get('request-report','requestReport')->name('request.report');
        Route::post('request-report','reportSubmit');

        Route::get('download-attachments/{file_hash}', 'downloadAttachment')->name('download.attachment');

        //Check Slug
        Route::post('check/slug','checkSlug')->name('check.slug');

        Route::get('active/services', 'activeServices')->name('active.services');
        Route::get('active/domains', 'activeDomains')->name('active.domains');

        Route::get('automation/errors', 'automationErrors')->name('automation.errors');
        Route::get('delete/automation/errors', 'deleteAutomationErrors')->name('delete.automation.errors');
        Route::get('read/automation/errors', 'readAutomationErrors')->name('read.automation.errors');
        Route::get('delete/automation/error/{id}', 'deleteAutomationError')->name('delete.automation.error');

    });

    //Service
    Route::controller('ServiceController')->group(function(){
        Route::get('hosting/details/{id}', 'hostingDetails')->name('order.hosting.details');
        Route::post('hosting/update/', 'hostingUpdate')->name('order.hosting.update');
        Route::get('change/order/hosting/product/{hostingId}/{productId}', 'ServiceController@changeHostingProduct')->name('change.order.hosting.product');

        Route::get('domain/details/{id}', 'domainDetails')->name('order.domain.details');
        Route::post('domain/update', 'domainUpdate')->name('order.domain.update');
    });

    Route::controller('StaffController')->prefix('staff')->name('staff.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('save/{id?}', 'save')->name('save');
        Route::post('switch-status/{id}', 'status')->name('status');
        Route::get('login/{id}', 'login')->name('login');
    });

    Route::controller('RolesController')->prefix('roles')->name('roles.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('add', 'add')->name('add');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::post('save/{id?}', 'save')->name('save');
    });

    //Domain Setup / Tld
    Route::controller('TldController')->group(function(){
        Route::get('all/tld', 'all')->name('tld');
        Route::post('add/tld', 'add')->name('tld.add');
        Route::post('update/tld', 'update')->name('tld.update');
        Route::post('update/tld/pricing', 'updatePricing')->name('tld.update.pricing');
        Route::post('tld/status/{id}', 'status')->name('tld.status');
    });


    // Users Manager
    Route::controller('ManageUsersController')->name('users.')->prefix('clients')->group(function(){
        Route::get('/', 'allUsers')->name('all');
        Route::get('active', 'activeUsers')->name('active');
        Route::get('banned', 'bannedUsers')->name('banned');
        Route::get('email-verified', 'emailVerifiedUsers')->name('email.verified');
        Route::get('email-unverified', 'emailUnverifiedUsers')->name('email.unverified');
        Route::get('mobile-unverified', 'mobileUnverifiedUsers')->name('mobile.unverified');
        Route::get('kyc-unverified', 'kycUnverifiedUsers')->name('kyc.unverified');
        Route::get('kyc-pending', 'kycPendingUsers')->name('kyc.pending');
        Route::get('mobile-verified', 'mobileVerifiedUsers')->name('mobile.verified');
        Route::get('with-balance', 'usersWithBalance')->name('with.balance');

        Route::get('detail/{id}', 'detail')->name('detail');
        Route::get('kyc-data/{id}', 'kycDetails')->name('kyc.details');
        Route::post('kyc-approve/{id}', 'kycApprove')->name('kyc.approve');
        Route::post('kyc-reject/{id}', 'kycReject')->name('kyc.reject');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('add-sub-balance/{id}', 'addSubBalance')->name('add.sub.balance');
        Route::get('send-notification/{id}', 'showNotificationSingleForm')->name('notification.single');
        Route::post('send-notification/{id}', 'sendNotificationSingle')->name('notification.single');
        Route::get('login/{id}', 'login')->name('login');
        Route::post('status/{id}', 'status')->name('status');

        Route::get('send-notification', 'showNotificationAllForm')->name('notification.all');
        Route::post('send-notification', 'sendNotificationAll')->name('notification.all.send');
        Route::get('list', 'list')->name('list');
        Route::get('count-by-segment/{methodName}', 'countBySegment')->name('segment.count');
        Route::get('notification-log/{id}', 'notificationLog')->name('notification.log');

        Route::get('orders/{id}', 'orders')->name('orders');
        Route::get('invoices/{id}', 'invoices')->name('invoices');
        Route::get('cancellations/{id}', 'cancellations')->name('cancellations');
        Route::get('services/{id}', 'services')->name('services');
        Route::get('domains/{id}', 'domains')->name('domains');

        Route::get('add/new', 'addNewForm')->name('add.new.form');
        Route::post('add/new', 'addNew')->name('add.new');
    });

    // Subscriber
    Route::controller('SubscriberController')->prefix('subscriber')->name('subscriber.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::get('send-email', 'sendEmailForm')->name('send.email');
        Route::post('remove/{id}', 'remove')->name('remove');
        Route::post('send-email', 'sendEmail')->name('send.email');
    });

    // Deposit Gateway
    Route::name('gateway.')->prefix('gateway')->group(function(){
        // Automatic Gateway
        Route::controller('AutomaticGatewayController')->prefix('automatic')->name('automatic.')->group(function(){
            Route::get('/', 'index')->name('index');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{code}', 'update')->name('update');
            Route::post('remove/{id}', 'remove')->name('remove');
            Route::post('status/{id}', 'status')->name('status');
        });


        // Manual Methods
        Route::controller('ManualGatewayController')->prefix('manual')->name('manual.')->group(function(){
            Route::get('/', 'index')->name('index');
            Route::get('new', 'create')->name('create');
            Route::post('new', 'store')->name('store');
            Route::get('edit/{alias}', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('status/{id}', 'status')->name('status');
        });
    });



    // Report
    Route::controller('ReportController')->prefix('report')->name('report.')->group(function(){
        Route::get('transaction/{user_id?}', 'transaction')->name('transaction');
        Route::get('login/history', 'loginHistory')->name('login.history');
        Route::get('login/ipHistory/{ip}', 'loginIpHistory')->name('login.ipHistory');
        Route::get('notification/history', 'notificationHistory')->name('notification.history');
        Route::get('email/detail/{id}', 'emailDetails')->name('email.details');
    });


    // Admin Support
    Route::controller('SupportTicketController')->prefix('ticket')->name('ticket.')->group(function(){
        Route::get('/', 'tickets')->name('index');
        Route::get('pending', 'pendingTicket')->name('pending');
        Route::get('closed', 'closedTicket')->name('closed');
        Route::get('answered', 'answeredTicket')->name('answered');
        Route::get('view/{id}', 'ticketReply')->name('view');
        Route::post('reply/{id}', 'replyTicket')->name('reply');
        Route::post('close/{id}', 'closeTicket')->name('close');
        Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
        Route::post('delete/{id}', 'ticketDelete')->name('delete');
    });


    // Language Manager
    Route::controller('LanguageController')->prefix('language')->name('language.')->group(function(){
        Route::get('/', 'langManage')->name('manage');
        Route::post('/', 'langStore')->name('manage.store');
        Route::post('delete/{id}', 'langDelete')->name('manage.delete');
        Route::post('update/{id}', 'langUpdate')->name('manage.update');
        Route::get('edit/{id}', 'langEdit')->name('key');
        Route::post('import', 'langImport')->name('import.lang');
        Route::post('store/key/{id}', 'storeLanguageJson')->name('store.key');
        Route::post('delete/key/{id}', 'deleteLanguageJson')->name('delete.key');
        Route::post('update/key/{id}', 'updateLanguageJson')->name('update.key');
        Route::get('get-keys', 'getKeys')->name('get.key');
    });

    Route::controller('GeneralSettingController')->group(function(){

        Route::get('system-setting', 'systemSetting')->name('setting.system');

        // General Setting
        Route::get('general-setting', 'general')->name('setting.general');
        Route::post('general-setting', 'generalUpdate');

        Route::get('setting/social/credentials', 'socialiteCredentials')->name('setting.socialite.credentials');
        Route::post('setting/social/credentials/update/{key}', 'updateSocialiteCredential')->name('setting.socialite.credentials.update');
        Route::post('setting/social/credentials/status/{key}', 'updateSocialiteCredentialStatus')->name('setting.socialite.credentials.status.update');

        //configuration
        Route::get('setting/system-configuration','systemConfiguration')->name('setting.system.configuration');
        Route::post('setting/system-configuration','systemConfigurationSubmit');

        // Logo-Icon
        Route::get('setting/logo-icon', 'logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'logoIconUpdate')->name('setting.logo.icon.update');

        //Custom CSS
        Route::get('custom-css','customCss')->name('setting.custom.css');
        Route::post('custom-css','customCssSubmit');

        Route::get('sitemap','sitemap')->name('setting.sitemap');
        Route::post('sitemap','sitemapSubmit');

        Route::get('robot','robot')->name('setting.robot');
        Route::post('robot','robotSubmit');

        //Cookie
        Route::get('cookie','cookie')->name('setting.cookie');
        Route::post('cookie','cookieSubmit');

        //maintenance_mode
        Route::get('maintenance-mode','maintenanceMode')->name('maintenance.mode');
        Route::post('maintenance-mode','maintenanceModeSubmit');
    });

    // Event Cron Configuration
    Route::name('events.')->prefix('events')->group(function () {
        // Events CRUD
        Route::get('/', 'EventController@index')->name('index');
        Route::get('create', 'EventController@create')->name('create');
        Route::post('store', 'EventController@store')->name('store');
        Route::get('edit/{id}', 'EventController@edit')->name('edit');
        Route::post('update/{id}', 'EventController@update')->name('update');
        Route::post('delete/{id}', 'EventController@delete')->name('delete');
        
        // Applicants
        Route::get('applicants', 'EventController@applicants')->name('applicants');
        Route::post('applicants/delete/{id}', 'EventController@deleteApplicant')->name('applicants.delete');
        
        // Gallery
        Route::get('gallery', 'EventController@gallery')->name('gallery');
        Route::post('gallery/store', 'EventController@storeGallery')->name('gallery.store');
        Route::post('gallery/delete/{id}', 'EventController@deleteGallery')->name('gallery.delete');
    });

    Route::name('services.')->prefix('services')->group(function () {
        // Services CRUD
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::get('create', [ServiceController::class, 'create'])->name('create');
        Route::post('store', [ServiceController::class, 'store'])->name('store');
        Route::get('edit/{service}', [ServiceController::class, 'edit'])->name('edit');
        Route::put('update/{service}', [ServiceController::class, 'update'])->name('update'); // CHANGED TO PUT
        Route::post('delete/{service}', [ServiceController::class, 'destroy'])->name('delete');
        Route::post('status/{service}', [ServiceController::class, 'toggleStatus'])->name('status');
        
        // Service Stories
        Route::get('stories', [ServiceController::class, 'stories'])->name('stories');
        Route::get('stories/create', [ServiceController::class, 'createStory'])->name('stories.create');
        Route::post('stories/store', [ServiceController::class, 'storeStory'])->name('stories.store');
        Route::get('stories/edit/{story}', [ServiceController::class, 'editStory'])->name('stories.edit');
        Route::put('stories/update/{story}', [ServiceController::class, 'updateStory'])->name('stories.update'); // ALSO CHANGE THIS
        Route::post('stories/delete/{story}', [ServiceController::class, 'destroyStory'])->name('stories.delete');
    });

    Route::controller('CronConfigurationController')->name('cron.')->prefix('cron')->group(function () {
        Route::get('index', 'cronJobs')->name('index');
        Route::post('store', 'cronJobStore')->name('store');
        Route::post('update', 'cronJobUpdate')->name('update');
        Route::post('delete/{id}', 'cronJobDelete')->name('delete');
        Route::get('schedule', 'schedule')->name('schedule');
        Route::post('schedule/store', 'scheduleStore')->name('schedule.store');
        Route::post('schedule/status/{id}', 'scheduleStatus')->name('schedule.status');
        Route::get('schedule/pause/{id}', 'schedulePause')->name('schedule.pause');
        Route::get('schedule/logs/{id}', 'scheduleLogs')->name('schedule.logs');
        Route::post('schedule/log/resolved/{id}', 'scheduleLogResolved')->name('schedule.log.resolved');
        Route::post('schedule/log/flush/{id}', 'logFlush')->name('log.flush');
    });


    //KYC setting
    Route::controller('KycController')->group(function(){
        Route::get('kyc-setting','setting')->name('kyc.setting');
        Route::post('kyc-setting','settingUpdate');
    });

    //Notification Setting
    Route::name('setting.notification.')->controller('NotificationController')->prefix('notification')->group(function(){
        //Template Setting
        Route::get('global/email','globalEmail')->name('global.email');
        Route::post('global/email/update','globalEmailUpdate')->name('global.email.update');

        Route::get('global/sms','globalSms')->name('global.sms');
        Route::post('global/sms/update','globalSmsUpdate')->name('global.sms.update');

        Route::get('global/push','globalPush')->name('global.push');
        Route::post('global/push/update','globalPushUpdate')->name('global.push.update');

        Route::get('templates','templates')->name('templates');
        Route::get('template/edit/{type}/{id}','templateEdit')->name('template.edit');
        Route::post('template/update/{type}/{id}','templateUpdate')->name('template.update');

        //Email Setting
        Route::get('email/setting','emailSetting')->name('email');
        Route::post('email/setting','emailSettingUpdate');
        Route::post('email/test','emailTest')->name('email.test');

        //SMS Setting
        Route::get('sms/setting','smsSetting')->name('sms');
        Route::post('sms/setting','smsSettingUpdate');
        Route::post('sms/test','smsTest')->name('sms.test');

        Route::get('notification/push/setting', 'pushSetting')->name('push');
        Route::post('notification/push/setting', 'pushSettingUpdate');
        Route::post('notification/push/setting/upload', 'pushSettingUpload')->name('push.upload');
        Route::get('notification/push/setting/download', 'pushSettingDownload')->name('push.download');
    });

    // Plugin
    Route::controller('ExtensionController')->prefix('extensions')->name('extensions.')->group(function(){
        Route::get('/', 'index')->name('index');
        Route::post('update/{id}', 'update')->name('update');
        Route::post('status/{id}', 'status')->name('status');
    });


    //System Information
    Route::controller('SystemController')->name('system.')->prefix('system')->group(function(){
        Route::get('info','systemInfo')->name('info');
        Route::get('server-info','systemServerInfo')->name('server.info');
        Route::get('optimize', 'optimize')->name('optimize');
        Route::get('optimize-clear', 'optimizeClear')->name('optimize.clear');
        Route::get('system-update','systemUpdate')->name('update');
        Route::post('system-update','systemUpdateProcess')->name('update.process');
        Route::get('system-update/log','systemUpdateLog')->name('update.log');
    });


    // SEO
    Route::get('seo', 'FrontendController@seoEdit')->name('seo');


    // Frontend
    Route::name('frontend.')->prefix('frontend')->group(function () {

        Route::controller('FrontendController')->group(function(){
            Route::get('index', 'index')->name('index');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'templatesActive')->name('templates.active');
            Route::get('frontend-sections/{key?}', 'frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'frontendElement')->name('sections.element');
            Route::get('frontend-slug-check/{key}/{id?}', 'frontendElementSlugCheck')->name('sections.element.slug.check');
            Route::get('frontend-element-seo/{key}/{id}', 'frontendSeo')->name('sections.element.seo');
            Route::post('frontend-element-seo/{key}/{id}', 'frontendSeoUpdate');
            Route::post('remove/{id}', 'remove')->name('remove');
        });

        // Page Builder
        Route::controller('PageBuilderController')->group(function(){
            Route::get('manage-pages', 'managePages')->name('manage.pages');
            Route::get('manage-pages/check-slug/{id?}', 'checkSlug')->name('manage.pages.check.slug');
            Route::post('manage-pages', 'managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete/{id}', 'managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'manageSectionUpdate')->name('manage.section.update');

            Route::get('manage-seo/{id}','manageSeo')->name('manage.pages.seo');
            Route::post('manage-seo/{id}','manageSeoStore');
        });

    });


    // Fundraiser Routes
    Route::prefix('fundraisers')->name('fundraisers.')->group(function () {
        // Categories
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('categories/{category}/update', [CategoryController::class, 'update'])->name('categories.update');
        Route::post('categories/{category}/status', [CategoryController::class, 'toggleStatus'])->name('categories.status');
        Route::post('categories/{category}/delete', [CategoryController::class, 'destroy'])->name('categories.delete');
        
        // Fundraisers
        Route::get('/', [FundraiserController::class, 'index'])->name('index');
        Route::get('pending', [FundraiserController::class, 'pending'])->name('pending');
        Route::get('create', [FundraiserController::class, 'create'])->name('create');
        Route::post('store', [FundraiserController::class, 'store'])->name('store');
        Route::get('{fundraiser}/edit', [FundraiserController::class, 'edit'])->name('edit');
        Route::post('{fundraiser}/update', [FundraiserController::class, 'update'])->name('update');
        Route::post('{fundraiser}/status', [FundraiserController::class, 'updateStatus'])->name('status');
        Route::post('{fundraiser}/approve', [FundraiserController::class, 'approve'])->name('approve');
        Route::post('{fundraiser}/reject', [FundraiserController::class, 'reject'])->name('reject');
        Route::post('{fundraiser}/toggle-featured', [FundraiserController::class, 'toggleFeatured'])->name('toggle.featured');
        Route::post('{fundraiser}/delete', [FundraiserController::class, 'destroy'])->name('delete');
        
        // Ajax
        Route::get('get-type-fields', [FundraiserController::class, 'getTypeFields'])->name('get.type.fields');
    });

    // ==================== TEAM MANAGEMENT ROUTES ====================
    Route::prefix('team')->name('team.')->group(function () {
        // Team Categories
        Route::controller(TeamCategoryController::class)->prefix('categories')->name('categories.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::put('update/{id}', 'update')->name('update');
            Route::delete('destroy/{id}', 'destroy')->name('destroy');
            Route::post('toggle-status/{id}', 'toggleStatus')->name('toggle-status');
            Route::get('select2', 'getSelect2')->name('select2');
        });

        // Team Members
        Route::controller(TeamController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::put('update/{id}', 'update')->name('update');
            Route::delete('destroy/{id}', 'destroy')->name('destroy');
            Route::post('toggle-status/{id}', 'toggleStatus')->name('toggle-status');
            Route::post('bulk-action', 'bulkAction')->name('bulk-action');
        });
    });
    // ==================== END TEAM MANAGEMENT ROUTES ====================
});