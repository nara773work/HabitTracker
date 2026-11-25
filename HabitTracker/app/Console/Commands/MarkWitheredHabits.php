<?php

namespace App\Console\Commands;

use App\Models\HabitRun;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class MarkWitheredHabits extends Command
{
    protected $signature = 'habits:mark-withered';

    protected $description = '2日連続で記録がない木を枯れ木にする';

    public function handle(): int
    {
        $today = Carbon::today();

        // 昨日と一昨日を確認する
        $missedDates = [
            $today->copy()->subDay(),
            $today->copy()->subDays(2),
        ];

        $updatedCount = 0;

        HabitRun::query()
            ->where('status', 'ongoing')
            ->chunkById(100, function ($runs) use ($missedDates, &$updatedCount) {
                foreach ($runs as $run) {
                    $activeSince = Carbon::parse(
                        $run->restart_on ?? $run->started_on
                    )->startOfDay();

                    $missedBothDays = true;

                    foreach ($missedDates as $date) {
                        // 開始前の日、または達成ログがある日はスキップ扱いにしない
                        if (
                            $date->lt($activeSince) ||
                            $run->habitLogs()
                                ->whereDate('completed_on', $date)
                                ->exists()
                        ) {
                            $missedBothDays = false;
                            break;
                        }
                    }

                    if ($missedBothDays) {
                        $run->status = 'withered';
                        $run->save();

                        $updatedCount++;
                    }
                }
            });

        $this->info("枯れ木に変更した記録: {$updatedCount}件");

        return self::SUCCESS;
    }
}