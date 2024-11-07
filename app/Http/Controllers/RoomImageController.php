<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomImageResource;
use App\Http\Resources\RoomResource;
use App\Models\RoomImageModel;
use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RoomImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $roomImg = RoomImageModel::with(['room'])->get();
        if ($roomImg->isNotEmpty()) {
            return RoomImageResource::collection($roomImg);
        } else {
            return response()->json(
                [
                    'message' => 'Không có dữ liệu nào về ảnh phòng.'
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
                'RoomId' => 'required|exists:Room,RoomId',
                'RoomImageUrl' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'RoomImageDescription' => 'required|string|max:255',
            ],
            [
                // Custom thông báo lỗi cho từng trường và quy tắc
                'RoomImageUrl.required' => 'Ảnh phòng không được để trống.',
                'RoomImageUrl.image' => 'Đường dẫn ảnh phải là một tệp ảnh.',
                'RoomImageUrl.mimes' => 'Ảnh chỉ được có định dạng jpeg, png, jpg, gif.',
                'RoomImageUrl.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
                'RoomImageDescription.required' => 'Mô tả ảnh không được để trống.',
                'RoomImageDescription.string' => 'Mô tả ảnh phải là một chuỗi ký tự.',
                'RoomImageDescription.max' => 'Mô tả ảnh không được dài quá 255 ký tự.',
                'RoomId.required' => 'ID room không được để trống.',
                'RoomId.exists' => 'ID room không tồn tại.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Thêm dữ liệu thất bại',
                'error' => $validator->messages(),
            ], 422);
        }

        // $existingImg = RoomImageModel::where('RoomImageUrl', $request->RoomImageUrl)->exists();

        // if ($existingImg) {
        //     return response()->json([
        //         'message' => 'Ảnh đã tồn tại.',
        //     ], 409);
        // }

        $imagePath = null;
        if ($request->hasFile('RoomImageUrl')) {
            $image = $request->file('RoomImageUrl');
            $imagePath = $image->store('room_images', 'public'); // Lưu vào thư mục "hotel_images" trong storage/app/public
        }

        $roomimg = RoomImageModel::create(
            [
                'RoomId' => $request->RoomId,
                'RoomImageUrl' => $imagePath,
                'RoomImageDescription' => $request->RoomImageDescription,
            ]
        );

        return response()->json([
            'message' => 'Thêm ảnh thành công',
            'data' => new RoomImageResource($roomimg)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomImageModel $roomImg)
    {
        //
        return new RoomImageResource($roomImg);
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
    public function destroy(RoomImageModel $roomImg)
    {
        //
        $imgPath = $roomImg->RoomImageUrl;

        // dd($imgPath);

        if (Storage::disk('public')->exists($imgPath)) {
            Storage::disk('public')->delete($imgPath);
        }

        $roomImg->delete();

        return response()->json([
            'message' => 'Ảnh đã được xoá thành công.'
        ], 200);
    }
}
