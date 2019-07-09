<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RefundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $beer = parent::toArray($request);
        return [
            'refunded' => $beer['refunded'],
            'amount' => $beer['amount'],
        ];
    }
}
