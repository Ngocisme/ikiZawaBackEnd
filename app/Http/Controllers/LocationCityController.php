<?php

namespace App\Http\Controllers;

use App\Http\Resources\LocationCityResource;
use App\Models\LocationCityModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationCityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $perPage = $request->get('perPage', 10);
        $locationCities = LocationCityModel::paginate($perPage);
        if ($locationCities->isNotEmpty()) {
            return LocationCityResource::collection($locationCities)->additional(
                [
                    'pagination' => [
                        'current_page' => $locationCities->currentPage(),
                        'last_page' => $locationCities->lastPage(),
                        'per_page' => $locationCities->perPage(),
                        'total' => $locationCities->total(),
                    ]
                ]
            );
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make(
            $request->all(),
            [
                'locationCityName' => 'required|string|max:20'
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'locationCityName.required' => 'Tên thành phố không được để trống.',
                'locationCityName.string' => 'Tên thành phố phải là một chuỗi ký tự.',
                'locationCityName.max' => 'Tên thành phố không được dài quá 20 ký tự.'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Thêm dữ liệu thất bại',
                'error' => $validator->messages(),
            ], 422);
        }

        $existingCity = LocationCityModel::where('locationCityName', $request->locationCityName)->exists();

        if ($existingCity) {
            return response()->json([
                'message' => 'Thành phố này đã tồn tại trong hệ thống.',
            ], 409);
        }

        $locationCities = LocationCityModel::create(
            [
                'locationCityName' => $request->locationCityName,
            ]
        );

        return response()->json([
            'message' => 'tạo thành phố thành công',
            'data' => new LocationCityResource($locationCities)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(LocationCityModel $locationCity)
    {
        //
        return new LocationCityResource($locationCity);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LocationCityModel $locationCity)
    {
        //
        $validator = Validator::make(
            $request->all(),
            [
                'locationCityName' => 'required|string|max:20'
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'locationCityName.required' => 'Tên thành phố không được để trống.',
                'locationCityName.string' => 'Tên thành phố phải là một chuỗi ký tự.',
                'locationCityName.max' => 'Tên thành phố không được dài quá 20 ký tự.'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Thêm dữ liệu thất bại',
                'error' => $validator->messages(),
            ], 422);
        }

        $existingCity = LocationCityModel::where('locationCityName', $request->locationCityName)->exists();

        if ($existingCity) {
            return response()->json([
                'message' => 'Thành phố này đã tồn tại trong hệ thống.',
            ], 409);
        }

        $locationCity->update(
            [
                'locationCityName' => $request->locationCityName,
            ]
        );

        return response()->json([
            'message' => 'tạo thành phố thành công',
            'data' => new LocationCityResource($locationCity)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LocationCityModel $locationCity)
    {
        //
        $locationCity->delete();
        return response()->json([
            'message' => 'Đã xoá thành công thành phố'
        ]);
    }
}
