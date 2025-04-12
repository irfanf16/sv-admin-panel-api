<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Company\CompaniesIndustry;

class CompaniesIndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() 
    {
        //Truncate table before insert data
        CompaniesIndustry::truncate();
        $industries =
            [
                ["name" => "Manufacturing", "created_at" => Carbon::now()],
                ["name" => "Technology", "created_at" => Carbon::now()],
                ["name" => "Trade", "created_at" => Carbon::now()],
                ["name" => "Production", "created_at" => Carbon::now()],
                ["name" => "Finance", "created_at" => Carbon::now()],
                ["name" => "Industry", "created_at" => Carbon::now()],
                ["name" => "Market research", "created_at" => Carbon::now()],
                ["name" => "Mining", "created_at" => Carbon::now()],
                ["name" => "Construction", "created_at" => Carbon::now()],
                ["name" => "Retail", "created_at" => Carbon::now()],
                ["name" => "Robotics", "created_at" => Carbon::now()],
                ["name" => "Agriculture", "created_at" => Carbon::now()],
                ["name" => "Conglomerate", "created_at" => Carbon::now()],
                ["name" => "Transport", "created_at" => Carbon::now()],
                ["name" => "Investment", "created_at" => Carbon::now()],
                ["name" => "Computers and information technology", "created_at" => Carbon::now()],
                ["name" => "Foodservice", "created_at" => Carbon::now()],
                ["name" => "Food industry", "created_at" => Carbon::now()],
                ["name" => "Economics", "created_at" => Carbon::now()],
                ["name" => "Education", "created_at" => Carbon::now()],
                ["name" => "Software", "created_at" => Carbon::now()],
                ["name" => "Real Estate", "created_at" => Carbon::now()],
                ["name" => "Goods", "created_at" => Carbon::now()],
                ["name" => "Research", "created_at" => Carbon::now()],
                ["name" => "Heavy industry", "created_at" => Carbon::now()],
                ["name" => "Factory", "created_at" => Carbon::now()],
                ["name" => "Health care", "created_at" => Carbon::now()],
                ["name" => "Engineering", "created_at" => Carbon::now()],
                ["name" => "Insurance", "created_at" => Carbon::now()],
                ["name" => "Marketing", "created_at" => Carbon::now()],
                ["name" => "Machine industry", "created_at" => Carbon::now()],
                ["name" => "Telecommunications", "created_at" => Carbon::now()],
                ["name" => "Holding company", "created_at" => Carbon::now()],
                ["name" => "Tertiary sector of the economy", "created_at" => Carbon::now()],
                ["name" => "Cryptocurrency", "created_at" => Carbon::now()],
                ["name" => "Computer hardware", "created_at" => Carbon::now()],
                ["name" => "Electronics", "created_at" => Carbon::now()],
                ["name" => "Financial services", "created_at" => Carbon::now()],
                ["name" => "Public administration", "created_at" => Carbon::now()],
                ["name" => "Aerospace", "created_at" => Carbon::now()],
                ["name" => "Lobbying", "created_at" => Carbon::now()],
                ["name" => "Digital transformation", "created_at" => Carbon::now()],
                ["name" => "Forestry", "created_at" => Carbon::now()],
                ["name" => "Digital media", "created_at" => Carbon::now()],
                ["name" => "Shipbuilding", "created_at" => Carbon::now()],
                ["name" => "Publishing", "created_at" => Carbon::now()],
                ["name" => "Entertainment", "created_at" => Carbon::now()],
                ["name" => "Website", "created_at" => Carbon::now()],
            ];
        CompaniesIndustry::insert($industries);
    }
}
