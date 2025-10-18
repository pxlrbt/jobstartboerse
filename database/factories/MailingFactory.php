<?php

namespace Database\Factories;

use App\Models\Mailing;
use Illuminate\Database\Eloquent\Factories\Factory;

class MailingFactory extends Factory
{
    protected $model = Mailing::class;

    public function definition(): array
    {
        return [
            'subject' => $this->faker->sentence(),
            'message' => $this->faker->randomHtml(),
        ];
    }
}
