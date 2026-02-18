<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Donor;
use App\Models\Fundraiser;
use App\Models\CauseDonation;
use App\Models\Event;
use App\Models\EventApplicant;
use App\Models\Service;
use App\Models\ServiceStory;
use App\Models\TeamMember;
use App\Models\TeamCategory;
use App\Models\Subscriber;
use App\Models\Frontend;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    
    public function dashboard()
    {
        $pageTitle = 'Dashboard';

        // Donor Stats
        $widget['total_donors'] = Donor::count();
        $widget['new_donors_this_month'] = Donor::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $widget['anonymous_donors'] = Donor::where('is_anonymous', 1)->count();

        // Fundraiser/Campaign Stats
        $widget['total_campaigns'] = Fundraiser::count();
        $widget['active_campaigns'] = Fundraiser::where('status', 'active')->count();
        $widget['pending_campaigns'] = Fundraiser::where('status', 'pending')->count();
        $widget['completed_campaigns'] = Fundraiser::where('status', 'completed')->count();
        $widget['draft_campaigns'] = Fundraiser::where('status', 'draft')->count();
        $widget['featured_campaigns'] = Fundraiser::where('is_featured', 1)->count();
        
        // Total campaign goal and raised
        $widget['total_goal_amount'] = Fundraiser::sum('target_amount');
        $widget['total_raised_amount'] = Fundraiser::sum('raised_amount');

        // Donation Stats
        $widget['total_donations'] = CauseDonation::count();
        $widget['total_donation_amount'] = CauseDonation::where('payment_status', 'completed')->sum('amount');
        $widget['monthly_donation_amount'] = CauseDonation::where('payment_status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $widget['pending_donations'] = CauseDonation::where('payment_status', 'pending')->count();
        $widget['pending_donation_amount'] = CauseDonation::where('payment_status', 'pending')->sum('amount');
        $widget['failed_donations'] = CauseDonation::where('payment_status', 'failed')->count();

        // Event Stats
        $widget['total_events'] = Event::count();
        $widget['upcoming_events'] = Event::where('status', 'upcoming')->count();
        $widget['ongoing_events'] = Event::where('status', 'ongoing')->count();
        $widget['completed_events'] = Event::where('status', 'completed')->count();
        $widget['cancelled_events'] = Event::where('status', 'cancelled')->count();
        $widget['total_applicants'] = EventApplicant::count();
        $widget['physical_events'] = Event::where('type', 'physical')->count();
        $widget['virtual_events'] = Event::where('type', 'virtual')->count();

        // Service Stats
        $widget['total_services'] = Service::count();
        $widget['active_services'] = Service::where('status', 'active')->count();
        $widget['inactive_services'] = Service::where('status', 'inactive')->count();
        $widget['total_service_stories'] = ServiceStory::count();

        // Team Stats
        $widget['total_team_members'] = TeamMember::count();
        $widget['active_team_members'] = TeamMember::where('status', 'active')->count();
        $widget['team_categories'] = TeamCategory::count();

        // Subscriber Stats
        $widget['total_subscribers'] = Subscriber::count();

        // Recent Donations
        $recentDonations = CauseDonation::with('fundraiser')
            ->latest()
            ->limit(10)
            ->get();

        // Top Campaigns by donations
        $topCampaigns = Fundraiser::withCount('donations as donations_count')
            ->withSum('donations as total_raised', 'amount')
            ->where('status', 'active')
            ->orderByDesc('total_raised')
            ->limit(5)
            ->get();

        // Recent Campaigns
        $recentCampaigns = Fundraiser::latest()
            ->limit(5)
            ->get();

        // Recent Events
        $recentEvents = Event::latest()
            ->limit(5)
            ->get();

        // Chart Data - Monthly Donations for last 6 months
        $months = [];
        $donationData = [];
        $campaignData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $amount = CauseDonation::where('payment_status', 'completed')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('amount');
                
            $donationData[] = round($amount, 2);
            
            $campaignCount = Fundraiser::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
                
            $campaignData[] = $campaignCount;
        }

        // Campaign status distribution for pie chart
        $campaignStatusData = [
            'active' => Fundraiser::where('status', 'active')->count(),
            'pending' => Fundraiser::where('status', 'pending')->count(),
            'completed' => Fundraiser::where('status', 'completed')->count(),
            'cancelled' => Fundraiser::where('status', 'cancelled')->count(),
            'draft' => Fundraiser::where('status', 'draft')->count(),
        ];

        // Donation payment status distribution
        $donationStatusData = [
            'completed' => CauseDonation::where('payment_status', 'completed')->count(),
            'pending' => CauseDonation::where('payment_status', 'pending')->count(),
            'failed' => CauseDonation::where('payment_status', 'failed')->count(),
            'refunded' => CauseDonation::where('payment_status', 'refunded')->count(),
        ];

        // Event status distribution
        $eventStatusData = [
            'upcoming' => Event::where('status', 'upcoming')->count(),
            'ongoing' => Event::where('status', 'ongoing')->count(),
            'completed' => Event::where('status', 'completed')->count(),
            'cancelled' => Event::where('status', 'cancelled')->count(),
        ];

        return view('admin.dashboard', compact(
            'pageTitle', 
            'widget', 
            'recentDonations',
            'topCampaigns',
            'recentCampaigns',
            'recentEvents',
            'months',
            'donationData',
            'campaignData',
            'campaignStatusData',
            'donationStatusData',
            'eventStatusData'
        ));
    }

    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'month');
        $campaignId = $request->get('campaign_id');
        
        $query = CauseDonation::where('payment_status', 'completed');
        
        if ($campaignId) {
            $query->where('fundraiser_id', $campaignId);
        }
        
        $labels = [];
        $data = [];
        
        switch ($period) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $labels[] = $date->format('D');
                    
                    $amount = (clone $query)
                        ->whereDate('created_at', $date->format('Y-m-d'))
                        ->sum('amount');
                        
                    $data[] = round($amount, 2);
                }
                break;
                
            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $month = now()->subMonths($i);
                    $labels[] = $month->format('M Y');
                    
                    $amount = (clone $query)
                        ->whereMonth('created_at', $month->month)
                        ->whereYear('created_at', $month->year)
                        ->sum('amount');
                        
                    $data[] = round($amount, 2);
                }
                break;
                
            default: // month
                $daysInMonth = now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $labels[] = $i;
                    
                    $amount = (clone $query)
                        ->whereDay('created_at', $i)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('amount');
                        
                    $data[] = round($amount, 2);
                }
                break;
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data)
        ]);
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $admin = auth('admin')->user();
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.profile', compact('pageTitle', 'admin', 'countries'));
    }

    public function profileUpdate(Request $request)
    {
        $isSuperAdmin = isSuperAdmin();
        $validation = [
            'name' => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ];

        if ($isSuperAdmin) {
            $validation['mobile'] = ['required', 'string', 'max:50', 'regex:/^\d{3}\.\d+$/'];
            $validation['country'] = 'required';
        }

        $request->validate($validation);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        if ($isSuperAdmin) {
            $address = [
                'address' => @$request->address,
                'state' => @$request->state,
                'zip' => @$request->zip,
                'country' => @$request->country,
                'city' => @$request->city,
            ];
    
            $user->mobile = '+' . $request->mobile;
            $user->address = $address;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }

    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.password')->withNotify($notify);
    }

    public function notifications()
    {
        $notifications = AdminNotification::orderBy('id', 'desc')->paginate(getPaginate());
        $hasUnread = AdminNotification::where('is_read', Status::NO)->exists();
        $hasNotification = AdminNotification::exists();
        $pageTitle = 'Notifications';
        return view('admin.notifications', compact('pageTitle', 'notifications', 'hasUnread', 'hasNotification'));
    }

    public function notificationRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }

    public function requestReport()
    {
        $pageTitle = 'Your Listed Report & Request';
        $notify[] = ['info', 'This feature has been disabled.'];
        return back()->withNotify($notify);
    }

    public function reportSubmit(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bug,feature',
            'message' => 'required',
        ]);
        
        $notify[] = ['info', 'This feature has been disabled.'];
        return back()->withNotify($notify);
    }

    public function readAllNotification()
    {
        AdminNotification::where('is_read', Status::NO)->update([
            'is_read' => Status::YES
        ]);
        $notify[] = ['success', 'Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAllNotification()
    {
        AdminNotification::truncate();
        $notify[] = ['success', 'Notifications deleted successfully'];
        return back()->withNotify($notify);
    }

    public function deleteSingleNotification($id)
    {
        AdminNotification::where('id', $id)->delete();
        $notify[] = ['success', 'Notification deleted successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function automationErrors()
    {
        $pageTitle = 'Automation Errors';
        $notifications = AdminNotification::orderBy('id', 'desc')->where('api_response', 1)->paginate(getPaginate());
        return view('admin.automation_errors', compact('pageTitle', 'notifications'));
    }

    public function deleteAutomationErrors()
    {
        AdminNotification::where('api_response', 1)->delete();
        $notify[] = ['success', 'Automation errors deleted successfully'];
        return back()->withNotify($notify);
    }

    public function readAutomationErrors()
    {
        AdminNotification::where('api_response', 1)->where('is_read', 0)->update([
            'is_read' => 1
        ]);
        $notify[] = ['success', 'Automation errors read successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAutomationError($id)
    {
        $data = AdminNotification::where('api_response', 1)->findOrFail($id);
        $data->delete();
        $notify[] = ['success', 'An automation error was deleted successfully'];
        return back()->withNotify($notify);
    }

    public function checkSlug(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'input' => 'required',
            'model_type' => 'required|in:fundraiser,event,service,team_category',
        ]);
     
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        
        // Check uniqueness based on model type
        $exists = false;
        
        switch ($request->model_type) {
            case 'fundraiser':
                $exists = Fundraiser::where('slug', $request->input)->exists();
                break;
            case 'event':
                $exists = Event::where('slug', $request->input)->exists();
                break;
            case 'service':
                $exists = Service::where('slug', $request->input)->exists();
                break;
            case 'team_category':
                $exists = TeamCategory::where('slug', $request->input)->exists();
                break;
        }
        
        if ($exists) {
            return response()->json(['error' => ['Slug already exists. Please choose another.']]);
        }
        
        return ['success' => true, 'message' => 'OK'];
    }
}