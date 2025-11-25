<?php

namespace App\Http\Resources;

use App\Models\JobFair;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin JobFair */
class JobFairResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $includes = $this->parseIncludes($request);

        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'are_exhibitors_public' => $this->are_exhibitors_public,
            'floor_plan_link' => $this->floor_plan_link,
            'lounge_registration_ends_at' => $this->lounge_registration_ends_at,

            'dates' => JobFairDateResource::collection($this->whenLoaded('dates')),
            'locations' => LocationResource::collection($this->whenLoaded('locations')),
            'exhibitors_count' => $this->whenCounted('exhibitors'),
            'exhibitors' => $this->when(
                in_array('exhibitors', $includes) && $this->are_exhibitors_public,
                fn () => ExhibitorResource::collection($this->exhibitors)
            ),
            'professions' => $this->when(
                in_array('professions', $includes) && $this->are_exhibitors_public,
                fn () => ProfessionResource::collection($this->professions())
            ),
            'degrees' => $this->when(
                in_array('degrees', $includes) && $this->are_exhibitors_public,
                fn () => DegreeResource::collection($this->degrees())
            ),
            'school_registrations' => $this->when(
                in_array('school_registrations', $includes),
                fn () => SchoolRegistrationResource::collection($this->whenLoaded('schoolRegistrations'))
            ),
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function parseIncludes(Request $request): array
    {
        $include = $request->query('include', '');

        if (blank($include)) {
            return [];
        }

        return array_map('trim', explode(',', $include));
    }
}
