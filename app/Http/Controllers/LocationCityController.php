<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCityResource;
use App\Models\LocationCityModel;
use Illuminate\Http\Request;

class LocationCityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $locationCities = LocationCityModel::get();
        if ($locationCities) {
            return LocationCityResource::collection($locationCities);
        } else {
            return response()->json(
                [
                    'message' => 'Không có dữ liệu nào về thành phố'
                ],
                200
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
