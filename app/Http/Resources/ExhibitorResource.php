<?php

namespace App\Http\Resources;

use App\Models\Exhibitor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Exhibitor */
class ExhibitorResource extends JsonResource
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
            'display_name_affix' => $this->display_name_affix,
            'website' => $this->website,
            'branch' => $this->branch?->value,
            'description' => $this->description,
            'logo_file' => $this->logo_file,
        ];
    }
}
