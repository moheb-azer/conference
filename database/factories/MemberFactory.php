<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FamilyRelation;
use App\Models\Member;
use App\Models\User;

class MemberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Member::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'dob' => fake()->date(),
            'gender' => fake()->randomElement(["m","f"]),
            'family_relation_id' => FamilyRelation::factory(),
            'phone1' => fake()->regexify('[A-Za-z0-9]{11}'),
            'phone2' => fake()->regexify('[A-Za-z0-9]{11}'),
            'whatsapp' => fake()->regexify('[A-Za-z0-9]{11}'),
            'image' => fake()->word(),
            'hasLogin' => fake()->numberBetween(-10000, 10000),
            'user_id' => User::factory(),
        ];
    }
}
