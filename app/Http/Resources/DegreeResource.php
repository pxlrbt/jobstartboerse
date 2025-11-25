<?php

namespace App\Http\Resources;

use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Degree */
class DegreeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'display_name' => $this->display_name,
        ];
    }
}
