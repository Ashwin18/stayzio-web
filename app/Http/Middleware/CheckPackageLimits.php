<?php

namespace App\Http\Middleware;

use App\Http\Helpers\VendorPermissionHelper;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Vendor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class CheckPackageLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $feature, $method)
    {
        if (Auth::check()) {

            if (Auth::guard('vendor')->user()) {
                $vendor = Vendor::find(Auth::guard('vendor')->user()->id);
            } else {
                return redirect()->route('vendor.dashboard');
            }
            $package = VendorPermissionHelper::currentPackagePermission($vendor->id);
            // No subscription model — always use free Package 9 as fallback
            if (empty($package)) {
                $package = \App\Models\Package::find(9);
            }
            if (empty($package)) {
                // Package 9 missing from DB - create a virtual unlimited package
                $package = new \App\Models\Package([
                    'number_of_hotel'              => 9999,
                    'number_of_room'               => 9999,
                    'number_of_bookings'           => 9999999,
                    'number_of_amenities_per_hotel'=> 9999,
                    'number_of_amenities_per_room' => 9999,
                    'number_of_images_per_hotel'   => 9999,
                    'number_of_images_per_room'    => 9999,
                    'number_of_listing'            => 999999,
                    'features'                     => '[]',
                ]);
            }

            $vendorTotalHotel =  vendorTotalAddedHotel($vendor->id);
            $vendorTotalRoom =  vendorTotalAddedRoom($vendor->id);

            if ($method == 'store') {

                if ($feature == 'hotel') {

                    if (($package->number_of_hotel > $vendorTotalHotel) && $this->checkFeaturesNotDowngraded($vendor->id, $feature, $package, $vendorTotalHotel)) {
                        return $next($request);
                    } else {
                        return response()->json('downgrade');
                    }
                }
                if ($feature == 'room') {

                    if (($package->number_of_room > $vendorTotalRoom) && $this->checkFeaturesNotDowngraded($vendor->id, $feature, $package, $vendorTotalRoom)) {
                        return $next($request);
                    } else {
                        return response()->json('downgrade');
                    }
                }
            }

            if ($method == 'update') {

                if ($feature == 'hotel') {

                    if (($package->number_of_hotel >= $vendorTotalHotel) && $this->checkFeaturesNotDowngraded($vendor->id, $feature, $package, $vendorTotalHotel)) {
                        return $next($request);
                    } else {
                        return response()->json('downgrade');
                    }
                }
                if ($feature == 'room') {

                    if (($package->number_of_room >= $vendorTotalRoom) && $this->checkFeaturesNotDowngraded($vendor->id, $feature, $package, $vendorTotalRoom)) {
                        return $next($request);
                    } else {
                        return response()->json('downgrade');
                    }
                }
            }
        }
    }
    private function checkFeaturesNotDowngraded($vendorId, $feature, $package, $userFeaturesCount)
    {
        $return = true;
        $vendor = Vendor::find(Auth::guard('vendor')->user()->id);
        $vendorTotalHotel = vendorTotalAddedHotel($vendor->id);
        $vendorTotalRoom = vendorTotalAddedRoom($vendor->id);

        $features = json_decode($package->features);

        if ($feature != 'hotel') {
            if ($package->number_of_listing != 999999 && $package->number_of_hotel < $vendorTotalHotel) {
                return  $return = false;
            }
        }
        if ($feature != 'room') {
            if ($package->number_of_listing != 999999 && $package->number_of_room < $vendorTotalRoom) {
                return  $return = false;
            }
        }

        // hotel images check 
        $hotelImages = Hotel::with(['hotel_galleries'])->where('vendor_id', $vendorId)->get();
        if ($hotelImages) {
            foreach ($hotelImages as $hotelImage) {
                $hotelImgCount = $hotelImage->hotel_galleries()->count();

                if ($package->number_of_images_per_hotel != 999999 && ($package->number_of_images_per_hotel < $hotelImgCount)) {
                    return  $return = false;
                }
            }
        }

        // room images check 
        $roomImages = Room::with(['room_galleries'])->where('vendor_id', $vendorId)->get();
        if ($roomImages) {
            foreach ($roomImages as $roomImage) {
                $roomImgCount = $roomImage->room_galleries()->count();

                if ($package->number_of_images_per_room != 999999 && ($package->number_of_images_per_room < $roomImgCount)) {
                    return  $return = false;
                }
            }
        }

        // hotel amenities check 
        $hotelAmenities = Hotel::with('hotel_contents')->where('vendor_id', $vendorId)->select('id')->get();
        if ($hotelAmenities) {
            foreach ($hotelAmenities as $hotelAmenitie) {

                foreach ($hotelAmenitie->hotel_contents as $content) {
                    if ($content->amenities) {
                        $amenities = json_decode($content->amenities);

                        if ($package->number_of_amenities_per_hotel < count($amenities)) {
                            return  $return = false;
                        }
                    }
                }
            }
        }

        // room amenities check 
        $roomAmenities = Room::with('room_content')->where('vendor_id', $vendorId)->select('id')->get();
        if ($roomAmenities) {
            foreach ($roomAmenities as $roomAmenitie) {

                foreach ($roomAmenitie->room_content as $content) {
                    if ($content->amenities) {
                        $amenities = json_decode($content->amenities);

                        if ($package->number_of_amenities_per_room < count($amenities)) {
                            return  $return = false;
                        }
                    }
                }
            }
        }

        return $return;
    }
}
