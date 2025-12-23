<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    
    public function dashboard()
    {
        $pageTitle = 'Dashboard';

        // User Info
        $widget['total_users'] = User::count();
        $widget['verified_users'] = User::active()->count();
        $widget['email_unverified_users'] = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();

        // Simplified dashboard - remove hosting/domain specific stats
        $statistics = [
            'count_active_service' => 0,
            'count_domain_service' => 0,
        ];

        $invoiceStatistics = (object) [
            'total_paid' => 0,
            'total_unpaid' => 0,
            'total_payment_pending' => 0,
            'total_refunded' => 0,
            'unpaid' => 0
        ];

        $orderStatistics = (object) [
            'total' => 0,
            'total_active' => 0,
            'total_pending' => 0,
            'total_cancelled' => 0,
            'pending' => 0
        ];

        return view('admin.dashboard', compact('pageTitle', 'widget', 'invoiceStatistics', 'orderStatistics', 'statistics'));
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
        $notifications = AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
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
        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASECODE');
        $url = "https://license.viserlab.com/issue/get?" . http_build_query($arr);
        $response = CurlRequest::curlContent($url);
        $response = json_decode($response);
        if (!$response || !@$response->status || !@$response->message) {
            return to_route('admin.dashboard')->withErrors('Something went wrong');
        }
        if ($response->status == 'error') {
            return to_route('admin.dashboard')->withErrors($response->message);
        }
        $reports = $response->message[0];
        return view('admin.reports', compact('reports', 'pageTitle'));
    }

    public function reportSubmit(Request $request)
    {
        $request->validate([
            'type' => 'required|in:bug,feature',
            'message' => 'required',
        ]);
        $url = 'https://license.viserlab.com/issue/add';

        $arr['app_name'] = systemDetails()['name'];
        $arr['app_url'] = env('APP_URL');
        $arr['purchase_code'] = env('PURCHASECODE');
        $arr['req_type'] = $request->type;
        $arr['message'] = $request->message;
        $response = CurlRequest::curlPostContent($url, $arr);
        $response = json_decode($response);
        if (!$response || !@$response->status || !@$response->message) {
            return to_route('admin.dashboard')->withErrors('Something went wrong');
        }
        if ($response->status == 'error') {
            return back()->withErrors($response->message);
        }
        $notify[] = ['success', $response->message];
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
            'model_type' => 'required|in:service_category,product',
        ]);
     
        if (!$validator->passes()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
     
        // Simplified - always return OK for now since we removed service categories
        return ['success' => true, 'message' => 'OK'];
    }

    public function orderStatistics(Request $request)
    {
        // Simplified - return empty stats for now
        if ($request->time == 'year') {
            $type = 'monthname';
        } elseif ($request->time == 'month') {
            $type = 'date';
        } elseif ($request->time == 'week') {
            $type = 'dayname';
        } else {
            $type = 'hour';
        }

        $orders = collect();
        $totalOrders = 0;

        return [
            'orders' => $orders,
            'total_orders' => $totalOrders,
        ];
    }
    
    public function services()
    {
        $pageTitle = 'All Services';
        $services = collect(); // Empty collection for now
        return view('admin.services', compact('pageTitle', 'services'));
    }

    public function domains()
    {
        $pageTitle = 'All Domains';
        $domains = collect(); // Empty collection for now
        return view('admin.domains', compact('pageTitle', 'domains'));
    }
}