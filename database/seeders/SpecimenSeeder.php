<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use App\Models\Specimen;

class SpecimenSeeder extends Seeder
{
    public function run()
    {
        $csv = Reader::createFromPath(storage_path('app/specimens.csv'), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            $data = array_map(function ($value) {
                return $value === '' ? null : $value;
            }, [
                'catalog_number' => $record['catalogNumber'],
                'scientific_name' => $record['scientificName'],
                'country' => $record['country'],
                'locality' => $record['locality'],
                'minimum_altitude_in_meters' => $record['minimumAltitudeInMeters'],
                'event_date' => $record['eventDate'],
                'recorded_by' => $record['recordedBy'],
                'other_catalog_numbers' => $record['otherCatalogNumbers'],
                'specimen_image' => $record['specimen_image'],
                'cover_image' => $record['cover_image'],
                'cover_barcode' => $record['cover_barcode'],
                'sheet_type' => $record['sheet_type'],
                'latitude' => $record['latitude'],
                'longitude' => $record['longitude'],
                'formatted_address' => $record['formatted_address'],
            ]);

            Specimen::create($data);
        }
    }
}
