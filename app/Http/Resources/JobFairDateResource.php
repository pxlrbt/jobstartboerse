<?php

namespace App\Http\Resources;

use App\Models\JobFairDate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin JobFairDate */
class JobFairDateResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('d.m.Y'),
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
        ];
    }
}
