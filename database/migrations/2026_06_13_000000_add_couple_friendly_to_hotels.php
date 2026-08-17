<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoupleFriendlyToHotels extends Migration
{
    public function up()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->boolean('couple_friendly')->default(0)->after('stars');
        });
    }

    public function down()
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn('couple_friendly');
        });
    }
}
