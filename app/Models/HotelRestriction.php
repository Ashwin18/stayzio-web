<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelRestriction extends Model
{
    use HasFactory;
    protected $table = 'hotel_restrictions';
    protected $fillable = ['icon', 'title', 'default_type', 'status'];
}
