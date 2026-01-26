<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventApplicant;
use App\Models\EventGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        $pageTitle = 'Events';
        $events = Event::whereIn('status', ['upcoming', 'ongoing'])
                      ->orderBy('startDate', 'asc')
                      ->paginate(12);
        
        $upcomingEvents = Event::upcoming()->take(3)->get();
        $ongoingEvents = Event::ongoing()->take(3)->get();
        
        return view('events.index', compact('pageTitle', 'events', 'upcomingEvents', 'ongoingEvents'));
    }

    public function show($id, $slug = null)
    {
        $event = Event::findOrFail($id);
        $pageTitle = $event->title;
        
        $gallery = EventGallery::where('eventId', $id)->get();
        $applicantsCount = EventApplicant::where('eventId', $id)->count();
        
        // Generate slug from title if not provided
        $eventSlug = str_slug($event->title);
        
        return view('events.show', compact('pageTitle', 'event', 'gallery', 'applicantsCount'));
    }

    public function book(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        
        // Check if event is open for booking
        if (!$event->isOpenForBooking()) {
            $notify[] = ['error', 'This event is not open for booking.'];
            return back()->withNotify($notify);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if already applied with this email
        $existingApplication = EventApplicant::where('eventId', $id)
            ->where('email', $request->email)
            ->first();

        if ($existingApplication) {
            $notify[] = ['info', 'You have already applied for this event with this email address.'];
            return back()->withNotify($notify);
        }

        // Create application
        $application = new EventApplicant();
        $application->eventId = $id;
        $application->name = $request->name;
        $application->email = $request->email;
        $application->phone = $request->phone;
        $application->save();

        $notify[] = ['success', 'Your application has been submitted successfully!'];
        return back()->withNotify($notify);
    }

    public function generateGoogleCalendarLink($id)
    {
        $event = Event::findOrFail($id);
        
        // Format dates for Google Calendar
        $startDate = Carbon::parse($event->startDate)->format('Ymd\THis\Z');
        $endDate = Carbon::parse($event->endDate)->format('Ymd\THis\Z');
        
        // Create Google Calendar URL
        $url = "https://calendar.google.com/calendar/render?action=TEMPLATE";
        $url .= "&text=" . urlencode($event->title);
        $url .= "&dates=" . $startDate . "/" . $endDate;
        $url .= "&details=" . urlencode(strip_tags(json_decode($event->description)->short_description ?? $event->title));
        $url .= "&location=" . urlencode($event->location);
        $url .= "&sf=true&output=xml";
        
        return redirect($url);
    }
}