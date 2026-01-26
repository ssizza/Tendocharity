<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\DomainRegisters\Register;
use App\Models\AdminNotification;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\Subscriber;
use App\Models\Page;
use App\Models\Product;
use App\Models\ServiceCategory;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\Event;
use App\Models\EventApplicant;
use App\Models\EventGallery;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function index()
    {
        $reference = @$_GET['reference'];
        if ($reference) {
            session()->put('reference', $reference);
        }

        $pageTitle = 'Home';
        $sections = Page::where('slug', '/')->first();
        
        $seoContents = null;
        $seoImage = null;
        
        if ($sections && $sections->seo_content) {
            $seoContents = $sections->seo_content;
            $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        }
        
        // Get featured events for homepage
        $featuredEvents = Event::where('status', 'upcoming')
            ->orderBy('startDate', 'asc')
            ->take(3)
            ->get();
        
        return view('home', compact('pageTitle', 'sections', 'seoContents', 'seoImage', 'featuredEvents'));
    }

    public function pages($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        
        $seoContents = null;
        $seoImage = null;
        
        if ($page->seo_content) {
            $seoContents = $page->seo_content;
            $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        }
        
        return view('pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('slug', 'contact')->first();
        
        $seoContents = null;
        $seoImage = null;
        
        if ($sections && $sections->seo_content) {
            $seoContents = $sections->seo_content;
            $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        }
        
        return view('contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;
        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    // Events Methods
  // Events Methods
public function events()
{
    $pageTitle = 'Events';
    
    // Try to get events page from Pages table
    $sections = Page::where('slug', 'events')->first();
    
    $seoContents = null;
    $seoImage = null;
    
    if ($sections && $sections->seo_content) {
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
    }
    
    // Get events
    $events = Event::whereIn('status', ['upcoming', 'ongoing'])
        ->orderBy('startDate', 'asc')
        ->paginate(12);
    
    $ongoingEvents = collect([]);
    if ($events->count() > 0) {
        $ongoingEvents = Event::where('status', 'ongoing')
            ->where('startDate', '<=', Carbon::now())
            ->where('endDate', '>=', Carbon::now())
            ->orderBy('startDate', 'asc')
            ->take(3)
            ->get();
    }
        
    $upcomingEvents = collect([]);
    if ($events->count() > 0) {
        $upcomingEvents = Event::where('status', 'upcoming')
            ->where('startDate', '>', Carbon::now())
            ->orderBy('startDate', 'asc')
            ->take(3)
            ->get();
    }
    
    return view('events', compact(
        'pageTitle', 
        'sections', 
        'seoContents', 
        'seoImage', 
        'events', 
        'upcomingEvents', 
        'ongoingEvents'
    ));
}

    public function eventDetails($id, $slug = null)
    {
        $event = Event::findOrFail($id);
        $pageTitle = $event->title;
        
        $seoContents = null;
        $seoImage = null;
        
        // If the event has a custom SEO content, use it
        if (isset(json_decode($event->description)->seo_content)) {
            $seoContents = json_decode($event->description)->seo_content;
            if (isset($seoContents->image) && $seoContents->image) {
                $seoImage = getImage(getFilePath('seo') . '/' . $seoContents->image, getFileSize('seo'));
            }
        }
        
        $gallery = EventGallery::where('eventId', $id)->get();
        $applicantsCount = EventApplicant::where('eventId', $id)->count();
        
        // Check if event is open for booking
        $isOpenForBooking = $event->status === 'upcoming' && $event->startDate > Carbon::now();
        
        // Get related events
        $relatedEvents = Event::where('id', '!=', $id)
            ->where('status', 'upcoming')
            ->orderBy('startDate', 'asc')
            ->limit(3)
            ->get();
        
        return view('event_details', compact(
            'pageTitle', 
            'event', 
            'seoContents', 
            'seoImage', 
            'gallery', 
            'applicantsCount', 
            'isOpenForBooking',
            'relatedEvents'
        ));
    }

    public function eventBookSubmit(Request $request, $id)
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

        // Create admin notification
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'New event registration: ' . $event->title;
        $adminNotification->click_url = urlPath('admin.event.applicants', $id);
        $adminNotification->save();

        $notify[] = ['success', 'Your registration has been submitted successfully!'];
        return back()->withNotify($notify);
    }

    public function eventAddToCalendar($id)
    {
        $event = Event::findOrFail($id);
        
        // Format dates for Google Calendar
        $startDate = Carbon::parse($event->startDate)->format('Ymd\THis\Z');
        $endDate = Carbon::parse($event->endDate)->format('Ymd\THis\Z');
        
        // Get event description
        $description = '';
        if ($event->description) {
            $descData = json_decode($event->description);
            if (isset($descData->short_description)) {
                $description = strip_tags($descData->short_description);
            }
        }
        
        // Create Google Calendar URL
        $url = "https://calendar.google.com/calendar/render?action=TEMPLATE";
        $url .= "&text=" . urlencode($event->title);
        $url .= "&dates=" . $startDate . "/" . $endDate;
        $url .= "&details=" . urlencode($description ?: $event->title);
        $url .= "&location=" . urlencode($event->location);
        $url .= "&sf=true&output=xml";
        
        return redirect($url);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        
        $seoContents = null;
        $seoImage = null;
        
        if ($policy->seo_content) {
            $seoContents = $policy->seo_content;
            $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        }
        
        return view('policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogs()
    {
        $pageTitle = 'Announcements';
        $sections = Page::where('slug', 'announcements')->first();
        return view('blogs', compact('pageTitle', 'sections'));
    }

    public function blogDetails($slug)
    {
        $blog = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $pageTitle = $blog->data_values->title;
        
        $seoContents = null;
        $seoImage = null;
        
        if ($blog->seo_content) {
            $seoContents = $blog->seo_content;
            $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        }
        
        return view('blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if(!$cookieContent || $cookieContent->data_values->status != Status::ENABLE, 404);
        
        $pageTitle = 'Cookie Policy';
        $cookie = $cookieContent;
        return view('cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        $imgWidth = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text = $imgWidth . '×' . $imgHeight;
        $fontFile = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize = round(($imgWidth - 50) / 8);
        
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX = ($imgWidth - $textWidth) / 2;
        $textY = ($imgHeight + $textHeight) / 2;
        
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('maintenance', compact('pageTitle', 'maintenance'));
    }

    public function registerDomain(Request $request)
    {
        $pageTitle = 'Register New Domain';
        $domain = strtolower($request->domain);
        $result = [];

        if ($domain) {
            $request->validate([
                'domain' => ['regex:/^[a-zA-Z0-9.-]+$/']
            ]);

            $defaultDomainRegister = DomainRegister::getDefault();
            if (!$defaultDomainRegister) {
                $notify[] = ['info', 'There is no default domain register, please setup default domain register'];
                return redirect()->route('register.domain')->withNotify($notify);
            }
            
            $request->merge(['domain' => $domain]);

            $register = new Register($defaultDomainRegister->alias);
            $register->command = 'searchDomain';
            $register->domain = $domain;
            $execute = $register->run();

            if (!$execute['success']) {
                $notify = [];
                foreach ((array) $execute['message'] as $message) {
                    $notify[] = ['error', $message];
                }
                return redirect()->route('register.domain')->withNotify($notify);
            }

            if (@$execute['data']['status'] == 'ERROR') {
                $notify[] = ['error', $execute['data']['message']];
                return redirect()->route('register.domain')->withNotify($notify);
            }

            $result = $execute;
        }

        return view('register_domain', compact('pageTitle', 'result'));
    }

    public function serviceCategory($slug = null)
    {
        $serviceCategory = ServiceCategory::active()
            ->when($slug, function ($query) use ($slug) {
                $query->where('slug', $slug);
            })
            ->firstOrFail();

        $pageTitle = $serviceCategory->name;
        return view('service_category', compact('pageTitle', 'serviceCategory'));
    }

    public function productConfigure($categorySlug, $productSlug, $id)
    {
        $product = Product::active()
            ->where('id', $id)
            ->whereHas('serviceCategory', function ($query) {
                $query->where('status', Status::ENABLE);
            })
            ->whereHas('price', function ($query) {
                $query->where('status', Status::ENABLE);
            })
            ->with('getConfigs.activeGroup.activeOptions.activeSubOptions.getOnlyPrice')
            ->firstOrFail();

        $domains = [];
        $pageTitle = 'Product Configure';

        if ($product->domain_register) {
            $domains = DomainSetup::active()->orderBy('id', 'DESC')->with('pricing')->get();
        }

        return view('product_configure', compact('product', 'pageTitle', 'domains'));
    }

    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255|unique:subscribers,email'
        ]);

        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $newSubscriber = new Subscriber();
        $newSubscriber->email = $request->email;
        $newSubscriber->save();

        return response()->json(['success' => true, 'message' => 'Thank you, we will notice you our latest news']);
    }

    public function searchDomain(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => ['required', 'regex:/^[a-zA-Z0-9.-]+$/']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => [$validator->errors()->all()],
            ]);
        }

        $domain = strtolower($request->domain);
        $request->merge(['domain' => $domain]);

        $defaultDomainRegister = DomainRegister::getDefault();
        if (!$defaultDomainRegister) {
            return response()->json([
                'success' => false,
                'message' => 'There is no default domain register, Please setup default domain register'
            ]);
        }

        $register = new Register($defaultDomainRegister->alias);
        $register->command = 'searchDomain';
        $register->domain = $domain;
        $execute = $register->run();

        if (!$execute['success']) {
            return response()->json([
                'success' => false,
                'message' => $execute['message']
            ]);
        }

        if (@$execute['data']['status'] == 'ERROR') {
            return response()->json([
                'success' => false,
                'message' => $execute['data']['message']
            ]);
        }

        return response()->json([
            'success' => true,
            'result' => $execute
        ]);
    }
}