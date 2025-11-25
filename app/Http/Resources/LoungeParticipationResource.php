<?php

namespace App\Http\Resources;

use App\Models\Degree;
use App\Models\LoungeParticipation;
use App\Models\Profession;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin LoungeParticipation */
class LoungeParticipationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /**
         * @var Degree|Profession $model
         */
        $model = $this->model;

        return [
            'id' => $this->id,
            'model_type' => $this->model_type,
            'model_display_name' => $model->display_name,
            'exhibitor' => new ExhibitorResource($this->exhibitor)->only(['id', 'display_name']),
        ];
    }
}
