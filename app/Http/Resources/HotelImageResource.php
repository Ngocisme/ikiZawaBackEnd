<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->HotelImageId,
            'tên khách sạn' => $this->hotel->HotelName,
            'Url ảnh' => $this->ImageUrl,
            'Mô tả ảnh' => $this->HotelImageDescription,
        ];
    }
}
