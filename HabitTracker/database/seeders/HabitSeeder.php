<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HabitSeeder extends Seeder
{
    /**
     * 習慣の初期データ
     *
     * 処理の流れは以下の通り
     * 習慣を登録→木の種類を探す→木の成長記録を登録→達成ログを登録
     */
    public function run(): void
    {
        $today = Carbon::today();

        $samples = [
            [
                'name' => '毎朝5時に起きる',
                'tree' => 'トチノキ',
                'count' => 179,
                'status' => 'ongoing',
                'timing' => '朝',
                'skip_days' => 1,
            ],
            [
                'name' => '10分間読書する',
                'tree' => 'ウメ',
                'count' => 50,
                'status' => 'ongoing',
                'timing' => '就寝前',
                'skip_days' => 0,
            ],
            [
                'name' => '毎日ストレッチする',
                'tree' => 'ケヤキ',
                'count' => 5,
                'status' => 'withered',
                'timing' => 'お風呂上り',
                'skip_days' => 2,
            ],
            [
                'name' => '英語の学習をする',
                'tree' => 'スギ',
                'count' => 38,
                'status' => 'ongoing',
                'timing' => '朝食後',
                'skip_days' => 0,
            ],
            [
                'name' => '毎日8000歩歩く',
                'tree' => 'シラカバ',
                'count' => 30,
                'status' => 'completed',
                'timing' => '朝、夜の散歩',
                'skip_days' => 1,
            ],
        ];

        foreach ($samples as $sample) {
            // habitsに習慣名を登録し、そのIDを取得する
            $habitId = DB::table('habits')->insertGetId([
                'name' => $sample['name'],
                'timing' => $sample['timing'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 登録済みの木の種類を取得
            $treeType = DB::table('tree_types')
                ->where('name', $sample['tree'])
                ->first();

            $count = $sample['count'];
            $skipDays = $sample['skip_days'];

            // 達成ログを作り始める日
            $startedOn = $today->copy()->subDays($count + $skipDays);

            // habit_runsに木の成長状態を登録し、そのIDを取得する
            $habitRunId = DB::table('habit_runs')->insertGetId([
                'habit_id' => $habitId,
                'tree_type_id' => $treeType->id,
                'started_on' => $startedOn->toDateString(),
                'restart_on' => null,
                'ring_count' => $sample['count'],
                'recovery_count' => 0,
                'status' => $sample['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 達成した日ごとにhabit_logsを登録（カウントの数だけログを作る）
            $logs = [];

            for ($i = 0; $i < $count; $i++) {
                $logs[] = [
                    'habit_run_id' => $habitRunId,
                    'completed_on' => $startedOn->copy()
                        ->addDays($i)
                        ->toDateString(),
                    'attempt_number' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (! empty($logs)) {
                DB::table('habit_logs')->insert($logs);
            }
        }
    }
}
