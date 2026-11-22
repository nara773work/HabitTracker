<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreeTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tree_types')->insert([
            ['name' => 'シラカバ', 'target_days' => 30, 'sort_order' => 1],
            ['name' => 'スギ', 'target_days' => 90, 'sort_order' => 2],
            ['name' => 'ケヤキ', 'target_days' => 365, 'sort_order' => 3],
            ['name' => 'ウメ', 'target_days' => 100, 'sort_order' => 4],
            ['name' => 'トチノキ', 'target_days' => 180, 'sort_order' => 5],
            ['name' => 'クスノキ', 'target_days' => 1000, 'sort_order' => 6],
            ['name' => '屋久杉', 'target_days' => 3000, 'sort_order' => 7],
]);
    }
}
