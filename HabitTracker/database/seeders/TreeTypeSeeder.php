<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreeTypeSeeder extends Seeder
{
    /**
     * 木の種類の初期データ
     */
    public function run(): void
    {
        DB::table('tree_types')->insert([
            ['name' => 'シラカバ', 'target_days' => 30, 'sort_order' => 1],
            ['name' => 'スギ', 'target_days' => 90, 'sort_order' => 2],
            ['name' => 'ウメ', 'target_days' => 100, 'sort_order' => 3],
            ['name' => 'トチノキ', 'target_days' => 180, 'sort_order' => 4],
            ['name' => 'ケヤキ', 'target_days' => 365, 'sort_order' => 5],
            ['name' => 'クスノキ', 'target_days' => 1000, 'sort_order' => 6],
        ]);
    }
}
