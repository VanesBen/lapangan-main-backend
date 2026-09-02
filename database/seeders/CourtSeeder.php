<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyBase64 = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAwIiBoZWlnaHQ9IjUwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjMDUzODFhIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZpbGw9IiNjY2ZmMDAiIGRvbWluYW50LWJhc2VsaW5lPSJtaWRkbGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZvbnQtc2l6ZT0iNDAiIGZvbnQtZmFtaWx5PSJzYW5zLXNlcmlmIj5MYXBhbmdhbiBNYWluPC90ZXh0Pjwvc3ZnPg==';

        $courts = [
            [
                'name'        => 'Lapangan Futsal VVIP',
                'photo'       => $dummyBase64,
                'description' => 'Lapangan Futsal Indoor Rumput Sintetis Super Premium dengan pencahayaan LED standar kompetisi.',
                'is_active'   => 'true',
                'facilities'  => 'WiFi, Locker Room, Shower Air Hangat, Kantin',
                'location'    => 'Jakarta Selatan',
                'rules'       => 'Wajib sepatu futsal sol karet, Dilarang merokok di area lapangan',
            ],
            [
                'name'        => 'Cobra Champion Badminton',
                'photo'       => $dummyBase64,
                'description' => 'Lapangan Badminton Karpet Vinyl BWF dengan sirkulasi udara dingin dan nyaman.',
                'is_active'   => 'true',
                'facilities'  => 'AC, Musholla, Parkir Mobil & Motor, Ruang Ganti',
                'location'    => 'Kota Tangerang Selatan, Banten',
                'rules'       => 'Wajib sepatu khusus badminton non-marking',
            ],
            [
                'name'        => 'Lapangan Basket Outdoor',
                'photo'       => $dummyBase64,
                'description' => 'Lapangan Basket Outdoor standar FIBA dengan ring fleksibel dan tribun penonton.',
                'is_active'   => 'true',
                'facilities'  => 'Toilet, Kantin, Tribun, Lampu Sorot Malam',
                'location'    => 'Jakarta Barat',
                'rules'       => 'Gunakan pakaian dan sepatu olahraga yang sesuai',
            ],
        ];

        foreach ($courts as $court) {
            Court::create($court);
        }
    }
}
