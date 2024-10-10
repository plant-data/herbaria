<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specimen extends Model
{
    use HasFactory;

    protected $fillable = [
        'catalog_number',
        'scientific_name',
        'country',
        'locality',
        'minimum_altitude_in_meters',
        'event_date',
        'recorded_by',
        'other_catalog_numbers',
        'specimen_image',
        'cover_image',
        'cover_barcode',
        'sheet_type',
        'latitude',
        'longitude',
        'formatted_address',
    ];

    protected $casts = [
        'event_date' => 'string',
        'minimum_altitude_in_meters' => 'string',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];
}
