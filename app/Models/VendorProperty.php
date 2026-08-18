<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VendorProperty extends Model
{
    protected $table = 'vendor_properties';
    protected $guarded = [];

    protected $casts = [
        'amenities' => 'array',
        'selected_perks' => 'array',
        'selected_restrictions' => 'array',
    ];

    const STATUS_DRAFT    = 'draft';
    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_LIVE     = 'live';

    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeLive($query)
    {
        return $query->where('status', self::STATUS_LIVE);
    }

    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function isLive()
    {
        return $this->status === self::STATUS_LIVE;
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function getAmenitiesListAttribute()
    {
        $raw = $this->attributes['amenities'] ?? '[]';
        $amenities = $raw;

        for ($i = 0; $i < 3; $i++) {
            if (is_string($amenities)) {
                $decoded = json_decode($amenities, true);

                if (is_array($decoded)) {
                    $amenities = $decoded;
                    break;
                }

                if (is_string($decoded)) {
                    $amenities = $decoded;
                    continue;
                }

                break;
            }

            break;
        }

        if (!is_array($amenities)) {
            $amenities = [];
        }

        $labels = [
            'wifi' => 'Free WiFi',
            'lcd_led' => 'LCD/LED TV',
            'ac' => 'Air Conditioning',
            'water' => 'Drinking Water',
        ];

        return array_map(fn($k) => $labels[$k] ?? $k, $amenities);
    }

    public function getStatusBadgeAttribute()
    {
        $map = [
            'draft'    => ['color' => '#64748b', 'bg' => 'rgba(100,116,139,.1)', 'label' => 'Draft'],
            'pending'  => ['color' => '#d97706', 'bg' => 'rgba(217,119,6,.1)', 'label' => 'Pending Review'],
            'approved' => ['color' => '#2563eb', 'bg' => 'rgba(37,99,235,.1)', 'label' => 'Approved'],
            'rejected' => ['color' => '#dc2626', 'bg' => 'rgba(220,38,38,.1)', 'label' => 'Rejected'],
            'live'     => ['color' => '#059669', 'bg' => 'rgba(5,150,105,.1)', 'label' => 'Live'],
        ];

        return $map[$this->status] ?? $map['draft'];
    }

    public function createHotelAndRoom(array $adminData = [])
    {
        $langId = DB::table('languages')
            ->where('is_default', 1)
            ->value('id');

        $catId = DB::table('hotel_categories')
            ->where('status', 1)
            ->value('id') ?? DB::table('hotel_categories')->value('id');

        $countryId = DB::table('countries')
            ->where('name', 'like', '%India%')
            ->value('id');

        $stateId = DB::table('states')
            ->where('country_id', $countryId)
            ->where(function ($q) {
                $q->where('name', 'like', '%Tamil Nadu%')
                  ->orWhere('name', 'like', '%Tamilnadu%');
            })
            ->value('id');

        if (!$stateId) {
            $stateId = DB::table('states')
                ->where('country_id', $countryId)
                ->value('id');
        }

        $cityName = trim($this->city);

        // Match the exact city/locality for the current language.
        $cityId = DB::table('cities')
            ->where('language_id', $langId)
            ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower($cityName)])
            ->value('id');

        // Repair and reuse a legacy matching city row when possible.
        if (!$cityId) {
            $legacyCity = DB::table('cities')
                ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower($cityName)])
                ->first();

            if ($legacyCity) {
                $cityId = $legacyCity->id;

                DB::table('cities')
                    ->where('id', $cityId)
                    ->update([
                        'language_id' => $legacyCity->language_id ?: $langId,
                        'country_id'  => $legacyCity->country_id ?: $countryId,
                        'state_id'    => $legacyCity->state_id ?: $stateId,
                        'updated_at'  => now(),
                    ]);
            }
        }

        // Create a complete city record for a new vendor-entered locality.
        if (!$cityId) {
            $cityId = DB::table('cities')->insertGetId([
                'language_id' => $langId,
                'country_id'  => $countryId,
                'state_id'    => $stateId,
                'name'        => $cityName,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $hotelId = DB::table('hotels')->insertGetId([
            'vendor_id'       => $this->vendor_id,
            'average_rating'  => 0,
            'latitude'        => $adminData['latitude'] ?? '0',
            'longitude'       => $adminData['longitude'] ?? '0',
            'status'          => 1,
            'approval_status' => 1,
            'min_price'       => $this->price_3hrs,
            'max_price'       => $this->price_fullday,
            'stars'           => $adminData['stars'] ?? 3,
            'couple_friendly' => (
                $this->allow_same_city_couples === 'yes'
                || $this->allow_outstation_couples === 'yes'
            ) ? 1 : 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $slug = \Illuminate\Support\Str::slug($this->hotel_name) . '-' . $hotelId;

        $perkIds = json_decode($this->selected_perks ?? '[]', true) ?: [];
        $restrictionIds = json_decode($this->selected_restrictions ?? '[]', true) ?: [];

        $restrictionsArr = [];

        if (!empty($restrictionIds)) {
            $restrData = DB::table('hotel_restrictions')
                ->whereIn('id', $restrictionIds)
                ->get();

            foreach ($restrData as $rd) {
                $restrictionsArr[] = [
                    'id' => (int) $rd->id,
                    'type' => $rd->default_type ?? 'not_allowed',
                ];
            }
        }

        DB::table('hotel_contents')->insert([
            'language_id'  => $langId,
            'hotel_id'     => $hotelId,
            'category_id'  => $catId,
            'country_id'   => $countryId,
            'state_id'     => $stateId,
            'city_id'      => $cityId,
            'title'        => $this->hotel_name,
            'slug'         => $slug,
            'address'      => $this->address . ', ' . $cityName . ' - ' . $this->pincode,
            'description'  => $adminData['description'] ?? '',
            'perks'        => json_encode($perkIds),
            'restrictions' => json_encode($restrictionsArr),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        $roomId = DB::table('rooms')->insertGetId([
            'hotel_id'    => $hotelId,
            'vendor_id'   => $this->vendor_id,
            'average_rating' => 0,
            'status'      => 1,
            'bed'         => 1,
            'min_price'   => $this->price_3hrs,
            'max_price'   => $this->price_fullday,
            'adult'       => 2,
            'children'    => 0,
            'bathroom'    => 1,
            'number_of_rooms_of_this_same_type' => $this->total_rooms,
            'feature_image' => json_decode($this->hotel_images ?? '{}', true)['main'] ?? null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $roomCategoryId = DB::table('room_categories')
            ->where('status', 1)
            ->where('name', $this->room_type)
            ->value('id');

        if (!$roomCategoryId) {
            $roomCategoryId = DB::table('room_categories')
                ->where('status', 1)
                ->value('id');
        }

        DB::table('room_contents')->insert([
            'language_id'   => $langId,
            'room_id'       => $roomId,
            'title'         => $this->room_type,
            'slug'          => \Illuminate\Support\Str::slug($this->room_type) . '-' . $roomId,
            'room_category' => $roomCategoryId,
            'description'   => $this->room_type . ' with ' . implode(', ', $perkIds ?: $this->amenities_list),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        $bhIds = DB::table('booking_hours')->pluck('id', 'hour');

        foreach ([
            ['hour' => 3, 'price' => $this->price_3hrs],
            ['hour' => 6, 'price' => $this->price_6hrs],
            ['hour' => 24, 'price' => $this->price_fullday],
        ] as $priceData) {
            $bhId = $bhIds[strval($priceData['hour'])]
                ?? DB::table('booking_hours')->value('id');

            DB::table('hourly_room_prices')->insert([
                'vendor_id'  => $this->vendor_id,
                'hotel_id'   => $hotelId,
                'room_id'    => $roomId,
                'hour_id'    => $bhId,
                'hour'       => $priceData['hour'],
                'price'      => $priceData['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $imgData = json_decode($this->hotel_images ?? '{}', true);

        $hotelImgDir = public_path('assets/img/hotel/');
        $galleryDir  = public_path('assets/img/hotel/hotel-gallery/');
        $logoDir     = public_path('assets/img/hotel/logo/');
        $featureDir  = public_path('assets/img/room/featureImage/');

        foreach ([$galleryDir, $logoDir, $featureDir] as $directory) {
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }
        }

        if (!empty($imgData['main'])) {
            DB::table('hotel_images')->insert([
                'hotel_id'   => $hotelId,
                'image'      => $imgData['main'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $source = $hotelImgDir . $imgData['main'];

            if (file_exists($source)) {
                @copy($source, $galleryDir . $imgData['main']);
                @copy($source, $logoDir . $imgData['main']);
                @copy($source, $featureDir . $imgData['main']);
            }
        }

        if (!empty($imgData['gallery'])) {
            foreach ($imgData['gallery'] as $galleryImage) {
                DB::table('hotel_images')->insert([
                    'hotel_id'   => $hotelId,
                    'image'      => $galleryImage,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $source = $hotelImgDir . $galleryImage;

                if (file_exists($source)) {
                    @copy($source, $galleryDir . $galleryImage);
                }
            }
        }

        $this->update([
            'hotel_id' => $hotelId,
            'room_id'  => $roomId,
            'status'   => self::STATUS_LIVE,
        ]);

        return $hotelId;
    }
}
