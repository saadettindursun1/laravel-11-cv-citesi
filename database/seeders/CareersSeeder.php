<?php

namespace Database\Seeders;

use App\Models\Career;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CareersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Career::create([
            "title"=> "Php developer",
            "company"=> "Morsoft",
            "start_date"=> "2024-01-01",
            "end_date"=> "2024-06-06",
            "description"=> "Php developer görevi",    
            "status"=> 1,
        ]);
    }
}
