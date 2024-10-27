<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->HotelId,
            'tên khách sạn' => $this->HotelName,
            'địa chỉ khách sạn' => $this->HotelAddress,
            'ngày mở cửa' => $this->OpenDay,
            'trạng thái' => $this->HotelStatus,
            'quận' => $this->locationDistrictId,
        ];
    }
}
