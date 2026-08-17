<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Helpers\BasicMailer;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\UploadFile;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\Journal\Blog;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Room;
use App\Models\Subscriber;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vendor;
use App\Rules\ImageMimeTypeRule;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function authentication(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        // get the username and password which has provided by the admin
        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $authAdmin = Auth::guard('admin')->user();

            // check whether the admin's account is active or not
            if ($authAdmin->status == 0) {
                Session::flash('alert', __('Sorry, your account has been deactivated') . '!');

                // logout auth admin as condition not satisfied
                Auth::guard('admin')->logout();

                return redirect()->back();
            } else {
                return redirect()->route('admin.dashboard');
            }
        } else {
            return redirect()->back()->with('alert',  __('Oops, username or password does not match') . '!');
        }
    }

    public function forgetPassword()
    {
        return view('admin.forget-password');
    }

    public function forgetPasswordMail(Request $request)
    {
        // validation start
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
                new MatchEmailRule('admin')
            ]
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        // validation end

        // create a new password and store it in db
        $newPassword = uniqid();

        $admin = Admin::query()->where('email', '=', $request->email)->first();

        $admin->update([
            'password' => Hash::make($newPassword)
        ]);

        // prepare a mail to send newly created password to admin
        $mailData['subject'] = 'Reset Password';

        $mailData['body'] = 'Hi ' . $admin->first_name . ',<br/><br/>Your password has been reset. Your new password is: ' . $newPassword . '<br/>Now, you can login with your new password. You can change your password later.<br/><br/>Thank you.';

        $mailData['recipient'] = $admin->email;

        $mailData['sessionMessage'] = 'A mail has been sent to your email address.';

        BasicMailer::sendMail($mailData);

        return redirect()->back();
    }

    public function redirectToDashboard()
    {
        $now        = Carbon::now();
        $today      = $now->toDateString();
        $monthStart = $now->copy()->startOfMonth()->toDateString();

        // ── Legacy variables (blade references these) ──
        $information['authAdmin']         = Auth::guard('admin')->user();
        $information['totalBlog']         = Blog::query()->count();
        $information['totalUser']         = User::query()->count();
        $information['totalSubscriber']   = Subscriber::query()->count();
        $information['payment_log']       = Membership::where('vendor_id', '!=', 0)->count();
        $information['vendors']           = Vendor::count();
        $information['totalRooms']        = Room::count();
        $information['earning']           = Basic::first();
        $information['transcation_count'] = Transaction::count();

        // ── Payment status can be 1 (int) OR 'paid' (string) ──
        $paidCondition = function($q) {
            $q->where('payment_status', 1)->orWhere('payment_status', 'paid');
        };

        // ── Today ──
        $information['todayBookings'] = \App\Models\Booking::whereDate('created_at', $today)->count();
        $information['todayRevenue']  = \App\Models\Booking::whereDate('created_at', $today)
            ->where(function($q){ $q->where('payment_status',1)->orWhere('payment_status','paid'); })
            ->sum('grand_total');
        $information['todayCheckins']  = \App\Models\Booking::where('check_in_date', $today)->count();
        $information['todayCheckouts'] = \App\Models\Booking::where('check_out_date', $today)->count();

        // ── Month ──
        $information['monthBookings'] = \App\Models\Booking::whereDate('created_at', '>=', $monthStart)->count();
        $information['monthRevenue']  = \App\Models\Booking::whereDate('created_at', '>=', $monthStart)
            ->where(function($q){ $q->where('payment_status',1)->orWhere('payment_status','paid'); })
            ->sum('grand_total');

        // ── All time ──
        $information['totalBookings']  = \App\Models\Booking::count();
        $information['activeBookings'] = \App\Models\Booking::where(function($q){
            $q->where('payment_status',1)->orWhere('payment_status','paid');
        })->count();
        $information['totalRevenue']   = \App\Models\Booking::where(function($q){
            $q->where('payment_status',1)->orWhere('payment_status','paid');
        })->sum('grand_total');
        $information['cancelledBookings'] = \App\Models\Booking::where('order_status','cancelled')->count();
        $information['pendingBookings']   = \App\Models\Booking::where('payment_status',0)
            ->where('order_status','!=','cancelled')->count();

        // ── Slot breakdown ──
        $paidBase = \App\Models\Booking::where(function($q){
            $q->where('payment_status',1)->orWhere('payment_status','paid');
        });
        $information['slot3hrs']   = (clone $paidBase)->where('hour','<=',3)->count();
        $information['slot6hrs']   = (clone $paidBase)->whereBetween('hour',[4,6])->count();
        $information['slot12hrs']  = (clone $paidBase)->whereBetween('hour',[7,12])->count();
        $information['slotFull']   = (clone $paidBase)->where('hour','>',12)->count();
        $information['slotTotal']  = max($information['slot3hrs']+$information['slot6hrs']+$information['slot12hrs']+$information['slotFull'],1);

        // ── Hotels ──
        $information['totalHotels']    = DB::table('hotels')->count();
        $information['approvedHotels'] = DB::table('hotels')->where('approval_status',1)->count();
        $information['pendingHotels']  = DB::table('hotels')->where('approval_status',0)->count();
        $information['pendingApprovals'] = $information['pendingHotels'];

        // ── Vendors ──
        $information['pendingVendors'] = Vendor::where('status',0)->count();
        $information['activeVendors']  = Vendor::where('status',1)->count();

        // ── Customers ──
        $information['totalCustomers']        = User::count();
        $information['newCustomersThisMonth'] = User::whereDate('created_at','>=',$monthStart)->count();

        // ── Locations (city count) ──
        $information['locations'] = DB::table('cities')->count();

        // ── Recent Bookings (last 8) ──
        $information['recentBookings'] = DB::table('bookings')
            ->leftJoin('hotel_contents', function($j){
                $j->on('hotel_contents.hotel_id','=','bookings.hotel_id')
                  ->whereExists(function($q){
                      $q->select(DB::raw(1))->from('languages')
                        ->whereColumn('languages.id','hotel_contents.language_id')
                        ->where('languages.is_default',1);
                  });
            })
            ->leftJoin('room_contents', function($j){
                $j->on('room_contents.room_id','=','bookings.room_id')
                  ->whereExists(function($q){
                      $q->select(DB::raw(1))->from('languages')
                        ->whereColumn('languages.id','room_contents.language_id')
                        ->where('languages.is_default',1);
                  });
            })
            ->select(
                'bookings.id','bookings.order_number','bookings.grand_total',
                'bookings.payment_status','bookings.order_status',
                'bookings.check_in_date','bookings.check_in_time','bookings.hour',
                'bookings.created_at',
                'hotel_contents.title as hotel_name',
                'room_contents.title as room_name',
                'bookings.booking_name','bookings.booking_email'
            )
            ->latest('bookings.created_at')
            ->limit(8)
            ->get();

        // ── Monthly bookings chart (this year) ──
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[] = \App\Models\Booking::whereMonth('created_at',$m)
                ->whereYear('created_at',$now->year)->count();
        }
        $information['monthlyBookings'] = $monthlyData;

        // ── Revenue by City ──
        $cityRevenue = DB::table('bookings')
            ->join('hotel_contents','bookings.hotel_id','=','hotel_contents.hotel_id')
            ->join('languages','hotel_contents.language_id','=','languages.id')
            ->leftJoin('cities','hotel_contents.city_id','=','cities.id')
            ->where(function($q){ $q->where('bookings.payment_status',1)->orWhere('bookings.payment_status','paid'); })
            ->where('languages.is_default',1)
            ->select('cities.name as city_name', DB::raw('SUM(bookings.grand_total) as total'))
            ->groupBy('cities.id','cities.name')
            ->orderByDesc('total')->limit(6)->get();
        $maxCity = $cityRevenue->max('total') ?: 1;
        $cityRevenue = $cityRevenue->map(function($c) use ($maxCity) {
            $c->pct = round($c->total / $maxCity * 100);
            return $c;
        });
        $information['cityRevenue'] = $cityRevenue;

        // ── Top Hotels ──
        $information['topHotels'] = DB::table('bookings')
            ->join('hotel_contents','bookings.hotel_id','=','hotel_contents.hotel_id')
            ->join('languages','hotel_contents.language_id','=','languages.id')
            ->where(function($q){ $q->where('bookings.payment_status',1)->orWhere('bookings.payment_status','paid'); })
            ->where('languages.is_default',1)
            ->select('hotel_contents.title as hotel_name',
                     DB::raw('COUNT(bookings.id) as bookings'),
                     DB::raw('SUM(bookings.grand_total) as revenue'))
            ->groupBy('bookings.hotel_id','hotel_contents.title')
            ->orderByDesc('revenue')->limit(5)->get();

        // ── Top Vendors ──
        $information['topVendors'] = DB::table('vendors')
            ->leftJoin('hotels','vendors.id','=','hotels.vendor_id')
            ->selectRaw('vendors.id, vendors.username as name, COUNT(hotels.id) as hotels_count')
            ->groupBy('vendors.id','vendors.username')
            ->orderByDesc('hotels_count')->limit(5)->get();

        // ── Hotel status breakdown ──
        $information['hotelStatusBreakdown'] = DB::table('hotels')
            ->selectRaw('approval_status, COUNT(*) as count')
            ->groupBy('approval_status')->get();

        // ── Greeting ──
        $hour = $now->hour;
        $information['greeting']  = $hour < 12 ? 'morning' : ($hour < 17 ? 'afternoon' : 'evening');
        $information['adminName'] = explode(' ', ($information['authAdmin']->first_name ?? 'Admin'))[0];

        return view('admin.admin.dashboard', $information);
    }


        public function changeTheme(Request $request)
    {
        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            ['admin_theme_version' => $request->admin_theme_version]
        );

        return redirect()->back();
    }

    public function editProfile()
    {
        $adminInfo = Auth::guard('admin')->user();

        return view('admin.admin.edit-profile', compact('adminInfo'));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $rules = [];

        if (is_null($admin->image)) {
            $rules['image'] = 'required';
        }
        if ($request->hasFile('image')) {
            $rules['image'] = new ImageMimeTypeRule();
        }

        $rules['username'] = [
            'required',
            Rule::unique('admins')->ignore($admin->id),
            Rule::unique('vendors')
        ];

        $rules['email'] = [
            'required',
            Rule::unique('admins')->ignore($admin->id)
        ];

        $rules['first_name'] = 'required';

        $rules['last_name'] = 'required';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('image')) {
            $newImg = $request->file('image');
            $oldImg = $admin->image;
            $imageName = UploadFile::update(public_path('assets/img/admins/'), $newImg, $oldImg);
        }

        if ($request->show_email_address) {
            $show_email_address = 1;
        } else {
            $show_email_address = 0;
        }
        if ($request->show_phone_number) {
            $show_phone_number = 1;
        } else {
            $show_phone_number = 0;
        }


        $admin->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'image' => $request->hasFile('image') ? $imageName : $admin->image,
            'username' => $request->username,
            'email' => $request->email,
            'show_email_address' => $show_email_address,
            'phone' => $request->phone,
            'show_phone_number' => $show_phone_number,
            'address' => $request->address,
            'details' => $request->details,
        ]);

        Session::flash('success', __('Profile updated successfully') . '!');


        return redirect()->back();
    }

    public function changePassword()
    {
        return view('admin.admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => [
                'required',
                new MatchOldPasswordRule('admin')
            ],
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $admin = Auth::guard('admin')->user();

        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', __('Password updated successfully') . '!');

        return response()->json(['status' => 'success'], 200);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        // invalidate the admin's session
        $request->session()->invalidate();

        return redirect()->route('admin.login');
    }

    //membershipRequest
    public function membershipRequest()
    {
        $collections = Membership::where('memberships.status', '!=', 1)->paginate(10);
        $data['collections'] = $collections;
        return view('admin.admin.membership-request', $data);
    }
    public function membershipRequestUpdate(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);
        $vendor = Vendor::findorFail($membership->vendor_id);
        $package = Package::findOrFail($membership->package_id);
        $settings = json_decode($membership->settings, true);
        $activation = Carbon::parse($package->start_date);
        $expire = Carbon::parse($package->expire_date);

        $membership->update([
            'status' => 1,
            'modified' => 1
        ]);

        if ($request->status != 0) {
            $mailer = new MegaMailer();
            $data = [
                'toMail' => $vendor->email,
                'toName' => $vendor->fname,
                'username' => $vendor->username,
                'package_title' => $package->title,
                'package_price' => ($settings['base_currency_symbol_position'] == 'left' ? $settings['base_currency_symbol'] . ' ' : '') . $package->price . ($settings['base_currency_symbol_position'] == 'right' ? ' ' . $settings['base_currency_symbol'] : ''),
                'activation_date' => $activation->toFormattedDateString(),
                'expire_date' => Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString(),
                'website_title' => $settings['website_title'],
                'templateType' => $request->status == 1 ? 'payment_accepted_for_membership_(_offline_gateway_)' : 'payment_rejected_for_membership_(_offline_gateway_)',
            ];
            $mailer->mailFromAdmin($data);
        } else {
        }
        Session::flash('success', __('Updated payment status successfully') . '!');
        return back();
    }
    //transaction 
    public function transcation(Request $request)
    {
        $transcation_id = null;
        if ($request->filled('transcation_id')) {
            $transcation_id = $request->transcation_id;
        }

        $info['transcations'] = Transaction::when($transcation_id, function ($query) use ($transcation_id) {
            return $query->where('transcation_id', 'like', '%' . $transcation_id . '%');
        })->orderByDesc('id')->paginate(10);

        $info['memberships'] = Membership::with('vendor')->latest()->paginate(10);

        return view('admin.admin.transcation', $info);
    }
}
