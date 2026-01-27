<?php

use Illuminate\Support\Facades\Route;

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

    Route::get('/{slug}', 'pages')->name('pages');
    Route::get('/', 'index')->name('home');
});