<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $arrayTask = [
            'title'         => $this->faker->unique()->sentence(),
            'description'   => $this->faker->paragraph(3, true),
            'is_completed'  => (bool)random_int(0, 1),
            'user_id'       => $this->faker->uuid,
        ];

        $arrayAttachment = $this->set_attachment_array();
        if (count($arrayAttachment)) {
            // $arrayTask['attachment'] = json_encode($arrayAttachment);
        }

        return $arrayTask;
    }

    public function set_attachment_array() : array {
        $array = [];
        $quantity = mt_rand(0, 5);

        if ((bool)random_int(0, 1)) {
            for ($i=0; $i < $quantity; $i++) { 
                array_push($array,$this->faker->imageUrl);
            }
        }

        return $array;
    }
}
