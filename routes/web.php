<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FundraiserController;
use App\Http\Controllers\DonationController;


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
// Team Routes - Add this before the dynamic route
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
    Route::post('/{id}/donate', 'createDonation')->name('donate');
});

// Donations Routes
Route::controller(DonationController::class)->prefix('donations')->name('donations.')->group(function () {
    Route::post('/create/{fundraiser_id}', 'store')->name('create');
    Route::get('/success/{id}', 'success')->name('success');
    Route::get('/cancel/{id}', 'cancel')->name('cancel');
});

// Services Routes - ADD THIS BEFORE THE DYNAMIC ROUTE
Route::controller('SiteController')->group(function () {
    Route::get('/services', 'services')->name('services');
    Route::get('/services/{slug}', 'serviceDetails')->name('service.details');
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

// Donation Payment Routes (No Auth Required)
Route::controller(\App\Http\Controllers\Gateway\DonationPaymentController::class)->group(function () {
    // Donation payment flow
    Route::get('/donate/{fundraiser}', 'initiateDonation')->name('donation.initiate');
    Route::post('/donate/{fundraiser}/process', 'insertDonation')->name('donation.insert');
    Route::get('/donation/confirm', 'confirmPayment')->name('donation.payment.confirm');
    Route::get('/donation/manual/confirm', 'manualConfirm')->name('donation.payment.manual.confirm');
    Route::post('/donation/manual/update', 'manualUpdate')->name('donation.payment.manual.update');
    Route::get('/donation/success/{reference}', 'success')->name('donation.success');
    Route::get('/donation/cancel/{reference}', 'cancel')->name('donation.cancel');
    Route::get('/donation/status/{reference}', 'checkDonationStatus')->name('donation.status');
});


// Update existing donation route to use new controller
Route::post('/fundraisers/{id}/donate', [\App\Http\Controllers\Gateway\DonationPaymentController::class, 'insertDonation'])
    ->name('fundraisers.donate');

    