<?php

namespace App\Http\Controllers;

use App\Http\Resources\HotelImageResource;
use App\Models\HotelImageModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HotelImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $hotelImg = HotelImageModel::with(['hotel'])->get();
        if ($hotelImg->isNotEmpty()) {
            return HotelImageResource::collection($hotelImg);
        } else {
            return response()->json(
                [
                    'message' => 'Không có dữ liệu nào về ảnh của khách sạn'
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
                'HotelId' => 'required|exists:Hotel,HotelId',
                'ImageUrl' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'HotelImageDescription' => 'required|string|max:255',
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'ImageUrl.required' => 'Ảnh khách sạn không được để trống.',
                'ImageUrl.image' => 'Đường dẫn ảnh phải là một tệp ảnh.',
                'ImageUrl.mimes' => 'Ảnh chỉ được có định dạng jpeg, png, jpg, gif.',
                'ImageUrl.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
                'HotelImageDescription.required' => 'Mô tả ảnh không được để trống.',
                'HotelImageDescription.string' => 'Mô tả ảnh phải là một chuỗi ký tự.',
                'HotelImageDescription.max' => 'Mô tả ảnh không được dài quá 255 ký tự.',
                'HotelId.required' => 'ID hotel không được để trống.',
                'HotelId.exists' => 'ID hotel không hợp lệ.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Thêm dữ liệu thất bại',
                'error' => $validator->messages(),
            ], 422);
        }

        $existingImg = HotelImageModel::where('ImageUrl', $request->ImageUrl)->exists();

        if ($existingImg) {
            return response()->json([
                'message' => 'Ảnh đã tồn tại.',
            ], 409);
        }

        $imagePath = null;
        if ($request->hasFile('ImageUrl')) {
            $image = $request->file('ImageUrl');
            $imagePath = $image->store('hotel_images', 'public'); // Lưu vào thư mục "hotel_images" trong storage/app/public
        }

        $hotelimg = HotelImageModel::create(
            [
                'HotelId' => $request->HotelId,
                'ImageUrl' => $imagePath,
                'HotelImageDescription' => $request->HotelImageDescription,
            ]
        );

        return response()->json([
            'message' => 'Thêm ảnh thành công',
            'data' => new HotelImageResource($hotelimg)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(HotelImageModel $hotelImg)
    {
        //
        return new HotelImageResource($hotelImg);
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
    public function destroy(HotelImageModel $hotelImg)
    {
        //
        $imgPath = $hotelImg->ImageUrl;

        // dd($imgPath);

        if (Storage::disk('public')->exists($imgPath)) {
            Storage::disk('public')->delete($imgPath);
        }

        $hotelImg->delete();

        return response()->json([
            'message' => 'Ảnh đã được xoá thành công.'
        ], 200);
    }
}
