<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        'title' => $this->faker->randomElement(['Broken Pole','Outage','Street Light']),
        'description' =>$this->faker->text(),
        'organisation_id' => 1,// $this->faker->numberBetween(1,3),
         
        'latitude' => $this->faker->numberBetween(10, 90),
        'longitude' => $this->faker->numberBetween(10,180),
        'user_id'=>$this->faker->randomElement(['2','3']),
        'votes' =>$this->faker->numberBetween(-10,10),
        'flags'=> $this->faker->numberBetween(-10,10),
        'status' => $this->faker->randomElement(['Submitted','Created'])
        ];
    }
}
