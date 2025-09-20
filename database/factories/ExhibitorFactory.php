<?php

namespace Database\Factories;

use App\Enums\Branch;
use App\Models\Address;
use App\Models\ContactPerson;
use App\Models\Exhibitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exhibitor>
 */
class ExhibitorFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(function (Exhibitor $exhibitor) {

            // dd($exhibitor->address());
            $exhibitor->address->update(['exhibitor_id' => $exhibitor->id]);
            $exhibitor->billingAddress->update(['exhibitor_id' => $exhibitor->id]);
            $exhibitor->contactPerson->update(['exhibitor_id' => $exhibitor->id]);
            $exhibitor->billingContactPerson->update(['exhibitor_id' => $exhibitor->id]);
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'display_name' => $this->faker->company(),
            'display_name_affix' => $this->faker->companySuffix(),
            'branch' => $this->faker->randomElement(Branch::cases()),
            'website' => $this->faker->url(),

            'address_id' => Address::factory(),
            'billing_address_id' => Address::factory(),
            'contact_person_id' => ContactPerson::factory(),
            'billing_contact_person_id' => ContactPerson::factory(),

            'description' => $this->faker->realText(),
            'internal_note' => $this->faker->realText(),
        ];
    }
}
