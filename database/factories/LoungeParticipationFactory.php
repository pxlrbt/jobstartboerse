<?php

namespace Database\Factories;

use App\Models\Degree;
use App\Models\Exhibitor;
use App\Models\JobFair;
use App\Models\LoungeParticipation;
use App\Models\Profession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @extends Factory<LoungeParticipation>
 */
class LoungeParticipationFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $model = $this->faker->randomElement([
            Degree::class,
            Profession::class,
        ]);

        return [
            'job_fair_id' => JobFair::factory(),
            'exhibitor_id' => Exhibitor::factory(),
            'model_type' => Relation::getMorphAlias($model),
            'model_id' => $model::factory(),
        ];
    }
}
