<?php

namespace App\Http\Controllers;

use App\Http\Resources\HotelResource;
use App\Models\HotelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $hotels = HotelModel::with(['district', 'imageHotel'])->get();
        if ($hotels->isNotEmpty()) {
            return HotelResource::collection($hotels);
        } else {
            return response()->json(
                [
                    'message' => 'Không có dữ liệu nào về khách sạn'
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
                'HotelName' => 'required|string|max:255',
                'HotelAddress' => 'required|string|max:255',
                'OpenDay' => 'required|date',
                'locationDistrictId' => 'required|exists:Location_district,locationDistrictId'
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'HotelName.required' => 'Tên khách sạn không được để trống.',
                'HotelName.string' => 'Tên khách sạn phải là một chuỗi ký tự.',
                'HotelName.max' => 'Tên khách sạn không được dài quá 255 ký tự.',
                'HotelAddress.required' => 'Địa chỉ khách sạn không được để trống.',
                'HotelAddress.string' => 'Địa chỉ khách sạn phải là một chuỗi ký tự.',
                'HotelAddress.max' => 'Địa chỉ khách sạn không được dài quá 255 ký tự.',
                'OpenDay.required' => 'Phải chọn ngày mở cửa.',
                'OpenDay.date' => 'Phải là định dạng ngày tháng năm.',
                'locationDistrictId.required' => 'ID quận không được để trống.',
                'locationDistrictId.exists' => 'ID quận không hợp lệ.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Thêm dữ liệu thất bại',
                'error' => $validator->messages(),
            ], 422);
        }

        $existingHotel = HotelModel::where('HotelName', $request->HotelName)->exists();

        if ($existingHotel) {
            return response()->json([
                'message' => 'Khách sạn này đã tồn tại trong hệ thống.',
            ], 409);
        }

        $hotel = HotelModel::create(
            [
                'HotelName' => $request->HotelName,
                'HotelAddress' => $request->HotelAddress,
                'OpenDay' => $request->OpenDay,
                'locationDistrictId' => $request->locationDistrictId,
            ]
        );

        return response()->json([
            'message' => 'tạo khách sạn thành công',
            'data' => new HotelResource($hotel)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelModel $hotel)
    {
        //
        return new HotelResource($hotel);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HotelModel $hotel)
    {
        //
        $validator = Validator::make(
            $request->all(),
            [
                'HotelName' => 'required|string|max:255',
                'HotelAddress' => 'required|string|max:255',
                'OpenDay' => 'required|date',
                'HotelStatus' => 'in:0,1',
                // !! 0 là đóng cửa
                // ** 1 là mở cửa
                'locationDistrictId' => 'required|exists:Location_district,locationDistrictId'
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'HotelName.required' => 'Tên khách sạn không được để trống.',
                'HotelName.string' => 'Tên khách sạn phải là một chuỗi ký tự.',
                'HotelName.max' => 'Tên khách sạn không được dài quá 255 ký tự.',
                'HotelAddress.required' => 'Địa chỉ khách sạn không được để trống.',
                'HotelAddress.string' => 'Địa chỉ khách sạn phải là một chuỗi ký tự.',
                'HotelAddress.max' => 'Địa chỉ khách sạn không được dài quá 255 ký tự.',
                'OpenDay.required' => 'Phải chọn ngày mở cửa.',
                'OpenDay.date' => 'Phải là định dạng ngày tháng năm.',
                'HotelStatus.in' => 'Trạng thái khách sạn chỉ có thể là 0 (không hoạt động) hoặc 1 (hoạt động).',
                'locationDistrictId.required' => 'ID quận không được để trống.',
                'locationDistrictId.exists' => 'ID quận không hợp lệ.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Thêm dữ liệu thất bại',
                'error' => $validator->messages(),
            ], 422);
        }

        $existingHotel = HotelModel::where('HotelName', $request->HotelName)->exists();

        if ($existingHotel) {
            return response()->json([
                'message' => 'Khách sạn này đã tồn tại trong hệ thống.',
            ], 409);
        }

        $hotel->update(
            [
                'HotelName' => $request->HotelName,
                'HotelAddress' => $request->HotelAddress,
                'OpenDay' => $request->OpenDay,
                'HotelStatus' => $request->HotelStatus,
                'locationDistrictId' => $request->locationDistrictId,
            ]
        );

        return response()->json([
            'message' => 'tạo khách sạn thành công',
            'data' => new HotelResource($hotel)
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HotelModel $hotel)
    {
        //
        $hotel->delete();
        return response()->json([
            'message' => 'Đã xoá thành công khách sạn'
        ], 200);
    }
}
