<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim(fake()->sentence(3), '.');

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('####'),
            'description' => fake()->optional(0.85)->paragraph(),
            'location' => fake()->address(),
            'starts_at' => $start = fake()->dateTimeBetween('now', '+1 year'),
            'ends_at' => fake()->optional(0.6)->dateTimeBetween($start, (clone $start)->modify('+1 day')),
            'is_published' => fake()->boolean(70),
        ];
    }
}
