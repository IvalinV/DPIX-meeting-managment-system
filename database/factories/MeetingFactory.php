<?php

namespace Database\Factories;

use App\Models\Citizen;
use App\Models\Lawyer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meeting>
 */
class MeetingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'citizen_id' => Citizen::factory()->create()->id,
            'lawyer_id' => Lawyer::factory()->create()->id,
            'date' => $this->faker->dateTime()
        ];
    }
}
