<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'hotel_id',
        'category_id',
        'country_id',
        'state_id',
        'city_id',
        'title',
        'slug',
        'address',
        'amenities',
        'description',
        'perks',
        'policies',
        'restrictions',
        'meta_keyword',
        'meta_description',
    ];
    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function city()
    {
        return $this->belongsTo(\App\Models\Location\City::class, 'city_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(\App\Models\Location\Country::class, 'country_id', 'id');
    }

    public function state()
    {
        return $this->belongsTo(\App\Models\Location\State::class, 'state_id', 'id');
    }
}
