<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rooms = RoomModel::with(['hotel'])->get();
        if ($rooms->isNotEmpty()) {
            return RoomResource::collection($rooms);
        } else {
            return response()->json(
                [
                    'message' => 'Không có dữ liệu nào về phòng của khách sạn'
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
                'RoomName' => 'required|string|max:64',
                'HotelId' => 'required|exists:Hotel,HotelId',
                'RoomType' => 'required|string|max:30',
                'RoomStatus' => 'required|string|max:30',
                'Description' => 'required|string',
                'MaxCustomer' => 'required|integer|min:1|max:3',
                'Price' => 'required|numeric|between:0,99999999.99'
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'RoomName.required' => 'Tên phòng không được để trống.',
                'RoomName.string' => 'Tên phòng phải là một chuỗi ký tự.',
                'RoomName.max' => 'Tên phòng không được dài quá 64 ký tự.',

                'RoomStatus.required' => 'Trạng thái phòng không được để trống.',
                'RoomStatus.string' => 'Trạng thái phòng phải là một chuỗi ký tự.',
                'RoomStatus.max' => 'Trạng thái phòng không được dài quá 30 ký tự.',

                'RoomType.required' => 'Kiểu phòng không được để trống.',
                'RoomType.string' => 'Kiểu phòng phải là một chuỗi ký tự.',
                'RoomType.max' => 'Kiểu phòng không được dài quá 30 ký tự.',

                'HotelId.required' => 'ID hotel không được để trống.',
                'HotelId.exists' => 'ID hotel không hợp lệ.',

                'MaxCustomer.required' => 'Số khách tối đa không được để trống.',
                'MaxCustomer.integer' => 'Số khách tối đa phải là số nguyên.',
                'MaxCustomer.min' => 'Số khách tối đa phải lớn hơn hoặc bằng 1.',
                'MaxCustomer.max' => 'Số khách tối đa phải nhỏ hơn hoặc bằng 3.',

                'Price.required' => 'Giá không được để trống.',
                'Price.numeric' => 'Giá phải là một số.',
                'Price.between' => 'Giá phải nằm trong khoảng từ 1 đến 99999999.99.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Thêm dữ liệu thất bại',
                'error' => $validator->messages(),
            ], 422);
        }

        // $existingHotel = RoomModel::where('RoomName', $request->RoomName)->exists();

        // if ($existingHotel) {
        //     return response()->json([
        //         'message' => 'Phòng này đã tồn tại trong hệ thống.',
        //     ], 409);
        // }

        $room = RoomModel::create(
            [
                'RoomName' => $request->RoomName,
                'HotelId' => $request->HotelId,
                'RoomType' => $request->RoomType,
                'RoomStatus' => $request->RoomStatus,
                'Description' => $request->Description,
                'MaxCustomer' => $request->MaxCustomer,
                'Price' => $request->Price,
            ]
        );

        return response()->json([
            'message' => 'tạo phòng ở thành công',
            'data' => new RoomResource($room)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomModel $room)
    {
        //
        return new RoomResource($room);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomModel $room)
    {
        //
        $validator = Validator::make(
            $request->all(),
            [
                'RoomName' => 'required|string|max:64',
                'HotelId' => 'required|exists:Hotel,HotelId',
                'RoomType' => 'required|string|max:30',
                'RoomStatus' => 'required|string|max:30',
                'Description' => 'required|string',
                'MaxCustomer' => 'required|integer|min:1|max:3',
                'Price' => 'required|numeric|between:0,99999999.99'
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'RoomName.required' => 'Tên phòng không được để trống.',
                'RoomName.string' => 'Tên phòng phải là một chuỗi ký tự.',
                'RoomName.max' => 'Tên phòng không được dài quá 64 ký tự.',

                'RoomStatus.required' => 'Trạng thái phòng không được để trống.',
                'RoomStatus.string' => 'Trạng thái phòng phải là một chuỗi ký tự.',
                'RoomStatus.max' => 'Trạng thái phòng không được dài quá 30 ký tự.',

                'RoomType.required' => 'Kiểu phòng không được để trống.',
                'RoomType.string' => 'Kiểu phòng phải là một chuỗi ký tự.',
                'RoomType.max' => 'Kiểu phòng không được dài quá 30 ký tự.',

                'HotelId.required' => 'ID hotel không được để trống.',
                'HotelId.exists' => 'ID hotel không hợp lệ.',

                'MaxCustomer.required' => 'Số khách tối đa không được để trống.',
                'MaxCustomer.integer' => 'Số khách tối đa phải là số nguyên.',
                'MaxCustomer.min' => 'Số khách tối đa phải lớn hơn hoặc bằng 1.',
                'MaxCustomer.max' => 'Số khách tối đa phải nhỏ hơn hoặc bằng 3.',

                'Price.required' => 'Giá không được để trống.',
                'Price.numeric' => 'Giá phải là một số.',
                'Price.between' => 'Giá phải nằm trong khoảng từ 1 đến 99999999.99.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Sửa dữ liệu thất bại',
                'error' => $validator->messages(),
            ], 422);
        }

        // $existingHotel = RoomModel::where('RoomName', $request->RoomName)->exists();

        // if ($existingHotel) {
        //     return response()->json([
        //         'message' => 'Phòng này đã tồn tại trong hệ thống.',
        //     ], 409);
        // }

        $room->update(
            [
                'RoomName' => $request->RoomName,
                'HotelId' => $request->HotelId,
                'RoomType' => $request->RoomType,
                'RoomStatus' => $request->RoomStatus,
                'Description' => $request->Description,
                'MaxCustomer' => $request->MaxCustomer,
                'Price' => $request->Price,
            ]
        );

        return response()->json([
            'message' => 'Cập nhật phòng ở thành công',
            'data' => new RoomResource($room)
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomModel $room)
    {
        //
        $room->delete();
        return response()->json([
            'message' => 'Đã xoá thành công phòng'
        ], 200);
    }
}
