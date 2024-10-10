<?php

namespace App\Http\Controllers;

use App\Models\Specimen;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpecimenController extends Controller
{
    public function index()
    {
        $specimens = Specimen::paginate(12);

        return Inertia::render('Specimens/Index', [
            'specimens' => $specimens,
            'mapData' => $this->getMapData(),
        ]);
    }

    public function getAllSpecimens()
    {
        $allSpecimens = Specimen::all();
        return response()->json($allSpecimens);
    }

    private function getMapData()
    {
        return Specimen::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('id', 'catalog_number', 'scientific_name', 'latitude', 'longitude')
            ->get();
    }

    public function getSpecimenDetail(Specimen $specimen)
    {
        return response()->json([
            'id' => $specimen->id,
            'catalog_number' => $specimen->catalog_number,
            'scientific_name' => $specimen->scientific_name,
            'locality' => $specimen->locality,
        ]);
    }

    public function show(Specimen $specimen)
    {
        return Inertia::render('Specimens/Detail', [
            'specimen' => $specimen
        ]);
    }
}
