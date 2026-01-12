<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{

     protected $model = Role::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'user', 
        ];
    }

    public function user()
    {
        return $this->state([
            'id' => 1,
            'name' => 'user',
        ]);
    }

    public function admin()
    {
        return $this->state([
            'id' => 2,
            'name' => 'admin',
        ]);
    }
}