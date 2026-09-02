<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\PricingRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courts = Court::all();

        foreach ($courts as $court) {
            // Aturan Weekday (Senin - Jumat)
            PricingRule::create([
                'courts_id'      => $court->id,
                'day_type'       => 'weekday',
                'start_hour'     => 8,     
                'end_hour'       => 17,    
                'price_per_hour' => 75000,
            ]);

            PricingRule::create([
                'courts_id'      => $court->id,
                'day_type'       => 'weekday',
                'start_hour'     => 17,    
                'end_hour'       => 23,    
                'price_per_hour' => 100000,
            ]);

            // Aturan Weekend (Sabtu - Minggu)
            PricingRule::create([
                'courts_id'      => $court->id,
                'day_type'       => 'weekend',
                'start_hour'     => 8,
                'end_hour'       => 23,
                'price_per_hour' => 120000, // Flat weekend
            ]);
    }
    }
}