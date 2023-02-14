<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'siren' => '5555555555555',
            'company_name' => 'test',
            'address' => 'test',
            'city' => 'test',
            'zip' => 'test',
            'signed' => false,
            'PDL' => 'test',
            'PCE' => 'test',
            'first_name' => 'test',
            'last_name' => 'test',
            'supplier' => true,
            'email' => 'test@test.fr',
            'user_id' =>  ['user_id'],
        ];
    }
}
