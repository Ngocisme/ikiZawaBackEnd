<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationDistrictResource;
use App\Models\LocationDistrictModel;
use Illuminate\Http\Request;

class LocationDistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $districts = LocationDistrictModel::with('city')->get();
        if ($districts->isNotEmpty()) {
            return LocationDistrictResource::collection($districts);
        } else {
            return response()->json(
                [
                    'message' => 'Không có dữ liệu nào về quận'
                ],
                200
            );
        }
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
