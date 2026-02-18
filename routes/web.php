<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundraiserController;
use App\Http\Controllers\Gateway\DonationPaymentController;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::get('cron', 'CronController@cron')->name('cron');

// User Support Ticket
Route::controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{id}', 'replyTicket')->name('reply');
    Route::post('close/{id}', 'closeTicket')->name('close');
    Route::get('download/{attachment_id}', 'ticketDownload')->name('download');
});

// Team Routes
Route::controller('TeamController')->prefix('team')->name('team.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/category/{slug}', 'category')->name('category');
    Route::get('/{id}/{slug?}', 'show')->name('member');
});

// Events Routes
Route::controller('SiteController')->name('event.')->prefix('events')->group(function () {
    Route::get('/', 'events')->name('index');
    Route::get('upcoming', 'upcomingEvents')->name('upcoming');
    Route::get('ongoing', 'ongoingEvents')->name('ongoing');
    Route::get('completed', 'completedEvents')->name('completed');
    Route::get('virtual', 'virtualEvents')->name('virtual');
    Route::get('physical', 'physicalEvents')->name('physical');
    Route::get('{id}/{slug?}', 'eventDetails')->name('details');
    Route::post('{id}/book', 'eventBookSubmit')->name('book');
    Route::get('{id}/calendar', 'eventAddToCalendar')->name('calendar');
});

// Fundraiser Routes
Route::controller(FundraiserController::class)->prefix('fundraisers')->name('fundraisers.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{slug}', 'show')->name('show');
});

// Services Routes
Route::controller('SiteController')->group(function () {
    Route::get('/services', 'services')->name('services');
    Route::get('/services/{slug}', 'serviceDetails')->name('service.details');
});

// Donation Payment Routes (Complete Payment Flow)
Route::prefix('donation')->name('donation.')->controller(DonationPaymentController::class)->group(function () {
    // Step 1: Initiate donation (shows payment methods)
    Route::get('/initiate/{fundraiserSlug}', 'initiateDonation')->name('initiate');
    
    // Step 2: Insert donation record
    Route::post('/process/{fundraiserId}', 'insertDonation')->name('insert');
    
    // Step 3: Confirm and process payment
    Route::get('/confirm', 'confirmPayment')->name('payment.confirm');
    
    // Step 4: Manual payment routes
    Route::get('/manual', 'manualPayment')->name('payment.manual');
    Route::post('/manual/submit', 'manualPaymentSubmit')->name('payment.manual.submit');
    
    // Step 5: Manual payment confirmation (from old system - keep for backward compatibility)
    Route::get('/manual/confirm', 'manualConfirm')->name('payment.manual.confirm');
    Route::post('/manual/update', 'manualUpdate')->name('payment.manual.update');
    
    // Step 6: Payment status pages
    Route::get('/pending/{reference}', 'pending')->name('pending');
    Route::get('/success/{reference}', 'success')->name('success');
    Route::get('/cancel/{reference}', 'cancel')->name('cancel');
    
    // Step 7: API endpoint for checking status (AJAX)
    Route::get('/status/{reference}', 'checkDonationStatus')->name('status');
});

// Main Site Controller Routes
Route::controller('SiteController')->group(function () {
    Route::get('/store/{slug?}', 'serviceCategory')->name('service.category');
    Route::get('store/{categorySlug}/{productSlug}/{id}', 'productConfigure')->name('product.configure');

    Route::get('/register/domain', 'registerDomain')->name('register.domain');
    Route::get('/search/domain', 'searchDomain')->name('search.domain');

    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactSubmit');
    Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

    Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');
    Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

    Route::get('announcements', 'blogs')->name('blogs');
    Route::get('announcements/{slug}', 'blogDetails')->name('blog.details');

    Route::get('policy/{slug}', 'policyPages')->name('policy.pages');

    Route::get('placeholder-image/{size}', 'placeholderImage')->withoutMiddleware('maintenance')->name('placeholder.image');
    Route::get('maintenance-mode','maintenance')->withoutMiddleware('maintenance')->name('maintenance');
    Route::post('subscribe', 'subscribe')->name('subscribe');

    // This dynamic route should be LAST
    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});

// Keep backward compatibility with old donation routes if needed
Route::post('/fundraisers/{id}/donate', [DonationPaymentController::class, 'insertDonation'])->name('fundraisers.donate');