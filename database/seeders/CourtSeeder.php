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
        $generateSvg = function (string $text, string $bgColor = '#1e293b', string $textColor = '#38bdf8') {
            $svg = '<svg width="800" height="500" xmlns="http://www.w3.org/2000/svg">'
                 . '<rect width="100%" height="100%" fill="' . $bgColor . '"/>'
                 . '<circle cx="400" cy="250" r="140" fill="none" stroke="' . $textColor . '" stroke-width="6" opacity="0.25"/>'
                 . '<text x="50%" y="50%" fill="' . $textColor . '" dominant-baseline="middle" text-anchor="middle" font-size="34" font-weight="bold" font-family="sans-serif">' . htmlspecialchars($text) . '</text>'
                 . '</svg>';
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        };

       $courts = [
            // 1. Futsal / Sepakbola
            [
                'user_id'     => 1,
                'name'        => 'Lapangan Futsal VVIP Arena',
                'category'    => 'Futsal / Sepakbola',
                'photo'       => $generateSvg('Futsal VVIP Arena', '#05381a', '#ccff00'),
                'description' => 'Lapangan Futsal Indoor Rumput Sintetis Super Premium dengan pencahayaan LED standar kompetisi.',
                'is_active'   => true,
                'facilities'  => 'WiFi, Locker Room, Shower Air Hangat, Kantin',
                'location'    => 'Jakarta Selatan',
                'rules'       => 'Wajib sepatu futsal sol karet, Dilarang merokok di area lapangan',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Champion Futsal Vinyl Court',
                'category'    => 'Futsal / Sepakbola',
                'photo'       => $generateSvg('Champion Vinyl Futsal', '#064e3b', '#6ee7b7'),
                'description' => 'Lantai vinyl tebal anti-slip berstandar internasional, sangat ramah untuk lutut dan ankle.',
                'is_active'   => true,
                'facilities'  => 'AC Spot, Toilet, Sound System, P3K',
                'location'    => 'Jakarta Barat',
                'rules'       => 'Hanya sepatu sol karet bersih / non-marking',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Garuda Futsal Centre',
                'category'    => 'Futsal / Sepakbola',
                'photo'       => $generateSvg('Garuda Futsal Centre', '#14532d', '#86efac'),
                'description' => 'Venue futsal dengan 3 lapangan sintetis import dan tribun penonton kapasitas 200 orang.',
                'is_active'   => true,
                'facilities'  => 'Parkir Luas, Musholla, Kantin, Tribun',
                'location'    => 'Kota Tangerang Selatan, Banten',
                'rules'       => 'Dilarang membawa makanan dan minuman ke dalam area permainan',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Urban Rooftop Futsal',
                'category'    => 'Futsal / Sepakbola',
                'photo'       => $generateSvg('Urban Rooftop Futsal', '#15803d', '#fef08a'),
                'description' => 'Sensasi main futsal di lantai atas gedung dengan pemandangan kota dan jaring pengaman penuh.',
                'is_active'   => true,
                'facilities'  => 'Cafe, Rooftop Lounge, Ruang Ganti, Toilet',
                'location'    => 'Jakarta Pusat',
                'rules'       => 'Bermain tertib, dilarang memanjat jaring pengaman',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Camp Nou Mini Soccer Field',
                'category'    => 'Futsal / Sepakbola',
                'photo'       => $generateSvg('Camp Nou Mini Soccer', '#065f46', '#a7f3d0'),
                'description' => 'Mini soccer 7 vs 7 beralas rumput sintetis monofilament setinggi 5cm lengkap dengan butiran karet.',
                'is_active'   => true,
                'facilities'  => 'Loker Pemain, Wasit Tersedia, Bench Tim Standar Liga',
                'location'    => 'Jakarta Timur',
                'rules'       => 'Wajib sepatu pull karet (TF/AG), dilarang sepatu besi (SG)',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Stamford Mini Soccer Pitch',
                'category'    => 'Futsal / Sepakbola',
                'photo'       => $generateSvg('Stamford Pitch 7v7', '#047857', '#d1fae5'),
                'description' => 'Lapangan mini soccer bertaraf nasional dengan sistem drainase bawah tanah anti-banjir.',
                'is_active'   => true,
                'facilities'  => 'Videotron Skor, Live Streaming Cam, Musholla',
                'location'    => 'Kota Tangerang Selatan, Banten',
                'rules'       => 'Dilarang merokok dan membawa flare ke area bench',
            ],

            // 2. Badminton
            [
                'user_id'     => 1,
                'name'        => 'Cobra Champion Badminton A',
                'category'    => 'Badminton',
                'photo'       => $generateSvg('Cobra Badminton A', '#1e3a8a', '#93c5fd'),
                'description' => 'Lapangan Badminton Karpet Vinyl BWF dengan sirkulasi udara dingin dan bebas silau.',
                'is_active'   => true,
                'facilities'  => 'AC, Musholla, Parkir Mobil & Motor, Ruang Ganti',
                'location'    => 'Kota Tangerang Selatan, Banten',
                'rules'       => 'Wajib sepatu khusus badminton non-marking',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Cobra Champion Badminton B',
                'category'    => 'Badminton',
                'photo'       => $generateSvg('Cobra Badminton B', '#172554', '#60a5fa'),
                'description' => 'Karpet vinyl BWF lapangan samping dengan pencahayaan samping ramah mata pemain smash.',
                'is_active'   => true,
                'facilities'  => 'Water Station, Musholla, Ruang Ganti',
                'location'    => 'Kota Tangerang Selatan, Banten',
                'rules'       => 'Dilarang menggunakan sepatu lari atau sol hitam',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Duta Smash Badminton Hall',
                'category'    => 'Badminton',
                'photo'       => $generateSvg('Duta Smash Badminton', '#1d4ed8', '#bfdbfe'),
                'description' => 'Gedung olahraga badminton berplafon tinggi (12m) dengan 6 lapangan beralas kayu parket.',
                'is_active'   => true,
                'facilities'  => 'Kantin, Toko Raket, Stringing Service, Shower',
                'location'    => 'Jakarta Timur',
                'rules'       => 'Sepatu non-marking wajib, jaga kebersihan hall',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Smash Hub Badminton Arena',
                'category'    => 'Badminton',
                'photo'       => $generateSvg('Smash Hub Arena', '#2563eb', '#e0e7ff'),
                'description' => 'Arena badminton modern dilengkapi kamera rekaman otomatis untuk review pertandingan.',
                'is_active'   => true,
                'facilities'  => 'Smart Replay Camera, AC, Ruang Tunggu VIP',
                'location'    => 'Jakarta Utara',
                'rules'       => 'Dilarang merokok, gunakan raket dan shuttlecock yang teratur',
            ],

            // 3. Basket
            [
                'user_id'     => 1,
                'name'        => 'Lapangan Basket Outdoor FIBA',
                'category'    => 'Basket',
                'photo'       => $generateSvg('Basket Outdoor FIBA', '#7c2d12', '#fdba74'),
                'description' => 'Lapangan Basket Outdoor standar FIBA dengan ring fleksibel dan tribun penonton nyaman.',
                'is_active'   => true,
                'facilities'  => 'Toilet, Kantin, Tribun, Lampu Sorot Malam',
                'location'    => 'Jakarta Barat',
                'rules'       => 'Gunakan pakaian dan sepatu olahraga yang sesuai',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Downtown Indoor Basketball',
                'category'    => 'Basket',
                'photo'       => $generateSvg('Downtown Basketball', '#9a3412', '#fed7aa'),
                'description' => 'Lantai kayu Maple Kanada khusus indoor dengan pantulan bola sempurna dan ring hidrolik.',
                'is_active'   => true,
                'facilities'  => 'Locker Eksklusif, AC Penuh, Scoring Board Digital',
                'location'    => 'Jakarta Selatan',
                'rules'       => 'Wajib sepatu basket indoor bersih',
            ],
            [
                'user_id'     => 1,
                'name'        => 'The Hoop District 3x3',
                'category'    => 'Basket',
                'photo'       => $generateSvg('The Hoop District 3x3', '#c2410c', '#ffedd5'),
                'description' => 'Khusus permainan 3x3 setengah lapangan dengan cat akrilik anti-licin dan soundbeat kekinian.',
                'is_active'   => true,
                'facilities'  => 'Bluetooth Speaker, Bench, Dispenser Air',
                'location'    => 'Kota Tangerang, Banten',
                'rules'       => 'Maksimal 6 pemain di dalam court per sesi',
            ],

            // 4. Tenis
            [
                'user_id'     => 1,
                'name'        => 'Senayan Hard Court Tennis',
                'category'    => 'Tenis',
                'photo'       => $generateSvg('Senayan Hard Court', '#0f766e', '#5eead4'),
                'description' => 'Lapangan tenis tipe hard court dengan lapisan acrylic cushion 5 layer untuk pantulan akurat.',
                'is_active'   => true,
                'facilities'  => 'Ball Boy Service, Floodlight LED, Rest Area Teduh',
                'location'    => 'Jakarta Pusat',
                'rules'       => 'Wajib pakaian tenis berkerah dan sepatu tenis',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Clay Court Elite Tennis Club',
                'category'    => 'Tenis',
                'photo'       => $generateSvg('Elite Clay Tennis', '#991b1b', '#fca5a5'),
                'description' => 'Satu-satunya lapangan tenis tanah liat (red clay) standar Roland Garros di area kota.',
                'is_active'   => true,
                'facilities'  => 'Sauna, Ruang Pijat Olahraga, Bar Minuman Isotonik',
                'location'    => 'Jakarta Selatan',
                'rules'       => 'Wajib sikat lapangan sendiri setelah selesai bermain',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Metro Squash Arena Glass Back',
                'category'    => 'Tenis',
                'photo'       => $generateSvg('Metro Squash Arena', '#374151', '#e5e7eb'),
                'description' => 'Lapangan squash dengan dinding belakang kaca transparan standar WSF dan lantai kayu pegas.',
                'is_active'   => true,
                'facilities'  => 'Kacamata Pelindung Gratis, AC Central, Kursi Penonton',
                'location'    => 'Jakarta Pusat',
                'rules'       => 'Wajib kacamata pelindung dan bola titik kuning',
            ],

            // 5. Padel
            [
                'user_id'     => 1,
                'name'        => 'Apex Padel Court 1 (Panoramic)',
                'category'    => 'Padel',
                'photo'       => $generateSvg('Apex Padel Court 1', '#4c1d95', '#c4b5fd'),
                'description' => 'Lapangan Padel tenis kaca panoramic tanpa tiang halangan di sudut lapangan, rumput monofilament.',
                'is_active'   => true,
                'facilities'  => 'Rental Raket Carbon, Shower Hangat, Coffee Shop',
                'location'    => 'Jakarta Selatan',
                'rules'       => 'Gunakan tali safety raket saat mengayun',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Apex Padel Court 2 (Standard)',
                'category'    => 'Padel',
                'photo'       => $generateSvg('Apex Padel Court 2', '#5b21b6', '#ddd6fe'),
                'description' => 'Court padel standar turnamen WPT dengan pencahayaan anti-silau untuk game malam hari.',
                'is_active'   => true,
                'facilities'  => 'Lounge, Free Towel, Ruang Ganti',
                'location'    => 'Jakarta Selatan',
                'rules'       => 'Dilarang membenturkan tubuh ke kaca dengan sengaja',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Volley Beach Oasis Sand Court',
                'category'    => 'Futsal / Sepakbola',
                'photo'       => $generateSvg('Oasis Beach Volley', '#854d0e', '#fde047'),
                'description' => 'Pasir kuarsa putih halus setebal 40cm, serasa bermain voli pantai profesional di tengah kota.',
                'is_active'   => true,
                'facilities'  => 'Kran Bilas Pasir, Shower Outdoor, Payung Pantai',
                'location'    => 'Jakarta Utara',
                'rules'       => 'Dilarang membawa benda tajam dan botol kaca ke arena pasir',
            ],
            [
                'user_id'     => 1,
                'name'        => 'Ultimate Multisport Training Hall',
                'category'    => 'Futsal / Sepakbola',
                'photo'       => $generateSvg('Multisport Hall', '#1e293b', '#f87171'),
                'description' => 'Hall serbaguna yang bisa dikonfigurasi untuk Futsal, Voli Indoor, Badminton, maupun Tenis Meja.',
                'is_active'   => true,
                'facilities'  => 'Peralatan Multi-cabor, Ruang Medis, Ruang Wasit',
                'location'    => 'Kota Tangerang, Banten',
                'rules'       => 'Peminjaman net harus dikembalikan dalam kondisi rapi',
            ],
        ];

        foreach ($courts as $court) {
            Court::updateOrCreate(
                ['name' => $court['name']],
                $court
            );
        }

        foreach ($courts as $court) {
            Court::updateOrCreate(
                ['name' => $court['name']],
                $court
            );
        }
    }
}
