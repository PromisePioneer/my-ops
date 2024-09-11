<?php

namespace Database\Seeders;

use App\Models\BroadbandPacket;
use Illuminate\Database\Seeder;

class BroadbandPacketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BroadbandPacket::create([
            'name' => 'MY FIBER STARTER',
            'capacity' => 15,
            'price' => 150000,
        ]);
        BroadbandPacket::create([
            'name' => 'MY FIBER BASIC',
            'capacity' => 20,
            'price' => 200000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER BASIC PLUS',
            'capacity' => 25,
            'price' => 209099,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER HOME',
            'capacity' => 30,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER PRO',
            'capacity' => 45,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER ULTIMA',
            'capacity' => 60,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY CCTV BASIC PLUS',
            'capacity' => 75,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER SILVER',
            'capacity' => 100,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER GOLD',
            'capacity' => 100,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER DIAMOND',
            'capacity' => 100,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER CCTV SILVER',
            'capacity' => 100,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER CCTV GOLD',
            'capacity' => 100,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER CCTV DIAMOND',
            'capacity' => 100,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER SOHO 10',
            'capacity' => 100,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER SOHO 20',
            'capacity' => 100,
            'price' => 300000,
        ]);

        BroadbandPacket::create([
            'name' => 'MY FIBER SOHO 30',
            'capacity' => 100,
            'price' => 300000,
        ]);
    }
}
