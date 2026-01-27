<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventApplicant;
use App\Models\EventGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    // Display all events
    public function index()
    {
        $pageTitle = 'All Events';
        $events = Event::withCount(['applicants', 'gallery'])
                      ->orderBy('startDate', 'desc')
                      ->paginate(20);
        
        return view('admin.events.index', compact('pageTitle', 'events'));
    }

    // Show create event form
    public function create()
    {
        $pageTitle = 'Create New Event';
        return view('admin.events.create', compact('pageTitle'));
    }

    // Store new event
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'full_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'location' => 'required|string|max:255',
            'type' => 'required|in:virtual,physical',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Prepare description as JSON
        $description = [
            'short_description' => $request->short_description,
            'full_description' => $request->full_description
        ];

        $event = new Event();
        $event->title = $request->title;
        $event->description = json_encode($description);
        $event->startDate = $request->startDate;
        $event->endDate = $request->endDate;
        $event->location = $request->location;
        $event->type = $request->type;
        $event->status = $request->status;

        if ($request->hasFile('image')) {
            try {
                $path = $this->uploadImage($request->image, 'events');
                $event->image = $path;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the image.'];
                return back()->withNotify($notify);
            }
        }

        $event->save();

        $notify[] = ['success', 'Event created successfully'];
        return redirect()->route('admin.events.index')->withNotify($notify);
    }

    // Show edit event form
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $pageTitle = 'Edit Event: ' . $event->title;
        
        // Decode the JSON description
        $description = json_decode($event->description, true);
        
        return view('admin.events.edit', compact('pageTitle', 'event', 'description'));
    }

    // Update event
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'full_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'location' => 'required|string|max:255',
            'type' => 'required|in:virtual,physical',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Prepare description as JSON
        $description = [
            'short_description' => $request->short_description,
            'full_description' => $request->full_description
        ];

        $event->title = $request->title;
        $event->description = json_encode($description);
        $event->startDate = $request->startDate;
        $event->endDate = $request->endDate;
        $event->location = $request->location;
        $event->type = $request->type;
        $event->status = $request->status;

        if ($request->hasFile('image')) {
            try {
                // Remove old image if exists
                if ($event->image) {
                    $this->removeImage($event->image, 'events');
                }
                
                $path = $this->uploadImage($request->image, 'events');
                $event->image = $path;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the image.'];
                return back()->withNotify($notify);
            }
        }

        $event->save();

        $notify[] = ['success', 'Event updated successfully'];
        return back()->withNotify($notify);
    }

    // Delete event
    public function delete($id)
    {
        $event = Event::findOrFail($id);
        
        // Delete associated gallery images
        if ($event->gallery->count() > 0) {
            foreach ($event->gallery as $gallery) {
                $this->removeImage($gallery->image_url, 'events/gallery');
                $gallery->delete();
            }
        }
        
        // Delete event image
        if ($event->image) {
            $this->removeImage($event->image, 'events');
        }
        
        // Delete applicants
        $event->applicants()->delete();
        
        $event->delete();

        $notify[] = ['success', 'Event deleted successfully'];
        return back()->withNotify($notify);
    }

    // Show event applicants
    public function applicants(Request $request)
    {
        $pageTitle = 'Event Applicants';
        
        $query = EventApplicant::with('event');
        
        if ($request->eventId) {
            $query->where('eventId', $request->eventId);
            $event = Event::find($request->eventId);
            if ($event) {
                $pageTitle .= ' - ' . $event->title;
            }
        }
        
        $applicants = $query->orderBy('createdAt', 'desc')->paginate(20);
        $events = Event::all();
        
        return view('admin.events.applicants', compact('pageTitle', 'applicants', 'events'));
    }

    // Delete applicant
    public function deleteApplicant($id)
    {
        $applicant = EventApplicant::findOrFail($id);
        $applicant->delete();

        $notify[] = ['success', 'Applicant removed successfully'];
        return back()->withNotify($notify);
    }

    // Show event gallery
    public function gallery(Request $request)
    {
        $pageTitle = 'Event Gallery';
        
        $query = EventGallery::with('event');
        
        if ($request->eventId) {
            $query->where('eventId', $request->eventId);
            $event = Event::find($request->eventId);
            if ($event) {
                $pageTitle .= ' - ' . $event->title;
            }
        }
        
        $gallery = $query->orderBy('created_at', 'desc')->paginate(20);
        $events = Event::all();
        
        return view('admin.events.gallery', compact('pageTitle', 'gallery', 'events'));
    }

    // Store gallery image
    public function storeGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eventId' => 'required|exists:events,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $gallery = new EventGallery();
        $gallery->eventId = $request->eventId;
        $gallery->alt = $request->alt;
        $gallery->id = uniqid();

        if ($request->hasFile('image')) {
            try {
                $path = $this->uploadImage($request->image, 'events/gallery');
                $gallery->image_url = $path;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Could not upload the image.'];
                return back()->withNotify($notify);
            }
        }

        $gallery->save();

        $notify[] = ['success', 'Image added to gallery successfully'];
        return back()->withNotify($notify);
    }

    // Delete gallery image
    public function deleteGallery($id)
    {
        $gallery = EventGallery::findOrFail($id);
        $this->removeImage($gallery->image_url, 'events/gallery');
        $gallery->delete();

        $notify[] = ['success', 'Image removed from gallery successfully'];
        return back()->withNotify($notify);
    }

    // Helper method to upload images
    private function uploadImage($image, $folder)
    {
        $path = 'assets/images/' . $folder . '/';
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path($path), $filename);
        return $filename;
    }

    // Helper method to remove images
    private function removeImage($filename, $folder)
    {
        $path = public_path('assets/images/' . $folder . '/' . $filename);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}