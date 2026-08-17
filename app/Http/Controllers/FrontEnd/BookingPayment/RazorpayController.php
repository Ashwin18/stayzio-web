<?php

namespace App\Http\Controllers\FrontEnd\BookingPayment;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayController extends Controller
{
    private $key, $secret, $api;

    public function __construct()
    {
        $data = OnlineGateway::whereKeyword('razorpay')->first();
        $razorpayData = json_decode($data->information, true);

        $this->key = $razorpayData['key'];

        $this->secret = $razorpayData['secret'];

        $this->api = new Api($this->key, $this->secret);
    }

    public function index(Request $request, $paymentFor)
    {

        if ($request->session()->has('price')) {
            $priceId = $request->session()->get('price');
        } else {
            Session::flash('error', 'Something went wrong!');

            return redirect()->back();
        }

        $bookingProcess = new BookingController();

        // do calculation
        $calculatedData = $bookingProcess->calculation($request, $priceId);


        $currencyInfo = $this->getCurrencyInfo();

        // checking whether the currency is set to 'INR' or not
        if ($currencyInfo->base_currency_text !== 'INR') {
            return redirect()->back()->with('error', 'Invalid currency for razorpay payment.')->withInput();
        }

        $arrData = $bookingProcess->timeCheck($request, 'Razorpay');

        $title = 'Room Booking';
       
        $notifyURL = route('frontend.room.room_booking.razorpay.notify');

        // create order data
        $orderData = [
            'receipt'         => $title,
            'amount'          => intval($calculatedData['grandTotal'] * 100),
            'currency'        => 'INR',
            'payment_capture' => 1 // auto capture
        ];

        $razorpayOrder = $this->api->order->create($orderData);

        $webInfo = Basic::select('website_title')->first();

        $customerName = $request['booking_name'] ;
        $customerEmail = $request['booking_email'];
        $customerPhone = $request['booking_phone'];

        // create checkout data
        $checkoutData = [
            'key'               => $this->key,
            'amount'            => $orderData['amount'],
            'name'              => $webInfo->website_title,
            'description'       => $title . ' via Razorpay.',
            'prefill'           => [
                'name'              => $customerName,
                'email'             => $customerEmail,
                'contact'           => $customerPhone
            ],
            'order_id'          => $razorpayOrder->id
        ];
    
        $jsonData = json_encode($checkoutData);

        // put some data in session before redirect to razorpay url
        $request->session()->put('paymentFor', $paymentFor);   
        $request->session()->put('arrData', $arrData);
        $request->session()->put('razorpayOrderId', $razorpayOrder->id);

        return view('frontend.payment.razorpay', compact('jsonData', 'notifyURL'));
    }

    public function notify(Request $request)
    {
        // get the information from session
        $payment = $request->session()->get('payment');
        $arrData = $request->session()->get('arrData');

        $razorpayOrderId = $request->session()->get('razorpayOrderId');

        $urlInfo = $request->all();

        // assume that the transaction was successful
        $success = true;

        /**
         * either razorpay_order_id or razorpay_subscription_id must be present.
         * the keys of $attributes array must be follow razorpay convention.
         */
        try {
            $attributes = [
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_payment_id' => $urlInfo['razorpayPaymentId'],
                'razorpay_signature' => $urlInfo['razorpaySignature']
            ];

            $this->api->utility->verifyPaymentSignature($attributes);
        } catch (SignatureVerificationError $e) {
            $success = false;
        }

        if ($success === true) {


            $bookingProcess = new BookingController();

            // store all data in the database
            $bookingInfo = $bookingProcess->storeData($arrData);


            // generate an invoice in pdf format 
            $invoice = $bookingProcess->generateInvoice($bookingInfo);

            // then, update the invoice field info in database 
            $bookingInfo->update(['invoice' => $invoice]);

            // Update inventory: increment rooms_sold on successful payment (both blanket count and per-duration count)
            if ($bookingInfo->check_in_date && $bookingInfo->hotel_id) {
                $checkInDate = date('Y-m-d', strtotime($bookingInfo->check_in_date));
                $inv = \Illuminate\Support\Facades\DB::table('hotel_daily_inventories')
                    ->where('hotel_id', $bookingInfo->hotel_id)
                    ->where('booking_date', $checkInDate)
                    ->first();
                if ($inv) {
                    $roomQty = $bookingInfo->room_qty ?? 1;
                    $bookedHour = (int)($bookingInfo->hour ?? 0);
                    $durationField = $bookedHour <= 3 ? 'rooms_sold_3hrs' : ($bookedHour <= 6 ? 'rooms_sold_6hrs' : 'rooms_sold_fullday');
                    $currentDurationSold = $inv->{$durationField} ?? 0;

                    \Illuminate\Support\Facades\DB::table('hotel_daily_inventories')
                        ->where('id', $inv->id)
                        ->update([
                            'rooms_sold'     => $inv->rooms_sold + $roomQty,
                            'bookings_count' => $inv->bookings_count + 1,
                            'status'         => (($inv->rooms_sold + $roomQty) >= $inv->total_rooms)
                                                ? 'Sold Out' : 'Available',
                            $durationField   => $currentDurationSold + $roomQty,
                            'updated_at'     => now(),
                        ]);
                }
            }

            // Fire admin notification: booking confirmed
            try {
                \App\Models\AdminNotification::fire(
                    \App\Models\AdminNotification::TYPE_BOOKING_CONFIRMED,
                    'New Booking Confirmed',
                    'Booking #' . $bookingInfo->order_number . ' confirmed. Amount: ' .
                      ($bookingInfo->currency_symbol ?? '₹') . number_format($bookingInfo->grand_total, 0) .
                      '. Hotel: ' . (\App\Models\HotelContent::where('hotel_id', $bookingInfo->hotel_id)
                        ->whereHas('language', fn($q) => $q->where('is_default', 1))
                        ->value('title') ?? 'Hotel #' . $bookingInfo->hotel_id),
                    route('admin.room_bookings.all_bookings', ['language' => 'en']),
                    $bookingInfo->id
                );
            } catch (\Exception $e) {}

            // send a mail to the customer with the invoice
            $bookingProcess->prepareMailForCustomer($bookingInfo);


            // send a mail to the vendor with the invoice
            $bookingProcess->prepareMailForvendor($bookingInfo);

            //tranction part
            $vendor_id = $bookingInfo->vendor_id;


            //calculate commission
            if ($vendor_id == 0) {
                $commission = $bookingInfo->grand_total;
            } else {
                $commission = 0;
            }

            //get vendor
            $vendor = Vendor::where('id', $vendor_id)->first();

            //add blance to admin revinue
            $earning = Basic::first();

            if ($vendor_id == 0) {
                $earning->total_earning = $earning->total_earning + $bookingInfo->grand_total;
            } else {
                $earning->total_earning = $earning->total_earning + $commission;
            }
            $earning->save();

            //store Balance  to vendor
            if ($vendor) {
                $pre_balance = $vendor->amount;
                $vendor->amount = $vendor->amount + ($bookingInfo->grand_total - ($commission));
                $vendor->save();
                $after_balance = $vendor->amount;
            } else {
                $after_balance = NULL;
                $pre_balance = NULL;
            }
            //calculate commission end

            $data = [
                    'transcation_id' => time(),
                    'booking_id' => $bookingInfo->id,
                    'transcation_type' => 'room_booking',
                    'user_id' => null,
                    'vendor_id' => $vendor_id,
                    'payment_status' => 1,
                    'payment_method' => $bookingInfo->payment_method,
                    'grand_total' => $bookingInfo->grand_total,
                    'commission' => $commission,
                    'pre_balance' => $pre_balance,
                    'after_balance' => $after_balance,
                    'gateway_type' => $bookingInfo->gateway_type,
                    'currency_symbol' => $bookingInfo->currency_symbol,
                    'currency_symbol_position' => $bookingInfo->currency_symbol_position,
                ];
            store_transaction($data);


            // remove all session data
            $request->session()->forget('price');
            $request->session()->forget('checkInTime');
            $request->session()->forget('checkInDate');
            $request->session()->forget('adult');
            $request->session()->forget('children');
            $request->session()->forget('roomDiscount');
            $request->session()->forget('takeService');
            $request->session()->forget('serviceCharge');

            return redirect()->route('frontend.room_booking.complete', ['type' => 'online']);
        } else {
            Session::flash('success', 'Something Went Wrong');

            return redirect()->route('frontend.room_booking.cancel');
        }
    }
}
