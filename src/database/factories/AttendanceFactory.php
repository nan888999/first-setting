<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $created_at = Carbon::today()->addSeconds($this->faker->numberBetween(0, 86399));
        $updated_at = Carbon::instance($created_at)->addMinutes($this->faker->numberBetween(1, 1440 - $created_at->format('H') * 60 - $created_at->format('i')));

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'created_at' =>$created_at,
            'updated_at'=>$updated_at
        ];
    }
}
