<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fakeTitle = $this->faker->words(rand(5,15));

        return [
            'title' => implode(" ", $fakeTitle),
            'slug' => strtolower(implode("-", $fakeTitle)) ,
            'category_id' => rand(1,10),
            'writer_id' => rand(1,5),
            'content' => $this->faker->text(rand(500,900)),
            'prologue' => $this->faker->paragraph(),
            'thumbnail' => 'https://source.unsplash.com/random/' . rand(5,10) * 100 . 'x' .  rand(5,10) * 100,
            'viewer' => rand(10,200),
            'location' => 'jakarta'
        ];
    }
}
