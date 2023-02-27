<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'post_caption' => $this->faker->paragraph,
            // the faker takes the place to store the image and return the image name so concatenate 'uploads/' before it to store in the database
            // 'image_path' => 'uploads/' . $this->faker->image('public/storage/uploads', 800, 600, null, false),
            'image_path' => 'uploads/' . rand(1, 16) . '.png',
        ];
    }
}
