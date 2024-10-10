<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('specimens', function (Blueprint $table) {
            $table->id();
            $table->string('catalog_number');
            $table->string('scientific_name')->nullable();
            $table->string('country')->nullable();
            $table->text('locality')->nullable();
            $table->string('minimum_altitude_in_meters')->nullable();
            $table->string('event_date')->nullable();
            $table->string('recorded_by')->nullable();
            $table->string('other_catalog_numbers')->nullable();
            $table->string('specimen_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cover_barcode')->nullable();
            $table->string('sheet_type')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('formatted_address')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('specimens');
    }
};
