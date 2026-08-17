<?php

namespace App\Http\Controllers\FrontEnd\BookingPayment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\BookingPayment\BookingController;
use App\Http\Helpers\UploadFile;
use App\Models\BookingHour;
use App\Models\Hotel;
use App\Models\HourlyRoomPrice;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\Room;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use DateTime;

class OfflineController extends Controller
{
    public function index(Request $request)
    {
        $gatewayId = $request->gateway;
        $offlineGateway = OfflineGateway::query()->findOrFail($gatewayId);

        // validation start
        if ($offlineGateway->has_attachment == 1) {
            $rules = [
                'attachment' => [
                    'required',
                    new ImageMimeTypeRule()
                ]
            ];

            $message = [
                'attachment.required' => 'Please attach your payment receipt.'
            ];

            $validator = Validator::make($request->only('attachment'), $rules, $message);

            Session::flash('gatewayId', $offlineGateway->id);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors())->withInput();
            }
        }
        // validation end

        $price_id = $request->session()->get('price');

        $Time = $request->session()->get('checkInTime');
        $Date = $request->session()->get('checkInDate');
        $adult = $request->session()->get('adult');
        $children = $request->session()->get('children');

        $hour_id = HourlyRoomPrice::findorfail($price_id)->hour_id;
        $hour = BookingHour::findorfail($hour_id)->hour;
        $vendor_id = HourlyRoomPrice::findorfail($price_id)->vendor_id;
        $hotel_id = HourlyRoomPrice::findorfail($price_id)->hotel_id;
        $room_id = HourlyRoomPrice::findorfail($price_id)->room_id;
        $preparation_time = Room::findorfail($room_id)->preparation_time;
        
        $checkInTime = date('H:i:s', strtotime($Time));
        $checkInDate = date('Y-m-d', strtotime($Date));
        $check_in_date_time = $checkInDate . ' ' . $checkInTime;

        // --- User-Driven Checkout Date Logic ---
        if ($hour == 24 && $request->session()->has('checkOutDate')) {
            // Get checkout date directly from user selection via session
            $userCheckoutDate = $request->session()->get('checkOutDate');
            $checkoutDate = date('Y-m-d', strtotime($userCheckoutDate));
            
            // Checkout time matches check-in time for exactly 24-hour intervals
            $checkoutTime = $checkInTime; 
        } else {
            // Fallback system logic for normal hourly bookings
            $startInstance = new DateTime($check_in_date_time);
            $checkoutInstance = clone $startInstance;
            $checkoutInstance->modify("+$hour hours");
            
            $checkoutDate = $checkoutInstance->format('Y-m-d');
            $checkoutTime = $checkoutInstance->format('H:i:s');
        }

        // Calculate Next Allowed Booking Time timestamp including preparation room buffers
        $checkoutDateTimeString = $checkoutDate . ' ' . $checkoutTime;
        $nextBookingInstance = new DateTime($checkoutDateTimeString);
        $nextBookingInstance->modify("+$preparation_time minutes");
        
        $next_booking_time = $nextBookingInstance->format('H:i:s');
        $check_out_date_time = $nextBookingInstance->format('Y-m-d H:i:s');
        // ------------------------------------------------------------------------------------------------

        if ($request->session()->has('price')) {
            $priceId = $request->session()->get('price');
        } else {
            Session::flash('error', 'Something went wrong!');
            return redirect()->back();
        }

        $bookingProcess = new BookingController();

        // 1. Initial pricing snapshot calculation from the base hourly rule
        $calculatedData = $bookingProcess->calculation($request, $priceId);

        // 2. --- Calculate Day Differences & Adjust Totals ---
        $date1 = new DateTime($checkInDate);
        $date2 = new DateTime($checkoutDate);
        $dayDifference = $date1->diff($date2)->days;

        // Ensure we multiply by at least 1 if they pick the same calendar day
        $daysCount = $dayDifference > 0 ? $dayDifference : 1;

        if ($daysCount > 1) {
            // Extract the single day pricing variables
            $baseRoomPrice = $calculatedData['roomPrice'];
            $serviceCharge = $calculatedData['serviceCharge'];
            $discount      = $calculatedData['discount'];

            // Calculate new multiplied room total
            $newRoomPrice = $baseRoomPrice * $daysCount;
            $newSubTotal  = $newRoomPrice + $serviceCharge - $discount;

            // Recalculate dynamic tax breakdown if system applies a rate percentage
            if ($calculatedData['total'] > 0) {
                $taxPercentage = $calculatedData['tax'] / $calculatedData['total'];
                $newTax        = $newSubTotal * $taxPercentage;
            } else {
                $newTax = $calculatedData['tax']; // Fallback directly if zeroed out
            }

            // Bind recalculated totals back into structural execution array
            $calculatedData['roomPrice']  = $newRoomPrice;
            $calculatedData['total']      = $newSubTotal;
            $calculatedData['tax']        = $newTax;
            $calculatedData['grandTotal'] = $newSubTotal + $newTax;
        }
        // ------------------------------------------------------------------------------------------------

        $directory = public_path('assets/file/attachments/room-booking/');

        // store attachment in local storage
        if ($request->hasFile('attachment')) {
            $attachmentName = UploadFile::store($directory, $request->file('attachment'));
        } else {
            $attachmentName = null;
        }
        $currencyInfo = $this->getCurrencyInfo();
        
        if (!isset($request['booking_email']) || empty($request['booking_email'])) {
            
            $request['booking_email'] = 'info@stayziohotels.com';
        }

        $arrData = array(
            'check_in_time' => $checkInTime,
            'check_in_date' =>  $checkInDate,
            'check_out_date' => $checkoutDate,
            'check_out_time' =>  $checkoutTime,
            'check_in_date_time' =>  $check_in_date_time,
            'check_out_date_time' =>  $check_out_date_time,
            'vendor_id' =>  $vendor_id,
            'hotel_id' =>  $hotel_id,
            'room_id' =>  $room_id,
            'preparation_time' =>  $preparation_time,
            'next_booking_time' =>  $next_booking_time,
            'hour' =>  $hour,
            'adult' =>  $adult,
            'children' => $children,
            'booking_name' => $request['booking_name'],
            'booking_email' => $request['booking_email'],
            'booking_phone' => $request['booking_phone'],
            'booking_address' => $request['booking_address'],
            'additional_service' => $calculatedData['additional_service'],
            'service_details' => $calculatedData['service_details'],
            'roomPrice' => $calculatedData['roomPrice'],
            'serviceCharge' => $calculatedData['serviceCharge'],
            'total' => $calculatedData['total'],
            'discount' => $calculatedData['discount'],
            'tax' => $calculatedData['tax'],
            'grandTotal' => $calculatedData['grandTotal'],
            'commission_price'=>$calculatedData['commission_price'],
            'currencyText' => $currencyInfo->base_currency_text,
            'currencyTextPosition' => $currencyInfo->base_currency_text_position,
            'currencySymbol' => $currencyInfo->base_currency_symbol,
            'currencySymbolPosition' => $currencyInfo->base_currency_symbol_position,
            'paymentMethod' => $offlineGateway->name,
            'gatewayType' => 'offline',
            'payment_status' => 0,
            'attachment' => $attachmentName
        );

        // store product order information in database
        $bookingInfo = $bookingProcess->storeData($arrData);

        $booking_id = $bookingInfo->id;
        // generate an invoice in pdf format 
        $invoice = $bookingProcess->generateInvoice($bookingInfo);
        
        // then, update the invoice field info in database 
        $bookingInfo->update(['invoice' => $invoice]);

        // send a mail to the vendor with the invoice
        $bookingProcess->prepareMailForvendor($bookingInfo);

        $request->session()->put('booking_id', $booking_id);
        $request->session()->put('invoice_id', $invoice);
        
        // remove all session data (including custom checkout date selection)
        $request->session()->forget('price');
        $request->session()->forget('checkInTime');
        $request->session()->forget('checkInDate');
        $request->session()->forget('checkOutDate'); // Clean up the new session item
        $request->session()->forget('adult');
        $request->session()->forget('children');
        $request->session()->forget('roomDiscount');
        $request->session()->forget('takeService');
        $request->session()->forget('serviceCharge');

        return redirect()->route('frontend.room_booking.complete', ['type' => 'offline_booking']);
    }
}