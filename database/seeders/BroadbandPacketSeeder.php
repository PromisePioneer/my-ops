<?php

namespace Database\Seeders;

use App\Models\BroadbandPacket;
use Illuminate\Database\Seeder;

class BroadbandPacketSeeder extends Seeder
{
    public function run(): void
    {
        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER STARTER',
            'capacity' => 15,
            'price' => 166500,
        ]);
        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER BASIC',
            'capacity' => 20,
            'price' => 222000,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER BASIC PLUS',
            'capacity' => 25,
            'price' => 232100,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER HOME',
            'capacity' => 30,
            'price' => 333000,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER PRO',
            'capacity' => 45,
            'price' => 444000,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER ULTIMA',
            'capacity' => 60,
            'price' => 555000,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY CCTV BASIC PLUS',
            'capacity' => 75,
            'price' => 282600,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY CCTV HOME',
            'capacity' => 75,
            'price' => 383500,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER SILVER',
            'capacity' => 100,
            'price' => 252300,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER GOLD',
            'capacity' => 100,
            'price' => 353200,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER DIAMOND',
            'capacity' => 100,
            'price' => 454100,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER CCTV SILVER',
            'capacity' => 100,
            'price' => 302800,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER CCTV GOLD',
            'capacity' => 100,
            'price' => 403700,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER CCTV DIAMOND',
            'capacity' => 100,
            'price' => 504600,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER SOHO 10',
            'capacity' => 100,
            'price' => 1500000,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER SOHO 20',
            'capacity' => 100,
            'price' => 3000000,
        ]);

        BroadbandPacket::create([
            'branch_id' => 1,
            'name' => 'MY FIBER SOHO 30',
            'capacity' => 100,
            'price' => 4500000,
        ]);
    }
}
