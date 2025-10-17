<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\ToolKit;
use App\Models\ToolKitMedia;

class ToolKitSeeder extends Seeder
{
    public function run(): void
    {
        // pick 3 random courses
        $courses = Course::inRandomOrder()->take(3)->get();

        foreach ($courses as $course) {
            for ($i = 1; $i <= 2; $i++) {
                $toolKit = ToolKit::create([
                    'course_id'          => $course->id,
                    'name'               => "Toolkit $i for {$course->title}",
                    'description'        => "This is a detailed description of Toolkit $i under {$course->title}.",
                    'short_description'  => "Toolkit $i short desc",
                    'is_enabled'         => true,
                    'price'              => rand(100, 500),
                    'offer_price'        => rand(50, 100),
                    'min_loyalty_points' => rand(0, 50),
                ]);

                // attach 2 dummy images
                for ($j = 1; $j <= 2; $j++) {
                    ToolKitMedia::create([
                        'tool_kit_id' => $toolKit->id,
                        'file_path'   => "dummy/toolkit{$toolKit->id}_image{$j}.jpg",
                    ]);
                }
            }
        }
    }
}
