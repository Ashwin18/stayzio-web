<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HotelPerk extends Model
{
    use HasFactory;
    protected $table = 'hotel_perks';
    protected $fillable = ['icon', 'title', 'status'];
}
